@extends('googleContact/dashboard')
@section('content')
   <h1>GoogleSync Page</h1>

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

                      <button  class="w-full bg-black text-white py-2 rounded-lg font-medium mb-2"> <a href="{{route('auth.google')}}">Sync Now</a></button>
                    
                      
                      <button class="w-full border border-gray-300 text-black py-2 rounded-lg font-medium mb-2"><a href="{{route('db.to.google')}}">Push to Google</a></button>
                      <button class="w-full border border-gray-300 text-black py-2 rounded-lg font-medium"><a href="{{route('google.to.db')}}">Import from Google</a></button>
                    </div>
                  </div>
                
                  <!-- Tabs -->
                  <div class="mt-8 border-t border-gray-200 pt-4 flex justify-around text-gray-600 font-medium text-sm">
                    
                    {{-- <button class="text-black">Sync Status</button> --}}
                    {{-- <button  onclick="process(event, 'create')" class="tablinks " >Sync History</button> --}}
                    {{-- <button>Contacts</button>
                    <button>Settings</button> --}}
                    <button id="SynceOpen" onclick="process(event, 'syncStatus')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Sync Status</button>
                    <button  onclick="process(event, 'syncHistory')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Sync History</button>
                    <button  onclick="process(event, 'contact')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Contacts</button>
                    <button  onclick="process(event, 'setting')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Settings</button>
                    
                    
                  </div>
                  {{-- ------synccontact---------------start --}}
                  <div id="syncStatus" class="tabcontent  p-6 bg-white rounded-lg shadow  w-full">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                      <div>
                        <h2 class="text-2xl font-bold">Contact Sync Status</h2>
                        <p class="text-sm text-gray-500">Current status of Google Contacts synchronization</p>
                      </div>
                      <button class="flex items-center px-4 py-2 bg-black text-white rounded hover:bg-gray-800 text-sm">
                        🔄 Refresh
                      </button>
                    </div>
                  
                    <!-- Status Table -->
                    <div class="border rounded overflow-hidden mb-6">
                      <!-- Last Sync -->
                      <div class="flex justify-between items-center bg-gray-50 px-4 py-3 border-b">
                        <div class="text-sm font-medium">Last Sync: <span class="font-normal">April 7, 2025 at 1:29 PM</span></div>
                        <span class="text-green-700 bg-green-100 text-xs font-medium px-3 py-1 rounded-full">✓ Completed</span>
                      </div>
                  
                      <!-- Contacts in CRM -->
                      <div class="flex justify-between items-center px-4 py-4 border-b">
                        <div>
                          <div class="text-sm font-semibold">Contacts in CRM</div>
                          <div class="text-xs text-gray-500">Total contacts in your CRM system</div>
                        </div>
                        <div class="flex items-center space-x-4">
                          <div class="text-xl font-bold">1,248</div>
                        </div>
                        <div class="flex items-center space-x-4">
                          <span class="text-green-700 bg-green-100 text-xs font-medium px-3 py-1 rounded-full">✓ Synced</span>
                        </div>
                      </div>
                  
                      <!-- Contacts in Google -->
                      <div class="flex justify-between items-center px-4 py-4 border-b">
                        <div>
                          <div class="text-sm font-semibold">Contacts in Google</div>
                          <div class="text-xs text-gray-500">Total contacts in Google Contacts</div>
                        </div>
                        <div class=" flex   items-center space-x-4">
                          <div class="text-xl font-bold">1,356</div>
                        </div>
                        <div class=" flex   items-center space-x-4">
                          <span class="text-blue-700 bg-blue-100 text-xs font-medium px-3 py-1 rounded-full">🔄 108 to import</span>
                        </div>
                      </div>
                  
                      <!-- Pending Changes -->
                      <div class="flex justify-between items-center px-4 py-4 border-b">
                        <div>
                          <div class="text-sm font-semibold">Pending Changes</div>
                          <div class="text-xs text-gray-500">Changes waiting to be synced</div>
                        </div>
                        <div class="flex items-center space-x-4">
                          <div class="text-xl font-bold">42</div>
                        </div>
                        <div class="flex items-center space-x-4">
                          <span class="text-yellow-800 bg-yellow-100 text-xs font-medium px-3 py-1 rounded-full">⏳ Pending</span>
                        </div>
                      </div>
                  
                      <!-- Sync Errors -->
                      <div class="flex justify-between items-center px-4 py-4">
                        <div>
                          <div class="text-sm font-semibold">Sync Errors</div>
                          <div class="text-xs text-gray-500">Contacts that failed to sync</div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                          <div class="text-xl font-bold">0</div>
                        </div>
                        <div class="flex items-center space-x-4">
                          
                          <span class="text-green-700 bg-green-100 text-xs font-medium px-3 py-1 rounded-full">✓ No Errors</span>
                        </div>
                      </div>
                      
                    </div>
                  
                    <!-- Footer Buttons -->
                    <div class="flex justify-between items-center">
                      <button class="flex items-center px-4 py-2 border rounded text-sm hover:bg-gray-100">
                        🔁 Sync Now
                      </button>
                      <div class="space-x-2">
                        <button class="px-4 py-2 border rounded text-sm hover:bg-gray-100">View Logs</button>
                        <button class="px-4 py-2 bg-black text-white rounded text-sm hover:bg-gray-800">Resolve All Issues</button>
                      </div>
                    </div>
                  </div>
                  
                 {{-- ------synccontact---------------end--}}

                 {{-- -------SyncHistory--------start---------------- --}}
                 <div id="syncHistory" class="tabcontent p-6 bg-white rounded-lg shadow">
                  <div class="flex justify-between items-center mb-4">
                    <div>
                      <h2 class="text-2xl font-bold">Sync History</h2>
                      <p class="text-sm text-gray-500">History of Google Contacts synchronization</p>
                    </div>
                    <div class="space-x-2">
                      <button class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Filter</button>
                      <button class="px-4 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">Export</button>
                    </div>
                  </div>
                
                  <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                      <thead class="text-xs text-gray-600 uppercase bg-gray-50">
                        <tr>
                          <th class="px-4 py-2">Date & Time</th>
                          <th class="px-4 py-2">Status</th>
                          <th class="px-4 py-2">Added</th>
                          <th class="px-4 py-2">Updated</th>
                          <th class="px-4 py-2">Deleted</th>
                          <th class="px-4 py-2">Errors</th>
                          <th class="px-4 py-2">Actions</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-100">
                        <!-- Repeat this row block as needed -->
                        <tr>
                          <td class="px-4 py-3">April 7, 2025<br><span class="text-xs text-gray-400">1:29 PM</span></td>
                          <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 text-green-700 bg-green-100 rounded-full text-xs font-medium">✓ Success</span>
                          </td>
                          <td class="px-4 py-3">18</td>
                          <td class="px-4 py-3">24</td>
                          <td class="px-4 py-3">0</td>
                          <td class="px-4 py-3">0</td>
                          <td class="px-4 py-3">
                            <a href="#" class="text-blue-600 hover:underline">View</a>
                          </td>
                        </tr>
                
                        <tr>
                          <td class="px-4 py-3">April 7, 2025<br><span class="text-xs text-gray-400">11:59 AM</span></td>
                          <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 text-yellow-800 bg-yellow-100 rounded-full text-xs font-medium">⚠️ Warning</span>
                          </td>
                          <td class="px-4 py-3">138</td>
                          <td class="px-4 py-3">0</td>
                          <td class="px-4 py-3">0</td>
                          <td class="px-4 py-3">3</td>
                          <td class="px-4 py-3">
                            <a href="#" class="text-blue-600 hover:underline">View</a>
                          </td>
                        </tr>
                
                        <!-- More rows... -->
                      </tbody>
                    </table>
                  </div>
                
                  <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
                    <span>Showing 5 of 24 entries</span>
                    <div class="space-x-2">
                      <button class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Previous</button>
                      <button class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Next</button>
                    </div>
                  </div>
                </div>
                 {{-- -------SyncHistory--------end---------------- --}}

                 {{-- -------Contact--------start---------------- --}}
                 <div id="contact" class="tabcontent p-6 bg-white rounded-lg shadow">
                  <div class="flex justify-between items-center mb-4">
                    <div>
                      <h2 class="text-2xl font-bold">Contacts</h2>
                      <p class="text-sm text-gray-500">Manage your contacts and their sync status</p>
                    </div>
                    <div class="flex items-center space-x-2">
                      <button class="px-4 py-2 text-sm border rounded hover:bg-gray-100"><i class="ri-upload-line"></i> Export</button>
                      <button class="px-4 py-2 text-sm border rounded hover:bg-gray-100"><i class="ri-download-line"></i> Import</button>
                      <button class="px-4 py-2 text-sm font-medium text-white bg-black rounded hover:bg-gray-800">+ Add Contact</button>
                    </div>
                  </div>
                
                  <div class="flex justify-between items-center mb-4">
                    <input type="text" placeholder="Search contacts..." class="w-1/3 px-4 py-2 text-sm border rounded focus:outline-none focus:ring-1 focus:ring-gray-300" />
                    <div class="flex items-center space-x-2">
                      <select class="px-4 py-2 text-sm border rounded">
                        <option>All Contacts</option>
                      </select>
                      <button class="p-2 border rounded hover:bg-gray-100">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path d="M3 6h18M3 12h18M3 18h18" />
                        </svg>
                      </button>
                    </div>
                  </div>
                
                  <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-t">
                      <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                          <th class="px-4 py-2">
                            <input type="checkbox" class="form-checkbox" />
                          </th>
                          <th class="px-4 py-2">Name</th>
                          <th class="px-4 py-2">Email</th>
                          <th class="px-4 py-2">Phone</th>
                          <th class="px-4 py-2">Sync Status</th>
                          <th class="px-4 py-2">Last Sync</th>
                          <th class="px-4 py-2">Actions</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100">
                        <!-- Contact row -->
                        <tr>
                          <td class="px-4 py-3"><input type="checkbox" class="form-checkbox" /></td>
                          <td class="px-4 py-3 font-medium">Troy Moises</td>
                          <td class="px-4 py-3">jerde.blake@hotmail.com</td>
                          <td class="px-4 py-3">+911294114343</td>
                          <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-green-700 bg-green-100 text-xs rounded-full">Synced</span>
                          </td>
                          <td class="px-4 py-3">2 hours ago</td>
                          <td class="px-4 py-3 space-x-2">
                            <button class="text-gray-500 hover:text-black">
                              🔄
                            </button>
                            <button class="text-gray-500 hover:text-black">
                              ➕
                            </button>
                          </td>
                        </tr>
                
                        <tr>
                          <td class="px-4 py-3"><input type="checkbox" class="form-checkbox" /></td>
                          <td class="px-4 py-3 font-medium">Olive Michelle</td>
                          <td class="px-4 py-3">olive@yahoo.co.in</td>
                          <td class="px-4 py-3">+479374902740</td>
                          <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-yellow-800 bg-yellow-100 text-xs rounded-full">Pending</span>
                          </td>
                          <td class="px-4 py-3">1 hour ago</td>
                          <td class="px-4 py-3 space-x-2">
                            <button class="text-gray-500 hover:text-black">🔄</button>
                            <button class="text-gray-500 hover:text-black">➕</button>
                          </td>
                        </tr>
                
                        <tr>
                          <td class="px-4 py-3"><input type="checkbox" class="form-checkbox" /></td>
                          <td class="px-4 py-3 font-medium">New Contact</td>
                          <td class="px-4 py-3">aakanksha.enjay@gmail.com</td>
                          <td class="px-4 py-3"></td>
                          <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-gray-600 bg-gray-100 text-xs rounded-full">Not Synced</span>
                          </td>
                          <td class="px-4 py-3">Never</td>
                          <td class="px-4 py-3 space-x-2">
                            <button class="text-gray-500 hover:text-black">🔄</button>
                            <button class="text-gray-500 hover:text-black">➕</button>
                          </td>
                        </tr>
                
                        <!-- Add more rows as needed -->
                      </tbody>
                    </table>
                  </div>
                
                  <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
                    <span>Showing 6 of 1,248 contacts</span>
                    <div class="space-x-2">
                      <button class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Previous</button>
                      <button class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Next</button>
                    </div>
                  </div>
                </div>
                
                 {{-- -------contact--------end---------------- --}}

                 {{-- -------Setting--------start---------------- --}}
                 <div id="setting" class="tabcontent p-6 bg-white rounded-lg shadow max-w-4xl mx-auto">
                  <h2 class="text-2xl font-bold mb-4">Google Contacts Integration Settings</h2>
                
                  <!-- Sync Options -->
                  <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Sync Options</h3>
                    <div class="space-y-2">
                      <label class="flex items-center space-x-2">
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" checked />
                        <span>Enable automatic sync (every 30 minutes)</span>
                      </label>
                      <label class="flex items-center space-x-2">
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" checked />
                        <span>Enable two-way sync (changes in CRM update Google Contacts)</span>
                      </label>
                      <label class="flex items-center space-x-2">
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" checked />
                        <span>Notify on sync errors</span>
                      </label>
                    </div>
                  </div>
                
                  <!-- Field Mapping Table -->
                  <div class=" mb-6">
                    <h3 class="text-lg font-semibold mb-2">Field Mapping</h3>
                    <div class="overflow-x-auto">
                      <table class="min-w-full text-sm text-left border border-gray-200 rounded">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                          <tr>
                            <th class="px-4 py-2 border-b border-gray-200">CRM Field</th>
                            <th class="px-4 py-2 border-b border-gray-200">Google Contacts Field</th>
                          </tr>
                        </thead>
                        <tbody class="text-gray-700">
                          <tr class="border-b border-gray-100">
                            <td class="px-4 py-3">Name</td>
                            <td class="px-4 py-3">Name</td>
                          </tr>
                          <tr class="border-b border-gray-100">
                            <td class="px-4 py-3">Email</td>
                            <td class="px-4 py-3">Email</td>
                          </tr>
                          <tr class="border-b border-gray-100">
                            <td class="px-4 py-3">Phone</td>
                            <td class="px-4 py-3">Phone</td>
                          </tr>
                          <tr>
                            <td class="px-4 py-3">Company</td>
                            <td class="px-4 py-3">Organization</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                
                  <!-- Action Buttons -->
                  <div class="flex justify-end space-x-3">
                    <button class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-100">Cancel</button>
                    <button class="px-4 py-2 text-sm font-semibold text-white bg-black rounded hover:bg-gray-800">Save Settings</button>
                  </div>
                </div>
                
                 {{-- -------Settig--------end---------------- --}}
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
document.getElementById("SynceOpen").click();
</script>

 @endsection