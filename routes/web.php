<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\googleSyncController;
Route::get('/wel', function () {
    return view('welcome');
});


use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleSynController;


Route::get('/', [ContactController::class, 'index']);
Route::get('/gcontact', [ContactController::class, 'CreateContact'])->name('google.contact');
Route::get('/contact_list',[ContactController::class,'GcontactList'])->name('google.conlist');

///search
Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
Route::get('/contacts/edit/{id}', [ContactController::class, 'ContactEdit'])->name('contactlist.edit');
Route::get('contacts/integration',[ContactController::class,'Integration'])->name('contact.integration');
Route::get('contacts/syncgoogle',[ContactController::class,'syncContact'])->name('contact.syncgoogle');
//store_contact
Route::post('contacts/store_contact',[ContactController::class,'StoreContact'])->name('store.contact');
Route::post('contacts/contactUpdate',[ContactController::class,'Update_Contact'])->name('contactUpdate');
Route::view('demo','demo');


//----google--auth----
// Route::get('/auth/google', [GoogleSynController::class, 'redirectToGoogle']);
// Route::get('auth/google/callback', [GoogleSynController::class, 'handleGoogleCallback']);

// routes/web.php

Route::get('/google/sync-to-db', [GoogleSynController::class, 'syncGoogleToDB'])->name('contacts.sync');
Route::get('/google/sync-to-google', [GoogleSynController::class, 'syncDBToGoogle'])->name('contacts.push');



// ---------------services---used--------------------
use App\Http\Controllers\GoogleContactController;

Route::get('auth/google', [GoogleContactController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleContactController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('google-to-db', [GoogleContactController::class, 'googleToDatabaseSync'])->name('google.to.db');
Route::get('db-to-google', [GoogleContactController::class, 'syncAllContactsToGoogle'])->name('db.to.google');

Route::get('/clear-jobs', function () {
    DB::table('failed_jobs')->truncate();
    return 'All jobs cleared!';
});
