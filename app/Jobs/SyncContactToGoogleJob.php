<?php

namespace App\Jobs;

use App\Models\AuthUser;
use App\Models\ContactList;
use Google\Client;
use Google\Service\PeopleService;
use Google\Service\PeopleService\Person;
use Google\Service\PeopleService\Name;
use Google\Service\PeopleService\EmailAddress;
use Google\Service\PeopleService\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\GoogleService;
class SyncContactToGoogleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contacts;
    protected $googleService;

    public function __construct($contacts)
    {
        $this->contacts = $contacts;
    }

    public function handle(GoogleService $googleService)
    {
        $firstContact = $this->contacts[0];
        $contactName = $firstContact['contact_name']; // if array

        \Log::info("First Contact Name: " . $contactName);

        \Log::info('Handling job with contact count: ' . count($this->contacts));

         $this->googleService = $googleService;
        $client = $this->googleService->createGoogleClient();

        \Log::info('Google client created');

        $token = AuthUser::latest()->first();
        if (!$token) {
            \Log::error("No auth token available");
            return;
        }

        
        $client->setAccessToken([
            'access_token' => $token->access_token,
            'expires_in' => $token->expires_at,
            'refresh_token' => $token->refresh_token,
            'refresh_token_expires_in' => $token->refresh_token_expires
        ]);

        $service = new PeopleService($client);

        // Fetch existing Google contacts
        $googleContacts = $service->people_connections->listPeopleConnections('people/me', [
            'pageSize' => 1000,
            'personFields' => 'names,emailAddresses,phoneNumbers'
        ]);

        $googleMap = [];
        foreach ($googleContacts->getConnections() as $gContact) {
            $googleMap[$gContact->getResourceName()] = $gContact;
        }
        $totalGoogleContacts = count($googleContacts);
        $total = count($this->contacts);
        $synced = 0;
        $updated = 0;
        $failed = 0;
    foreach( $this->contacts as $contact){

        \Log::info("Processing contact: " . $contact->contact_name);
        // Create or Update logic
        if (!$contact->resourcesName) {
            $person = new Person([
                'names' => [new Name([
                    'givenName' => $contact->contact_name,
                    'familyName' => $contact->family_org_name,
                ])],
                'emailAddresses' => [new EmailAddress(['value' => $contact->email])],
                'phoneNumbers' => [new PhoneNumber(['value' => $contact->phone])],
            ]);

            try {
                $created = $service->people->createContact($person);
                $contact->resourcesName = $created->getResourceName();
                $contact->etag = $created->getEtag();
                $contact->save();
                $synced++;
                \Log::info("Created Google Contact successfully: {$contact->contact_name}");
            } catch (\Exception $e) {
                \Log::error("Failed to create: {$contact->contact_name} - " . $e->getMessage());
            }
        } else {
            $gContact = $googleMap[$contact->resourcesName] ?? null;
            if (!$gContact) {
                \Log::warning("Google contact not found for: {$contact->resourcesName}");
                return;
            }
            $gName = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getGivenName() : '';
            $gFamily = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getFamilyName() : '';
            $gEmail = isset($gContact->getEmailAddresses()[0]) ? $gContact->getEmailAddresses()[0]->getValue() : '';
            $gPhone = isset($gContact->getPhoneNumbers()[0]) ? $gContact->getPhoneNumbers()[0]->getValue() : '';
            

            if (
                $contact->contact_name !== $gName ||
                $contact->family_org_name !== $gFamily ||
                $contact->email !== $gEmail ||
                $contact->phone !== $gPhone
            ) {
                $person = new Person();
                $person->setResourceName($contact->resourcesName);
                $person->setEtag($contact->etag);
                $person->setNames([new Name([
                    'givenName' => $contact->contact_name,
                    'familyName' => $contact->family_org_name,
                ])]);
                $person->setEmailAddresses([new EmailAddress(['value' => $contact->email])]);
                $person->setPhoneNumbers([new PhoneNumber(['value' => $contact->phone])]);

                try {
                    $updated = $service->people->updateContact(
                        $contact->resourcesName,
                        $person,
                        ['updatePersonFields' => 'names,emailAddresses,phoneNumbers']
                    );
                    $contact->etag = $updated->getEtag();
            
                    $contact->save();
                    \Log::info("Updated Google Contact: {$contact->contact_name}");
                } catch (\Exception $e) {
                    \Log::error("Failed to update: {$contact->contact_name} - " . $e->getMessage());
                }
            }
        }
    }///end loop
    }
}
