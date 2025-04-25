@extends('googleContact/dashboard')

@section('content')
    <div class="container">

        <div class="p-6 bg-gray-100 min-h-screen space-y-10">
          <div>
            <h2 class="text-xl font-bold text-gray-800 mb-4">🔄 Sync Contacts</h2>
      
            @if($getToken?->access_token && $getToken?->expires_ac_token && now()->lt($getToken->expires_ac_token))
        
            <div class="mb-4 text-green-600 font-medium">
              ✅ Google Account Connected
            </div>
            <button class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
              Sync Contacts from Google
            </button>
          @else
            <div class="mb-4 text-red-600 font-medium">
              ❌ Google Account Not Connected
            </div>
            <a href="{{url('/auth/google')}}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
              Connect Google Account
            </a>
          @endif
          </div>
            <!-- SECTION 1: Sync FROM Google -->
            {{-- <div class="bg-white rounded-xl shadow p-6 border border-gray-200 flex justify-between">
              <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">🔄 Sync Contacts <span class="text-blue-600">From Google</span></h2>
           --}}
              {{-- @if(session('google_connected'))
                <div class="mb-4 text-green-600 font-medium">
                  ✅ Google Account Connected
                </div>
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
                  Sync Contacts from Google
                </button>
              @else
                <div class="mb-4 text-red-600 font-medium">
                  ❌ Google Account Not Connected
                </div>
                <a href="#" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
                  Connect Google Account
                </a>
              @endif --}}
              {{-- </div>

              <div class="flex flex-wrap gap-4 ">
                <!-- Import Progress -->
                <div class="bg-white border-2 hover:border-teal-700 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-teal-700 font-medium">Total Contact</p>
                  <p class="text-lg font-semibold text-teal-900">17/17</p>
                </div>
              
                <!-- Created -->
                <div class="bg-white border-2 hover:border-green-600 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-green-600 font-medium">Created</p>
                  <p class="text-lg font-semibold text-green-800">14</p>
                </div>
              
                <!-- Duplicates -->
                <div class="bg-white border-2 hover:border-yellow-600 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-yellow-600 font-medium">Duplicates</p>
                  <p class="text-lg font-semibold text-yellow-800">0</p>
                </div>
              
                <!-- Errors -->
                <div class="bg-white border-2 hover:border-red-500 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-red-600 font-medium">Errors</p>
                  <p class="text-lg font-semibold text-red-800">3</p>
                </div>
              </div>
              
            </div> --}}
          
            <!-- SECTION 2: Sync TO Google -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200 flex flex-col justify-between">
              <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">📤 Sync Contacts <span class="text-green-600">From SanchaCRM</span></h2>
          
              {{-- @if(session('google_connected'))
                <button class="bg-green-600 text-white px-5 py-2 rounded-lg shadow hover:bg-green-700">
                  Sync Dashboard Contacts to Google
                </button>
              @else
                <div class="text-red-600 mb-2 font-medium">
                  ⚠️ You need to connect your Google account first.
                </div>
                <a href="" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
                  Connect Now
                </a>
              @endif --}}
              </div>

              {{-- ///process div  --}}
              <div class="flex flex-wrap gap-4 ">
                <!-- Import Progress -->
                <div class="bg-white border-2 hover:border-teal-700 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-teal-700 font-medium">Total Contact</p>
                  <p class="text-lg font-semibold text-teal-900">{{$count ?? '0'}}</p>
                </div>
              
                <!-- Created -->
                <div class="bg-white border-2 hover:border-green-600 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-green-600 font-medium">Syncedcontact</p>
                  <p class="text-lg font-semibold text-green-800">{{$createdTodayCount ?? '0'}}</p>
                </div>
              
                <!-- Duplicates -->
                <div class="bg-white border-2 hover:border-yellow-600 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-yellow-600 font-medium">Duplicates</p>
                  <p class="text-lg font-semibold text-yellow-800">0</p>
                </div>
              
                  <!-- Pending-contact-to CRM -->
                  <div class="bg-white border-2 hover:border-yellow-600 rounded-xl shadow p-4 w-40 text-center">
                    <p class="text-sm text-yellow-600 font-medium">Pending Sync CRM</p>
                    <p class="text-lg font-semibold text-yellow-800">0</p>
                  </div>
                
                <!-- Errors -->
                <div class="bg-white border-2 hover:border-red-500 rounded-xl shadow p-4 w-40 text-center">
                  <p class="text-sm text-red-600 font-medium">Errors</p>
                  <p class="text-lg font-semibold text-red-800">3</p>
                </div>

                  <!-- Sync Google contact pending -->
                  <div class="bg-white border-2 hover:border-yellow-600 rounded-xl shadow p-4 w-40 text-center">
                    <p class="text-sm text-yellow-600 font-medium">Sync google pending</p>
                    <p class="text-lg font-semibold text-yellow-800">{{$totalGoogleContacts ?? '0'}}</p>
                  </div>
                
              </div>
              

            </div>

                      {{-- show the error,dublication,and other --}}
          <div class="w-full">
            <div class="tab w-full shadow-lg">
                <button id="createOpen" onclick="process(event, 'create')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Create</button>
                <button onclick="process(event, 'dublicate')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700  hover:text-blue-500">Duplication</button>
                <button onclick="process(event, 'error')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700  hover:text-blue-500">Error</button>
            </div>
            <div id="create" class="tabcontent w-full h-28 bg-amber-500">
                <h1>create</h1>
             </div>
             
             <div id="dublicate" class="tabcontent w-full h-28 bg-green-400">
                <h1>dublication</h1>
             </div>
    
             <div id="error" class="tabcontent error w-full h-28 bg-red-400">
                <h1>error</h1>
             </div>
           </div>
         
        </div>
          

    </div>
@endsection
@section('script')
<script>


    function process(evt, contact) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(contact).style.display = "block";
  evt.currentTarget.className += " active";

}
document.getElementById("createOpen").click();
</script>

