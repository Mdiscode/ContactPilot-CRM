@extends('googleContact/dashboard')
@section('contCss')
<style>
  #myTable tr:hover {
    background-color: #ddd;
  }

</style>
@endsection
@section('content')

<div class="container">
  <h2 class="mt-5">Contact Table</h2>

  <div class="card">

    <div class="card p-2 overflow-auto">
    <table id="myTable" class="border-collapse border border-gray-300 data-table w-full " >
      <thead class="shadow-lg border border-t border-l border-b border-gray-300 px-4 py-2 text-left bg-gray-100">
        <tr>
          <th class="whitespace-nowrap">Action</th>
          <th class="whitespace-nowrap"  >#</th>
          <th class="whitespace-nowrap"  >Contact Name</th>
          <th class="whitespace-nowrap">Total Sip</th>
          <th class="whitespace-nowrap">Family/Organisation Name</th>
          <th class="whitespace-nowrap">Pan Card</th>
          <th class="whitespace-nowrap">Investment Preferences</th>
          <th class="whitespace-nowrap">Total Investment</th>
          <th class="whitespace-nowrap">Kyc Status</th>
          <th class="whitespace-nowrap">Email</th>
          <th class="whitespace-nowrap">Phone</th>
          <th class="whitespace-nowrap">Aadhar Card</th>
          <th class="whitespace-nowrap">Relationship Manager</th>
          <th class="whitespace-nowrap">Created_at</th>
          <th class="whitespace-nowrap">Updated_at</th>
          
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  </div>
</div>

@endsection

@section('script')
<script>
  $(document).ready( function () {
    $('#myTable').DataTable({
      'processing':true,
      "serverSide": true,
      "ajax":{
        url:"{{route('google.conlist')}}",
        type:"GET",
      },
      "columns":[
        // {
                    //     data: null,
                    //     render: function(data, type, row) {
                    //         return `
                    //             <a href="#" class="text-blue-500 hover:text-blue-700">
                    //                 edit
                    //             </a>
                    //         `;
                    //     },
                    //     orderable: false // Disable sorting on this column
                    // },
        { data: 'edit', name: 'edit', orderable: false, searchable: false },
        {data : 'id'},
        {data : 'contact_name'},
        {data : 'total_sip'},
        {data : 'family_org_name'},
        {data : 'Pan_card'},
        {data : 'investment'},
        {data : 'total_investment'},
        {data : 'kyc_status'},
        {data : 'email'},
        {data : 'phone'},
        {data : 'Aadhar_card'},
        {data : 'Rms'},
        {data :  "formatted_date",name:'created_at'},
        {data : "formatted_date",name:'updated_at'},
        
        
      ],
      createdRow: function(row, data, dataIndex) {
                    // Add bottom border to each td
                    $(row).find('td').addClass(' border-b border-gray-300 px-4 py-2');
                }

    });


} );
</script>
@endsection