<?php

namespace App\Http\Controllers;

use App\Models\CarMember;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClientAccount;


class CarMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($supplier_id)
    {
        $members = DB::table('clients')
            -> select('clients.*' )
            -> where('clients.car_supplier_id' , '=' ,$supplier_id )
            -> where('clients.is_farmer' , '=' , 1)
            ->orderBy('sort', 'asc')
            -> get();

        $suppliers = Client::where('car_id' , '<>' , 0) 
        -> where('is_farmer' , 0)
        -> get();
        $supplier = Client::find($supplier_id);
        
        
      //  return $members ;

        return view('admin.Client.members.index' , compact('members' , 'suppliers' , 'supplier'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request -> id == 0){
            $request->validate([
                    'name' => 'required|unique:clients,name',
                    'supplier_id' => 'required',
                ]
                , [
                    'supplier_id.required' => __('main.supplier_required'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );



            $sort = 0 ;
          
                $sort = $request->sort ?? (Client::where('type', 1)->max('sort') + 1);
               
                Client::where('type', 1)
                    ->where('sort', '>=', $sort)
                    ->increment('sort');
                    
                
            

               Client::create([
                'type' => 1 ,
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'buffalo_min_limit' => $request -> buffalo_min_limit ?? 0,
                'buffalo_max_limit' => $request -> buffalo_max_limit ?? 0,
                'bovine_min_limit' => $request -> bovine_min_limit ?? 0,
                'bovine_max_limit' => $request -> bovine_max_limit ?? 0,
                'address' => $request -> address ?? "",
                'car_id' =>  0,
                'sort'   => $sort,
                'car_supplier_id' => $request -> supplier_id ,
                'is_farmer' => 1,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
            
            
            return redirect()->route('carMembers' , $request -> supplier_id) -> with('success', __('main.saved'));
        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarMember  $carMember
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
            
             $member = DB::table('clients')
            -> select('clients.*' )
            -> where('clients.id' , '=' ,$id )
            -> where('clients.is_farmer' , '=' , 1)
            -> first();
     
        $supplier = Client::find($id);
            
            if($member){
                $member -> supplier_name = $supplier -> name ;
            }
            
        echo json_encode($member);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarMember  $carMember
     * @return \Illuminate\Http\Response
     */
    public function edit(CarMember $carMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarMember  $carMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $member = Client::find($request -> id);
        if($member){

            $oldOrder = $member->sort;
            $newOrder = $request->sort;

            $member -> update([
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'buffalo_min_limit' => $request -> buffalo_min_limit ?? 0,
                'buffalo_max_limit' => $request -> buffalo_max_limit ?? 0,
                'bovine_min_limit' => $request -> bovine_min_limit ?? 0,
                'bovine_max_limit' => $request -> bovine_max_limit ?? 0,
                'address' => $request -> address ?? "",
                'car_id' => $request -> car_id ?? 0,
                'sort'   => $newOrder,
                'is_farmer' => 1,
                'user_upd' => Auth::user() -> id
            ]);
            return redirect()->route('carMembers' , $member -> car_supplier_id ) -> with('success', __('main.updated'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarMember  $carMember
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $member = Client::find($id);
        if($member){
            $supplierAccount = ClientAccount::where('client_id', $id)  -> get();
            
            if(count($supplierAccount) > 0 ){
             return redirect()->route('carMembers' , $member -> car_supplier_id ) -> with('success', __('main.can_not_delete'));
            } else {
                $member -> delete();
                 return redirect()->route('carMembers' , $member -> car_supplier_id ) -> with('success', __('main.deleted'));
               
            }

        }
    }


    public function  getOrder($supplier)
    {
        $sort = $request->sort ?? (Client::where('type', 1)->max('sort') + 1);
        echo json_encode($sort);
        exit();

    }
}
