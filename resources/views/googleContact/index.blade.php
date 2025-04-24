@extends('googleContact/dashboard')
@section('content')


        <!-- Page Content -->
        {{-- <main class="flex-1 overflow-y-auto p-6"> --}}
            {{-- <h1 class="text-2xl font-bold mb-4 text-gray-800">Dashboard</h1> --}}
            {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> --}}
                <!-- Example Card -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mt-5">
                    <!-- Total Contacts -->
                    <div class="bg-white p-6 rounded-2xl shadow">
                      <h3 class="text-gray-500 text-sm font-medium">Total Contacts</h3>
                      <div class="text-3xl font-bold">{{$count ?? '0'}}</div>
                      <div class="text-sm text-green-600 font-medium mt-1">+156 from last sync</div>
                      <div class="flex justify-between text-sm text-gray-600 mt-4">
                        <div>
                          <div class="font-semibold">In CRM</div>
                          <div>{{$count ?? '0'}}</div>
                        </div>
                        <div>
                          <div class="font-semibold">In Google</div>
                          <div>{{$totalGoogleContacts ?? '0'}}</div>
                          {{-- <div>{{print_r($getToken)}}</div> --}}
                        </div>
                      </div>
                    </div>
                
                    <!-- Sync Status -->
                    <div class="bg-white p-6 rounded-2xl shadow">
                      <h3 class="text-gray-500 text-sm font-medium">Sync Status</h3>
                      <div class="text-3xl font-bold">92%</div>
                      <div class="text-sm text-gray-500 mt-1">Last synced 23 minutes ago</div>
                      <div class="w-full h-2 bg-gray-200 rounded mt-3">
                        <div class="bg-black h-2 rounded" style="width: 92%"></div>
                      </div>
                      <div class="flex justify-between text-sm text-gray-600 mt-4">
                        <div>
                          <div class="font-semibold">Synced</div>
                          <div>{{$synced ?? 0}}</div>
                        </div>
                        <div>
                          <div class="font-semibold">Pending</div>
                          <div>{{$pending ?? 0}}</div>
                        </div>
                        <div>
                          <div class="font-semibold">Failed</div>
                          <div>{{$failed ?? 0}}</div>
                        </div>
                      </div>
                    </div>
                
                    <!-- Changes Detected -->
                    <div class="bg-white p-6 rounded-2xl shadow">
                      <h3 class="text-gray-500 text-sm font-medium">Changes Detected</h3>
                      <div class="text-3xl font-bold">42</div>
                      <div class="text-sm text-gray-500 mt-1">Since last sync</div>
                      <div class="flex justify-between text-sm text-gray-600 mt-4">
                        <div>
                          <div class="font-semibold">Added</div>
                          <div>{{$synced ?? 0}}</div>
                        </div>
                        <div>
                          <div class="font-semibold">Updated</div>
                          <div>{{$update ?? 0}}</div>
                        </div>
                        <div>
                          <div class="font-semibold">Deleted</div>
                          <div>0</div>
                        </div>
                      </div>
                    </div>
                 
                    <!-- Actions -->
                    <div class="bg-white p-6 rounded-2xl shadow">
                      <h3 class="text-gray-500 text-sm font-medium mb-4">Actions</h3>

                      {{-- @if($getToken?->access_token && $getToken?->expires_ac_token && now()->lt($getToken->expires_ac_token)) --}}
                      <button  class="w-full bg-black text-white py-2 rounded-lg font-medium mb-2"> <a href="{{url('/auth/google')}}">Sync Now</a></button>
                    {{-- @else --}}
                    {{-- <button class="w-full bg-black text-white py-2 rounded-lg font-medium mb-2">Sign in</button> --}}
                    {{-- @endif --}}
                      
                      <button class="w-full border border-gray-300 text-black py-2 rounded-lg font-medium mb-2"><a href="{{route('contacts.push')}}">Push to Google</a></button>
                      <button class="w-full border border-gray-300 text-black py-2 rounded-lg font-medium"><a href="{{route('contacts.sync')}}">Import from Google</a></button>
                    </div>
                  </div>
                
                  <!-- Tabs -->
                  <div class="mt-8 border-t border-gray-200 pt-4 flex justify-around text-gray-600 font-medium text-sm">
                    
                    <button class="text-black">Sync Status</button>
                   
                    <button>Sync History</button>
                    <button>Contacts</button>
                    <button>Settings</button>
                  </div>
            </div>
        {{-- </main> --}}
    {{-- </div>  --}}
@endsection