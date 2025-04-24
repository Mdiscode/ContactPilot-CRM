<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactList;
use Yajra\DataTables\DataTables; 
use Carbon\Carbon;
use Validator;
use App\Models\AuthUser;

class ContactController extends Controller
{
   public function index(){

    $count = ContactList::count();
                // Get the current date
                $currentDate = now()->toDateString();
    $createdTodayCount = ContactList::whereDate('created_at', $currentDate)->count();
        return view('googleContact.index',compact('count','createdTodayCount'));
    }

    //create function
    function CreateContact(){
        return view('googleContact.create');
    }

    //contact_list


public function GcontactList(Request $request)
{
    if ($request->ajax()) {
        $data = ContactList::orderBy('id','desc')->get();
    
        return DataTables::of($data)
            ->addColumn('formatted_date', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d');
            })
            ->addColumn('edit', function ($row) {
                $editUrl = route('contactlist.edit', $row->id); // Adjust the route name as per your route definition
                return '<a id="edit" href="' . $editUrl . '">
                <i class="bi bi-pencil "></i>
                </a>';
            })
            ->rawColumns(['formatted_date', 'edit']) // Declare both columns to be rendered as raw HTML
            ->make(true);
    }
    

    return view('googleContact.contacts_list');
}


//searching
public function search(Request $request)
    {
        // Retrieve the search parameter
        $searchTerm = $request->input('search');

        // Build the query
        $query = ContactList::query();
    

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('contact_nam', 'like', "%$searchTerm%")
                  ->orWhere('phone', 'like', "%$searchTerm%")
                  ->orWhere('email', 'like', "%$searchTerm%");
            });
        }

        // Execute the query and get results
        $results = $query->get();

        // Return results (you can return a view or JSON response)
        return response()->json($results);
    }

    //--Store--contact---
    public function StoreContact(Request $request){
        $validate = $request->validate([
            "contact_name"=>"required",
            "phone"=>"required|max:13|min:10",
            "email"=>"required|email"
        ]);
        
        $create = ContactList::create([
            "contact_name" =>$request->contact_name,
            "phone"=>$request->phone,
            "email"=>$request->email,
            "family_org_name"=>$request->family_org_name,
            "gender"=>$request->gender,
            "birthdate"=>$request->birthdate,
            "relation"=>$request->relation,
            "Rms"=>$request->rms,
            "Aadhar_card"=>$request->Aadhar_card,
            "kyc_status" =>$request->kyc_status,
            "investment"=>$request->investment,
            "total_investment"=>$request->total_investment,
            "Pan_card"=>$request->Pan_card,
            "total_sip"=>$request->total_sip
        ]);

        return redirect(route('google.conlist'));
    }
    //Edit_contact
    function ContactEdit($id){

        $data = ContactList::find($id);

        return view('googleContact.create',compact('data'));
    }

    function Update_Contact(Request $request){
        $validate = $request->validate([
            "contact_name"=>"required",
            "phone"=>"required|max:13|min:10",
            "email"=>"required|email"]);

           $updatecon = ContactList::find($request->id);
           $updatecon->update([
            "contact_name" =>$request->contact_name,
            "phone"=>$request->phone,
            "email"=>$request->email,
            "family_org_name"=>$request->family_org_name,
            "gender"=>$request->gender,
            "birthdate"=>$request->birthdate,
            "relation"=>$request->relation,
            "Rms"=>$request->rms,
            "Aadhar_card"=>$request->Aadhar_card,
            "kyc_status" =>$request->kyc_status,
            "investment"=>$request->investment,
            "total_investment"=>$request->total_investment,
            "Pan_card"=>$request->Pan_card,
            "total_sip"=>$request->total_sip
           ]);

           return redirect(route('google.conlist'));
    }

    
    //sync google contact
    function Integration(){
        $count = ContactList::count();

            // Get the current date
            $currentDate = now()->toDateString(); // or Carbon::today()->toDateString();

            // Count records created today
            $createdTodayCount = ContactList::whereDate('created_at', $currentDate)->count();
        
        
        return view('googleContact.integration' ,compact('count','createdTodayCount'));
    }


    public function syncContact(){
       
       return view('googleContact.googleSync');
    
    }
}
