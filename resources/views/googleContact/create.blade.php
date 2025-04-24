@extends('googleContact/dashboard')
@section('contCss')
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
@endsection
@section('content')
    <div class=" rounded  shadow-lg w-full overflow-hidden ">
        <div class="tab w-full shadow-lg overflow-hidden">
            <button id="defaultOpen" onclick="changeCon(event, 'overview')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700 hover:text-blue-500">Overview</button>
            <button onclick="changeCon(event, 'address')" class="tablinks p-3 border-b-2 border-transparent hover:border-blue-700  hover:text-blue-500">Address Information</button>
        </div>
        <form action="{{isset($data) ? route('contactUpdate') : route('store.contact')}}" method="POST" class="space-y-4 w-full">
            @if(isset($record))
            @method('PUT')
        @endif

        <div class="w-full  flex justify-center">
            <button type="submit" class="border-1 border-blue-600 text-blue-500 hover:bg-blue-500 hover:text-white m-1  px-2 py-1 rounded">Add Contact</button>
            <button type="text" class="border-1 border-red-500 text-red-500 hover:bg-red-500 hover:text-white m-1 px-2 py-1 rounded">Cangle</button>
        </div>

        
            @csrf
            
            {{-- ---row1------- --}}
                        {{-- ----row-10----Address--field --}}
                        <div id="address" class="tabcontent" class="  w-full h-full">
                            <table class="border  w-full h-[13vh] table-fixed">
                                <thead class="bg-gray-200">
                                    <tr class="shadow-lg">
                                        <th class="p-2 border border-gray-300">Address Type</th>
                                        <th class="p-2 border border-gray-300">Street</th>
                                        <th class="p-2 border border-gray-300">Area</th>
                                        <th class="p-2 border border-gray-300">City</th>
                                        <th class="p-2 border border-gray-300">State</th>
                                        <th class="p-2 border border-gray-300">Country</th>
                                        {{-- <th class="p-2 border border-gray-300">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input name="addressType" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        <td><input name="street" type="text" class="w-full p-0.5  border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        <td><input name="area" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        <td><input name="city" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        <td><input name="state" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        <td><input name="country" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td>
                                        {{-- <td><input name="" type="text" class="w-full p-0.5 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded"></td> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- --------row-10-end---------- --}}
           <div id="overview" class="tabcontent">
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">LastName</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="hidden" name="id" value="{{old('id',$data->id ?? '')}}">
                        <input type="text"  name="contact_name" value="{{old('contact_name',$data->contact_name ?? '')}}"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                            <span class="text-red-500">{{ $errors->first('lastname') }}</span>
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Family / Organisation Name</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="family_org_name"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                            
                    </div>
                </div>
            </div>
            {{-- ----row-2---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Phone</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="phone" value="{{old('phone',$data->phone ?? '')}}"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                            <span class="text-red-500">{{$errors->first('phone')}}</span>
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Email</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="email" value="{{old('email',$data->email ?? '')}}"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                            <span class="text-red-500">{{$errors->first('email')}}</span>
                    </div>
                </div>
            </div>
            {{-- --------row-2-end---------- --}}
            {{-- ----row-3---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Pan Card</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="Pan_card"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Aadhar Card</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="Aadhar_card"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-3-end---------- --}}

            {{-- ----row-4---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Occupation</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="Occuption"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Kyc Status</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="kyc_status"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-4-end---------- --}}

            {{-- ----row-5---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Annual Income</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="annual_incom"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Referred By Person Name </label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="ref_name"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-5-end---------- --}}

            {{-- ----row-6---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Total Investment</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="total_investment"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Comments / History</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="comment"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-6-end---------- --}}

            {{-- ----row-7---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Relationship Manager</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text"  name="Rms"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Service RM</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="Srms"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-7-end---------- --}}

            {{-- ----row-8---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Type Of Relation</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="relation"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Gender</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="text" name="gender"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-8-end---------- --}}

            {{-- ----row-9---- --}}
            <div class="row1 with-full p-3  bg-white grid grid-cols-2 gap-2">
                <div class="grid grid-cols-3 gap-0">
                    {{-- ---lastname-- --}}
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Birth Date</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="date" name="birthdate"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
                {{-- input--2-- --}}
                <div class="grid grid-cols-3 gap-0">
                    <div class="labe ">
                        <label class="text-center text-gray-500" for="">Anniversary</label>
                    </div>
                    <div class="input col-span-2 w-full">
                        <input type="date" name="anniversary"
                            class="w-full p-1 border border-gray-300 outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 rounded" />
                    </div>
                </div>
            </div>
            {{-- --------row-9-end---------- --}}

           </div>
            
        </form>
    </div>
@endsection

@section('script')
<script>


    function changeCon(evt, contact) {
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
document.getElementById("defaultOpen").click();
</script>
@endsection