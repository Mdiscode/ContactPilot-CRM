## ✅ Step 1: Create the Service
## Create the file: app/Services/GoogleContactService.php
```
<?php

namespace App\Services;

use Google\Client;
use Google\Service\PeopleService;
use Google\Service\PeopleService\Person;
use Google\Service\PeopleService\Name;
use Google\Service\PeopleService\EmailAddress;
use Google\Service\PeopleService\PhoneNumber;
use App\Models\AuthUser;
use App\Models\ContactList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GoogleContactService
{
    public function createGoogleClient()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(PeopleService::CONTACTS);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        return $client;
    }

    public function getPeopleService()
    {
        $token = AuthUser::latest()->first();
        $client = $this->createGoogleClient();
        $client->setAccessToken([
            'access_token' => $token->access_token,
            'expires_in' => $token->expires_at,
            'refresh_token' => $token->refresh_token,
            'refresh_token_expires_in' => $token->refresh_token_expires
        ]);

        if (Carbon::now()->greaterThan(Carbon::parse($token->expires_at))) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($token->refresh_token);
            if (isset($newToken['access_token'])) {
                AuthUser::create([
                    'access_token' => $newToken['access_token'],
                    'expires_at' => now()->addSeconds($newToken['expires_in']),
                    'refresh_token' => $token->refresh_token,
                    'refresh_token_expires' => $token->refresh_token_expires
                ]);
                $client->setAccessToken($newToken);
            }
        }

        return new PeopleService($client);
    }

    public function fetchGoogleContacts()
    {
        $peopleService = $this->getPeopleService();
        $connections = $peopleService->people_connections->listPeopleConnections('people/me', [
            'personFields' => 'names,emailAddresses,phoneNumbers',
            'pageSize' => 1000
        ]);
        return $connections->getConnections();
    }

    public function syncGoogleToDB()
    {
        $contacts = $this->fetchGoogleContacts();
        $synced = $failed = $updated = 0;

        foreach ($contacts as $person) {
            $name = $person->getNames()[0]->getDisplayName() ?? null;
            $email = $person->getEmailAddresses()[0]->getValue() ?? null;
            $phone = $person->getPhoneNumbers()[0]->getValue() ?? null;
            $resourceName = $person->getResourceName() ?? null;
            $etag = $person->getEtag() ?? null;

            if (!$name && !$email && !$phone) continue;

            $existing = ContactList::where('phone', $phone)
                ->orWhere('resourcesName', $resourceName)
                ->first();

            if (!$existing) {
                try {
                    ContactList::create([
                        'contact_name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'resourcesName' => $resourceName,
                        'etag' => $etag
                    ]);
                    $synced++;
                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Failed to insert: " . $e->getMessage());
                }
            } else {
                $existing->contact_name = $name;
                $existing->email = $email;
                $existing->etag = $etag;
                if ($existing->isDirty()) {
                    try {
                        $existing->save();
                        $updated++;
                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Failed to update: " . $e->getMessage());
                    }
                }
            }
        }

        return compact('synced', 'updated', 'failed');
    }

    public function syncDBToGoogle()
    {
        $service = $this->getPeopleService();
        $synced = $failed = 0;

        foreach (ContactList::whereNull('resourcesName')->get() as $contact) {
            $person = new Person([
                'names' => [new Name(['givenName' => $contact->contact_name])],
                'emailAddresses' => [new EmailAddress(['value' => $contact->email])],
                'phoneNumbers' => [new PhoneNumber(['value' => $contact->phone])]
            ]);

            try {
                $created = $service->people->createContact($person);
                $contact->resourcesName = $created->getResourceName();
                $contact->etag = $created->getEtag();
                $contact->save();
                $synced++;
            } catch (\Exception $e) {
                $failed++;
                Log::error("Create contact failed: " . $e->getMessage());
            }
        }

        return compact('synced', 'failed');
    }
}

```

## ✅ Step 2: Refactor Your Controller
## Update your GoogleSynController:
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleContactService;
use App\Models\ContactList;

class GoogleSynController extends Controller
{
    protected $googleService;

