@extends('googleContact/dashboard')
@section('content')
<div class="container mx-auto p-6">
  <h2 class="text-2xl font-bold mb-4">Contact List</h2>
  <h2>Search Results for "{{ $query }}"</h2>
  <div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full table-auto">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Phone</th>
          <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @foreach ($contacts as $contact)
          @php
          
           $id = str_replace('people/', '', $contact['resourceName'] ?? 'No Name');
           $etag=  $contact['etag'] ?? 'No Name'; 
            $name = $contact['names'][0]['displayName'] ?? 'No Name';
            $email = $contact['emailAddresses'][0]['value'] ?? 'No Email';
            $phone = $contact['phoneNumbers'][0]['value'] ?? 'No Phone';
          @endphp
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">{{ $name }}</td>
            <td class="px-6 py-4">{{ $email }}</td>
            <td class="px-6 py-4">{{ $phone }}</td>
            <td class="px-6 py-4 text-center space-x-2">
              <a href="#" class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">View</a>
              <a href="{{ route('google.editContact', ['resourceName' => $id, 'etag' => urlencode($etag)]) }}" class="inline-block bg-yellow-500 text-white text-sm px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
              <form action="{{ route('google.delete-contact', ['resourceName' => $id]) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return comfirm('Are You sure you want you delete Contact!')" class="bg-red-500 text-white text-sm px-3 py-1 rounded hover:bg-red-600">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    

  </div>
</div>


@endsection