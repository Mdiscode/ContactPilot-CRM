<?php
// app/Http/Controllers/GoogleContactController.php


namespace App\Http\Controllers;

use Carbon\Carbon;
use Google\Client;
use App\Models\AuthUser;
use App\Models\ContactList;
use App\Models\GoogleToken;
use Illuminate\Http\Request;
use App\Services\GoogleService;
use Google\Service\PeopleService;


use App\Jobs\SyncContactToGoogleJob;
use Illuminate\Support\Facades\Bus;
use Google\Service\PeopleService\Name;
use Illuminate\Support\Facades\Session;
use Google\Service\PeopleService\Gender;
use Google\Service\PeopleService\Person;
use Google\Service\PeopleService\Address;
use Google\Service\PeopleService\Birthday;
use Google\Service\PeopleService\Location;
use Google\Service\PeopleService\Relation;
use Illuminate\Foundation\Bus\Dispatchable;
use Google\Service\PeopleService\PhoneNumber;
use Google\Service\PeopleService\EmailAddress;

class GoogleContactController extends Controller
{
    protected $googleService;

    public function __construct(GoogleService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleService->createGoogleClient()->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        try {
            $token = $this->googleService->authenticate($request->input('code'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $googleContacts = $this->googleService->getGoogleContacts();
        $totalGoogleContacts = count($googleContacts);
        

        return view('googleContact.googleSync', compact('totalGoogleContacts', 'count'));
    }


//  ---------------------Sync--Google--to---DB-------------------------------------------------------------------

public function googleToDatabaseSync(){
    $synced=0;
    $update=0;
    $failed=0;
    $total =0;
    $count = 0;
        $getToken = AuthUser ::orderBy('id', 'desc')->first();
        $client = $this->googleService->createGoogleClient();
        
        $client->setAccessToken([
            'access_token' => $getToken->access_token,
            'expires_in' => $getToken->expires_at,
            'refresh_token' => $getToken->refresh_token,
            'refresh_token_expires_in' => $getToken->refresh_token_expires
        ]);
    
        $peopleService = new PeopleService($client);
        $pageToken = null; // Initialize page token
        $totalProcessed = 0; // Track total processed contacts
    
        do {
            // Fetch contacts with pagination
            $connections = $peopleService->people_connections->listPeopleConnections(
                'people/me',
                [
                    'pageSize' =>1000, // Limit to 1000 contacts per request
                    'personFields' => 'names,emailAddresses,phoneNumbers',
                    'pageToken' => $pageToken // Use the page token for pagination
                ]
            );
    
            // Get the actual contacts
            $googleContacts = $connections->getConnections();
            $totalGoogleContacts = count($googleContacts);
            //  dd($connections);
            foreach ($googleContacts as $person) {
                $totalProcessed++;
                $total++;
                
                $resourceName = $person->getResourceName() ?? null;
                $etag = $person->getEtag() ?? null;
                
                // Correctly retrieve the name and family name
                $name = isset($person->getNames()[0]) ? $person->getNames()[0]->getDisplayName() : null;
                $family = isset($person->getNames()[0]) ? $person->getNames()[0]->getFamilyName() : null;
                $email = isset($person->getEmailAddresses()[0]) ? $person->getEmailAddresses()[0]->getValue() : null;
                $phone = isset($person->getPhoneNumbers()[0]) ? $person->getPhoneNumbers()[0]->getValue() : null;
                // Check for duplication and update or create contacts

                $existingContact = ContactList::where('resourcesName', $resourceName)->first();
                if (!$existingContact) {
                    try {
                        ContactList::create([
                            'contact_name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'resourcesName' => $resourceName,
                            'etag' => $etag,
                            'family_org_name' => $family,
                        ]);
                        $synced++;
                    } catch (\Exception $e) {
                        $failed++;
                    }
                } else {
                    // Check if there is any real change
        $isChanged = (
            $existingContact->contact_name !== $name ||
            $existingContact->email !== $email ||
            $existingContact->phone !== $phone ||
            $existingContact->etag !== $etag ||
            $existingContact->family_org_name !== $family
        );

        if ($isChanged) {
            $existingContact->update([
                'contact_name' => $name,
                'email' => $email,
                'phone' => $phone,
                'etag' => $etag,
                'family_org_name' => $family,
            ]);
            $update++;
        }
        } 
    }
    
            // Check if there is a next page
            $pageToken = $connections->getNextPageToken();
            
            // Optional: Sleep for a short duration to avoid hitting API limits
            sleep(1); // Sleep for 1 second between requests
    
        } while ($pageToken && $totalProcessed < 1000); // Continue until there are no more pages or limit reached
            $count = ContactList::count();
        $pending = $total - $synced - $failed;
        return view('googleContact.googleSync', compact('pending', 'update', 'synced', 'failed', 'count', 'totalGoogleContacts'));
    }





//  ------------------------Sync--DB--to---Google---------------------------------------------------------------------------------

public function syncAllContactsToGoogle()
{
    ContactList::chunk(40, function ($contactsChunk) {
        SyncContactToGoogleJob::dispatch($contactsChunk);
    });
    

    return  view('googleContact.googleSync');
}


}
?>