@extends('googleContact/dashboard')
@section('content')

{{-- <form action="{{ route('google.update-contact', ['resourceName'=> $revName ,'etag'=> urlencode($etag)]) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    
    <input type="text" name="newName" placeholder="Enter new name" value="{{ $contact['names'][0]['givenName'] ?? '' }}" class="border rounded px-4 py-2 w-full" >
    <input type="email" name="email" placeholder="Enter new email" value="{{ $contact['emailAddresses'][0]['value'] ?? '' }}" class="border rounded px-4 py-2 w-full" >
    <input type="text" name="phone" placeholder="Enter new phone" value="{{ $contact['phoneNumbers'][0]['value'] ?? '' }}" class="border rounded px-4 py-2 w-full" >
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update </button>
</form> --}}

@endsection

$googleMap = [];
foreach ($googleContacts->getConnections() as $gContact) {
    $googleMap[$gContact->getResourceName()] = $gContact;
}

// 3. Loop through your local DB contacts
foreach (ContactList::wherenull('resourcesName')->get() as $dbContact) {
    $total++;
    $resourceName = $dbContact->resourcesName;

    if (!$resourceName) {
        print_r('ifconditions');
        // Not found in Google → CREATE new contact
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

        try {
            $createdPerson = $peopleService->people->createContact($person);

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

    } else {
        
        {{-- // Contact exists → Check for updates
        $gContact = $googleMap[$resourceName];
        print_r('else');

        // $gName = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getGivenName() : '';
        // $gFamily = isset($gContact->getNames()[0]) ? $gContact->getNames()[0]->getFamilyName() : '';
        // $gEmail = isset($gContact->getEmailAddresses()[0]) ? $gContact->getEmailAddresses()[0]->getValue() : '';
        // $gPhone = isset($gContact->getPhoneNumbers()[0]) ? $gContact->getPhoneNumbers()[0]->getValue() : '';

        // if (
        //     $dbContact->contact_name !== $gName ||
        //     $dbContact->family_org_name !== $gFamily ||
        //     $dbContact->email !== $gEmail ||
        //     $dbContact->phone !== $gPhone
        // ) {
        //     // Changes detected — update
        //     $personPatch = new \Google\Service\PeopleService\Person();
        //     $personPatch->setEtag($dbContact->etag); // important!

        //     $name = new \Google\Service\PeopleService\Name();
        //     $name->setGivenName($dbContact->contact_name);
        //     $name->setFamilyName($dbContact->family_org_name);
        //     $personPatch->setNames([$name]);

        //     $email = new \Google\Service\PeopleService\EmailAddress();
        //     $email->setValue($dbContact->email);
        //     $personPatch->setEmailAddresses([$email]);

        //     $phone = new \Google\Service\PeopleService\PhoneNumber();
        //     $phone->setValue($dbContact->phone);
        //     $personPatch->setPhoneNumbers([$phone]);

        //     try {
        //         $updated = $peopleService->people->updateContact(
        //             $resourceName,
        //             $personPatch,
        //             ['updatePersonFields' => 'names,emailAddresses,phoneNumbers']
        //         );

        //         $dbContact->etag = $updated->getEtag();
        //         $dbContact->save();

        //         $update++;
        //         \Log::info("Updated contact in Google: {$dbContact->contact_name}");
        //     } catch (\Exception $e) {
        //         $failed++;
        //         \Log::error("Failed to update contact: " . $e->getMessage()); --}}
            // }
        }
    }



