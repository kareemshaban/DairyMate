<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\SupplierInsuranceBalance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ClientController extends Controller
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
    public function index($type)
    {

        if (!Gate::allows('page-access', [7, 'view'])) {
            abort(403);
        }

        $suppliers = Client::with('balance') -> where('type', $type)
            -> where('is_farmer' , 0)
            ->orderBy('sort', 'asc')
            ->get();
        $cars = Cars::all();

       // return $suppliers ;
        return view('admin.Client.index', compact('suppliers' , 'type' , 'cars'));
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
                    'type' => 'required',
                ]
                , [
                    'type.required' => __('main.type_required'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );
            $sort = 0 ;
            if ($request->type == 1 ) {
                $sort = $request->sort ?? (Client::where('type', 1)->max('sort') + 1);
                // shift orders only for type=1
                Client::where('type', 1)
                    ->where('sort', '>=', $sort)
                    ->increment('sort');
            } else {
                $sort = 0 ;
            }


            Client::create([
                'type' => $request -> type, // 0 client , 1 supplier , 2 both
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'buffalo_min_limit' => $request -> buffalo_min_limit ?? 0,
                'buffalo_max_limit' => $request -> buffalo_max_limit ?? 0,
                'bovine_min_limit' => $request -> bovine_min_limit ?? 0,
                'bovine_max_limit' => $request -> bovine_max_limit ?? 0,
                'address' => $request -> address ?? "",
                'car_id' => $request -> car_id ?? 0,
                'sort'   => $sort,
                'is_farmer' => 0 ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
            return redirect()->route('suppliers' ,$request -> type) -> with('success', __('main.saved'));
        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Client::find($id);
        echo json_encode($supplier);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'type' => [
                'required'
            ],
            'name' => [
                'required',
                Rule::unique('clients', 'name')->ignore($request -> id),
            ],
        ], [
            'type.required' => __('main.type_required'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);
        $client = Client::find($request -> id);
        if($client){


            if ($client->type == 1) {
                $oldOrder = $client->sort;
                $newOrder = $request->sort;
                if ($newOrder != $oldOrder) {
                    if ($newOrder > $oldOrder) {
                        // Moving down: shift up suppliers between old+1 and new
                        Client::where('type', 1)
                            ->whereBetween('sort', [$oldOrder + 1, $newOrder])
                            ->decrement('sort');
                    } else {
                        // Moving up: shift down suppliers between new and old-1
                        Client::where('type', 1)
                            ->whereBetween('sort', [$newOrder, $oldOrder - 1])
                            ->increment('sort');
                    }
                }


            } else {
                $newOrder = 0 ;
            }



            $client -> update([
                'type' => $request -> type, // 0 client , 1 supplier , 2 both
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'buffalo_min_limit' => $request -> buffalo_min_limit ?? 0,
                'buffalo_max_limit' => $request -> buffalo_max_limit ?? 0,
                'bovine_min_limit' => $request -> bovine_min_limit ?? 0,
                'bovine_max_limit' => $request -> bovine_max_limit ?? 0,
                'address' => $request -> address ?? "",
                'car_id' => $request -> car_id  ?? 0,
                'sort'   => $newOrder,
                'is_farmer' => 0 ,
                'user_upd' => Auth::user() -> id
            ]);

            return redirect()->route('suppliers' , $request -> type) -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Client::find($id);
        if($supplier){
            $supplierAccount = ClientAccount::where('client_id', $id)  -> get();
            $supplierInsurance = SupplierInsuranceBalance::where('supplier_id' , $id)  -> get();
            if(count($supplierAccount) > 0 || count($supplierInsurance) > 0){

                return redirect()->route('suppliers' , $supplier -> type) -> with('warning', __('main.can_not_delete'));
            } else {
                $supplier -> delete();
                return redirect()->route('suppliers' , $supplier -> type) -> with('success', __('main.deleted'));
            }

        }
    }

    public function showBalance($id){
        $account = ClientAccount::where('client_id', $id) -> first();
        echo json_encode($account);
        exit();
    }

    public function updateBalance(Request $request){

        $account = ClientAccount::where('client_id', $request -> id) -> first();
        if($account){
            $account -> update([
                'opening_balance_debit' => $request -> opening_balance_debit ?? 0,
                'opening_balance_credit' => $request -> opening_balance_credit ?? 0,
                'user_upd' => Auth::user() -> id,
            ]);
        } else {
            ClientAccount::create([
                'client_id' => $request -> id ,
                'debit' => 0 ,
                'credit' => 0 ,
                'balance' => 0 ,
                'opening_balance_debit' => $request -> opening_balance_debit ?? 0,
                'opening_balance_credit' => $request -> opening_balance_credit ?? 0,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);

        }


       $type = Client::find($request -> id) -> type ;
        return redirect()->route('suppliers' , $type) -> with('success', __('main.updated'));

    }

    public function  getOrder()
    {
        $sort = $request->sort ?? (Client::where('type', 1)->max('sort') + 1);
        echo json_encode($sort);
        exit();

    }
}
