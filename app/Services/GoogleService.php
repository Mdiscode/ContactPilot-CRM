<?php
 // GoogleContactSyncService.php 

 namespace App\Services;

 use Google\Client;
 use Google\Service\PeopleService;
 use App\Models\AuthUser ;
 use Carbon\Carbon;
 use App\Models\ContactList;
 use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\SyncContactToGoogleJob;
 class GoogleService
 {
     public $client;
 
     public function __construct()
     {
         $this->client = $this->createGoogleClient();
     }
 
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
 
     public function authenticate($code)
     {
         $token = $this->client->fetchAccessTokenWithAuthCode($code);
         if (isset($token['error'])) {
             throw new \Exception($token['error_description'] ?? 'Unknown error');
         }
 
         // Store token in DB
         $AuthUser = AuthUser::create([
            "access_token" => $token['access_token'],
            "expires_at" =>$token['expires_in'],
            "refresh_token" => $token['refresh_token'],
            "refresh_token_expires" => $token['refresh_token_expires_in'] ?? null
        ]);
        
 
         return $token;
     }
 
     public function refreshTokenIfNeeded()
     {
         $getToken = AuthUser ::orderBy('id', 'desc')->first();
         $this->client->setAccessToken([
             'access_token' => $getToken->access_token,
             'expires_in' => $getToken->expires_at,
             'refresh_token' => $getToken->refresh_token,
             'refresh_token_expires_in' => $getToken->refresh_token_expires,
         ]);
 
         if (Carbon::now()->greaterThan($getToken->expires_at)) {
             $newToken = $this->client->fetchAccessTokenWithRefreshToken($getToken->refresh_token);
             if (isset($newToken['access_token'])) {
                 AuthUser ::create([
                     'access_token' => $newToken['access_token'],
                     'expires_at' =>$newToken['expires_in'],
                     'refresh_token' => $newToken['refresh_token'],
                     'refresh_token_expires' =>$newToken['refresh_token_expires_in'],
                 ]);
                 $this->client->setAccessToken($newToken);
             }
         }
     }
 
     public function getGoogleContacts()
     {
         $this->refreshTokenIfNeeded();
         $peopleService = new PeopleService($this->client);
         $connections = $peopleService->people_connections->listPeopleConnections('people/me', [
             'personFields' => 'names,emailAddresses,phoneNumbers',
         ]);
 
         return $connections->getConnections();
     }
 }
?>