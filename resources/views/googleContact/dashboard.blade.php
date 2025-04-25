<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) For Laravel Vite --}}
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
 {{-- -----row--readable---datatable-css------ --}}
<link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.min.css" rel="stylesheet" integrity="sha384-iJaY6tDwZfknqxE3hfWpJ/eOh/jUwudQWqFm251uZJWXZcpqPx3Z/BG65Y4r7us0" crossorigin="anonymous">
 
{{-- -----tailwindcss---------- --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- //----bootsratb--icon---cdn---------- --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    /* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
/* .tab button:hover {
  background-color: #ddd;
} */

/* Create an active/current tablink class */
.tab button.active {
  border-bottom: 2px solid blue;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
@yield('contCss')

</head>
<body class=" font-sans antialiased">
    <header id="header1" class="bg-blue-800 shadow px-4 py-3 flex justify-between items-center">
        {{-- <div class="text-lg font-semibold text-gray-700">Welcome, Admin</div> --}}
        <div class="ml-10">
        
            <form action="{{ route('contacts.search') }}" method="GET" class="flex">
                {{-- <input type="text" name="query" placeholder="Search contacts..." value="{{ request('query') }}"> --}}
                <input type="text" name="search" placeholder="Global Search.."  class="text-black border w-full rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button class="text-white" type="submit">Search</button>
            </form>
        </div> 
        <div class="flex items-center space-x-4 justify-center">
            <a href="#" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Logout</a>
        </div>
    </header>
<div class="flex h-screen">
    <!-- Topbar -->
    
    <!-- Sidebar -->
    <aside class=" w-48 bg-gray-100 shadow-lg hidden md:block">
        <nav class="mt-6 flex flex-col justify-center items-center ml-5 ">
            <div class="flex justify-between items-center hover:bg-gray-200 w-full">
                <div class="rounded-full w-8 h-8 bg-blue-700 text-white text-center">DS</div>
                <div class="bg-inherit">
                    <a  href="{{url('/')}}" class=" block px-6 py-3 text-gray-700  ">Dashboard</a>
                </div>
            </div>

            <div class="flex justify-between items-center hover:bg-gray-200 w-full">
                <div class="rounded-full w-8 h-8 bg-red-500 text-white text-center">DS</div>
                <div class="bg-inherit">
                    <a href="{{route('google.conlist')}}" class="block px-6 py-3 text-gray-700 ">Contacts-list</a>
                </div>
            </div>

            <div class="flex justify-between items-center hover:bg-gray-200 w-full">
                <div class="rounded-full w-8 h-8 bg-green-700 text-white text-center">DS</div>
                <div class="bg-inherit ">
                    
                    <a href="{{ route('google.contact') }}" onclick="openCreate()" class="block px-6 py-3 text-gray-700 ">createCont</a>
                </div>
            </div>
            <div class="flex justify-between items-center hover:bg-gray-200 w-full">
                <div class="rounded-full w-8 h-8 bg-orange-700 text-white text-center">IN</div>
                <div class="bg-inherit">
                    <a  href="{{route('contact.integration')}}" onclick="process()" class=" block px-6 py-3 text-gray-700  ">Integiration</a>
                </div>
            </div>
            {{-- <a href="{{url('searchcontact')}}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100">SearchContact</a> --}}
            <div class="flex justify-between items-center hover:bg-gray-200 w-full">
                <div class="rounded-full w-8 h-8 bg-orange-700 text-white text-center">SD</div>
                <div class="bg-inherit">
                    <a  href="{{route('contact.syncgoogle')}}"  class=" block px-6 py-3 text-gray-700  ">SyncDash</a>
                </div>
            </div>
            
            
        </nav>
    </aside>
           <!-- Main Content -->
           <div class="flex-1 flex flex-col">

            

            @yield('content')
           </div>
           
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- ---datatable---- --}}
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.min.js" integrity="sha384-xiwUQRasBcDH/VWFi1JXyBwDItoHATDc19Mt/Vz+3Ltg7GsOm6TBoUPfIw5LA1ck" crossorigin="anonymous"></script>



<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@yield('script')


</body>
</html>