    public function __construct(GoogleContactService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function redirectToGoogle()
    {
        $client = $this->googleService->createGoogleClient();
        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        // You can still write callback token logic here OR
        // move it to the service if desired
    }

    public function syncGoogleToDB()
    {
        $result = $this->googleService->syncGoogleToDB();
        $count = ContactList::count();

        return view('googleContact.index', array_merge($result, ['count' => $count]));
    }

    public function syncDBToGoogle()
    {
        $result = $this->googleService->syncDBToGoogle();
        $count = ContactList::count();

        return view('googleContact.index', array_merge($result, ['count' => $count]));
    }
}

```
## GoogleContactSync
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\PeopleService;
use Illuminate\Support\Facades\Session;
use App\Models\GoogleToken;
use Carbon\Carbon;
// use Auth;
use App\Models\ContactList;
use App\Models\AuthUser;
use Google\Service\PeopleService\Person;
use Google\Service\PeopleService\Name;
use Google\Service\PeopleService\EmailAddress;
use Google\Service\PeopleService\PhoneNumber;
use Google\Service\PeopleService\Gender;
use Google\Service\PeopleService\Address;
use Google\Service\PeopleService\Birthday;
use Google\Service\PeopleService\Location;
use Google\Service\PeopleService\Relation;
class GoogleSynController extends Controller
{
    public $total = 0;
    public  $synced = 0;
    public $failed = 0;
    public $update = 0;
    private function createGoogleClient()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(PeopleService::CONTACTS);
        $client->setAccessType('offline'); // <-- Important for refresh token
        $client->setPrompt('consent');     // <-- Always ask for permission
        

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->createGoogleClient();
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $client = $this->createGoogleClient();
        //fetch--token-----
        $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));
        
        session('token',$token);

        if (isset($token['error'])) {
            return response()->json(['error' => $token['error_description'] ?? 'Unknown error'], 403);
        }
        
    // ----------store token in DB-------------
        $AuthUser = AuthUser::create([
          "access_token"=>$token['access_token'],
          "expires_at"=>$token['expires_in'],
          "refresh_token"=>$token['refresh_token'],
          "refresh_token_expires"=>$token['refresh_token_expires_in']
        ]);
       
    // ---------------get token from DB-------------------------:
        $getToken = AuthUser::orderBy('id','desc')->first();

        $client->setAccessToken([
            'access_token'=>$getToken->access_token,
            'expires_in'=>$getToken->expires_at,
            'refresh_token'=>$getToken->refresh_token,
            'refresh_token_expires_in'=>$getToken->refresh_token_expires
        ]);

         //generate new token
         if (Carbon::now()->greaterThan(Carbon::parse($getToken->expires_ac_token))) {
            // Token is expired, refresh it
            $newToken = $client->fetchAccessTokenWithRefreshToken($getToken->refresh_token);

            if (isset($newToken['access_token'])) {
                AuthUser::create([
                    'access_token'=>$getToken->access_token,
                    'expires_at'=>$getToken->expires_at,
                    'refresh_token'=>$getToken->refresh_token,
                    'refresh_token_expires_in'=>$getToken->refresh_token_expires,
                ]);
        
                $client->setAccessToken($newToken);
            }
        }
        ///call Api
        $peopleService = new PeopleService($client);

        //----------get the data from google contact----------------
        $connections = $peopleService->people_connections->listPeopleConnections('people/me', [
            'personFields' => 'names,emailAddresses,phoneNumbers',
        ]);

        // Get the actual contacts
                $googleContacts = $connections->getConnections();
            //   ------count--google--contact---------
                $totalGoogleContacts = count($googleContacts);

                $count = ContactList::count(); //count--total--contact
                $currentDate = now()->toDateString();  //fetch--current--date--
                $createdTodayCount = ContactList::whereDate('created_at', $currentDate)->count();

                return view('googleContact.index',compact('totalGoogleContacts','getToken','count','createdTodayCount'));
    
    }


```

## Sync-GoogleContact--to-Database
```
//-----------------------------Sync Google Contacts to Database-------------------------------------------------

        public function syncGoogleToDB()
        {
            $getToken = AuthUser::orderBy('id','desc')->first();
           $client = $this->createGoogleClient();
            $client->setAccessToken([
                'access_token'=>$getToken->access_token,
                'expires_in'=>$getToken->expires_at,
                'refresh_token'=>$getToken->refresh_token,
                'refresh_token_expires_in'=>$getToken->refresh_token_expires
            ]);

           
            $peopleService = new PeopleService($client);
            $connections = $peopleService->people_connections->listPeopleConnections(
                'people/me',
                [   'pageSize' => 1,
                    'personFields' => 'names,emailAddresses,phoneNumbers',

                ]
            );
         // Get the actual contacts
         $googleContacts = $connections->getConnections();
         $totalGoogleContacts = count($googleContacts);
         $count = ContactList::count();
        

//get the data
foreach ($connections->getConnections() as $person) {
    $total++;
        $resourceName = $person->getResourceName() ?? null; // resourcesname
        $etag = $person->getEtag() ?? null;
        
$name = null;
$family = null;
if ($person->getNames() && isset($person->getNames()[0])) {
    $name = $person->getNames()[0]->getDisplayName();
    $family = $person->getNames()[0]->getFamilyName();
}

// Get email
$email = null;
if ($person->getEmailAddresses() && isset($person->getEmailAddresses()[0])) {
    $email = $person->getEmailAddresses()[0]->getValue();
}

// Get phone
$phone = null;
if ($person->getPhoneNumbers() && isset($person->getPhoneNumbers()[0])) {
    $phone = $person->getPhoneNumbers()[0]->getValue();
}

        // $gender = $person->getGenders()[0]->getValue() ?? null;
        
        // // Corrected the method name from getValu() to getValue()
        // $addressValue = $person->getAddresses()[0]->getValue() ?? null;
        // $addressType = $person->getAddresses()[0]->getType() ?? null;

        // // Birthdate should be accessed correctly
        // $birthdays = $person->getBirthdays()[0]->getDate() ?? null; // Use getBirthdays() instead of getBirthdate()

        // // Corrected method names for location and relations
        // $location = $person->getLocations()[0]->getValue() ?? null; // Use getLocations() instead of getLocation()
        // $relations = $person->getRelations()[0]->getValue() ?? null; // Use getRelations() instead of getRelation()
        if ($name || $email || $phone || $resourceName || $etag  || $family) {
             
            //check--duplication
            $failedContacts = [];
             
            $existingContact = ContactList::where(function ($query) use ($phone, $resourceName) {
                $query->where('phone', $phone);
            
                if (!empty($resourceName)) {
                    $query->orWhere('resourcesName', $resourceName);
                }
            })->first();
            
            
            if (!$existingContact) {
                try {

                    ContactList::updateOrCreate(
                        ['email' => $email],
                        [
                            'contact_name'  => $name,
                            'phone'         => $phone,
                            'resourcesName' => $resourceName ?? null,
                            'etag'          => $etag ?? null,
                            'family_org_name'=>$family,
                        ]
                    ); 
            
                    $this->synced++;
                } catch (\Exception $e) {
                    $this->failed++;
                    $this->failedContacts[] = [
                        'name'  => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'error' => $e->getMessage(),
                    ];
                }
            } else {
                  // Contact exists by phone, update specific fields
                $existingContact->contact_name    = $name;
                $existingContact->email           = $email;
                $existingContact->resourcesName   = $resourceName ?? $existingContact->resourcesName;
                $existingContact->etag            = $etag ?? $existingContact->etag;
                $existingContact->family_org_name = $family ?? $existingContact->family_org_name;
       // Only update if any field has changed
            if ($existingContact->isDirty()) {
                try {
                    $existingContact->save();
                    $this->update++; // increment only when actual changes were made
                } catch (\Exception $e) {
                    $this->failed++;
                    $failedContacts[] = [
                        'name'  => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'error' => $e->getMessage(),
                    ];
                }
            }
            }
            
        }
    }


    $pending = $this->total - $this->synced - $this->failed;
    return view('googleContact.index',compact('pending','update','synced','failed','count','totalGoogleContacts'));
}
```

## databaseToGoogleSync
```
// -------------------//-----------SYNCE---TO--DB_to_Google-----------\\--------------------------------------------
        public function syncDBToGoogle()
        {
            $getToken = AuthUser::orderBy('id','desc')->first();
        $client = $this->createGoogleClient();
            // $client->setAccessToken(session('token'));
            $client->setAccessToken([
                'access_token'=>$getToken->access_token,
                'expires_in'=>$getToken->expires_at,
                'refresh_token'=>$getToken->refresh_token,
                'refresh_token_expires_in'=>$getToken->refresh_token_expires
            ]);
        
// Create a new instance of the PeopleService
$service = new PeopleService($client);
//get the record from DB
$count = ContactList::count();


$changedContacts = [];

$total = 0;
$synced = 0;
$failed = 0;
$update = 0;

 //count-all-record
// ---------------create--new--contact---------------------------------------
foreach (ContactList::wherenull('resourcesName')->get() as $dbContact) {
    $person = new Person([
        'names' => [
            new Name([
                'givenName'  => $dbContact->contact_name,
                'familyName' => $dbContact->family_org_name,
            ])
        ],
        'emailAddresses' => [
            new EmailAddress(['value' => $dbContact->email])
        ],
        'phoneNumbers' => [
            new PhoneNumber(['value' => $dbContact->phone])
        ]
    ]);
// dd($person);
    try {
        $createdPerson = $service->people->createContact($person);
         $totalGoogleContacts = 50;
        // Save the resourceName and etag
        $dbContact->resourcesName = $createdPerson->getResourceName();
        $dbContact->etag = $createdPerson->getEtag();
        $dbContact->save();

        $synced++;
        \Log::info("Created new contact in Google: {$dbContact->contact_name}");
    } catch (\Exception $e) {
        $failed++;
        \Log::error("Failed to create contact: " . $e->getMessage());
    }
}
// Calculate pending contacts
// print_r('dbtogoogle');
$pending = $total - $synced - $failed;
   $this->UpdateDbToGoogle($pending,$synced,$failed);
return view('googleContact.index', compact('pending','synced', 'failed', 'count'));
        
}
```




//------------- ------------Update-Db-to--Google---Contact---------------------------------------------


public function UpdateDbToGoogle($pending,$synced){
    $getToken = AuthUser::orderBy('id','desc')->first();
    $client = $this->createGoogleClient();
        // $client->setAccessToken(session('token'));
        $client->setAccessToken([
            'access_token'=>$getToken->access_token,
            'expires_in'=>$getToken->expires_at,
            'refresh_token'=>$getToken->refresh_token,
            'refresh_token_expires_in'=>$getToken->refresh_token_expires
        ]);
        // Create a new instance of the PeopleService
        $service = new PeopleService($client);
            // 1. Fetch all Google contacts (1000 max)
        $googleContacts = $service->people_connections->listPeopleConnections(
            'people/me',
            [
                'pageSize' =>100,
                'personFields' => 'names,emailAddresses,phoneNumbers'
            ]
        );
        // dd($googleContacts);
        $totalGoogleContacts = count($googleContacts);
       // Contact exists → Check for updates
         $count = ContactList::count();

         $googleMap = [];

         // Create a map of Google Contacts by their resource name
         foreach ($googleContacts->getConnections() as $gContact) {
             $googleMap[$gContact->getResourceName()] = $gContact;
         }
        //  print_r("google".$googleMap);
         
         // Retrieve database contacts where created_at is less than updated_at
        //  $dbContacts = ContactList::whereColumn('created_at', '<', 'updated_at')->get();
         $total = 0;
        $synced = 0;
        $failed = 0;
        $update = 0;
         foreach (ContactList::all() as $dbContact) {
             $total++;
            //  dd($dbContact->contact_name);
            // dd("updatefunc",$total)
             $resourcesName = $dbContact->resourcesName;
            //  print_r($resourcesName);
            // print_r("dbrosurces".$resourcesName);
             // Check if the Google contact exists in the map
             if (!isset($googleMap[$resourcesName])) {
                 \Log::warning("Google contact not found for resource name: {$resourcesName}");
                 continue; // Skip to the next contact if not found
             }
                
             $gContact = $googleMap[$resourcesName];
            //  print_r($gContact);
             
             $gName = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getGivenName() : '';
             $gFamily = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getFamilyName() : '';
             $gEmail = isset($gContact->getEmailAddresses()[0]) ? $gContact->getEmailAddresses()[0]->getValue() : '';
             $gPhone = isset($gContact->getPhoneNumbers()[0]) ? $gContact->getPhoneNumbers()[0]->getValue() : '';
         
             // Check for changes between the database and Google contact
             if (
                 $dbContact->contact_name !== $gName ||
                 $dbContact->family_org_name !== $gFamily ||
                 $dbContact->email !== $gEmail ||
                 $dbContact->phone !== $gPhone
             ) {
                 // Create a new Person object for updating
                 $person = new \Google\Service\PeopleService\Person();
                    $person->setResourceName($dbContact->resourcesName);
                    $person->setEtag($dbContact->etag);
               
                    // Set name
                    $name = new \Google\Service\PeopleService\Name();
                    $name->setGivenName($dbContact->contact_name);
                    $name->setFamilyName($dbContact->family_org_name);
                    $person->setNames([$name]);
                
                    // Set email
                    $email = new \Google\Service\PeopleService\EmailAddress();
                    $email->setValue($dbContact->email);
                    $person->setEmailAddresses([$email]);

                    // Set phone
                    $phone = new \Google\Service\PeopleService\PhoneNumber();
                    $phone->setValue($dbContact->phone);
                    $person->setPhoneNumbers([$phone]);

                            
                 try {
                     // Update the contact in Google
                     $updated = $service->people->updateContact(
                         $resourcesName,
                         $person,
                         ['updatePersonFields' => 'names,emailAddresses,phoneNumbers']
                     );
         
                     // Update the local database contact's etag
                     $dbContact->etag = $updated->getEtag();
                     $dbContact->save();
         
                     $update++;
                     \Log::info("Updated contact in Google: {$dbContact->contact_name}");
                 } catch (\Exception $e) {
                     $failed++;
                     \Log::error("Failed to update contact: " . $e->getMessage());
                 }
             }
         }
      $pending = $total - $update - $failed;
    //   return [$pending,$update,$failed,$totalGoogleContacts];
    }
    
}
```
