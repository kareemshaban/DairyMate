<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Items;
use App\Models\SupplierInsuranceBalance;
use App\Models\SupplierInsuranceItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierInsuranceBalanceController extends Controller
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
    public function index()
    {
         $balances = DB::table('supplier_insurance_balances')
            ->join('clients', 'supplier_insurance_balances.supplier_id', '=', 'clients.id')
            ->select('supplier_insurance_balances.*', 'clients.name as supplier_name')
            ->get();

         return view('admin.Client.Insurance.index' , compact('balances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $suppliers = Client::where('type', '<>' , 0)->get();
         $items = Items::all();

        return view('admin.Client.Insurance.create', compact('suppliers' , 'items'));
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
                    'supplier_id' => 'required',
                    'balance' => 'required',
                ]
                , [
                    'type.required' => __('main.supplier_required'),
                    'name.required' => __('main.amount_required'),
                ]
            );
           $id = SupplierInsuranceBalance::create([
                'supplier_id' => $request -> supplier_id,
                'date' => Carbon::parse($request -> date) ,
                'balance' => $request -> balance ,
                'notes' => $request -> notes ?? "",
                'state' => $request -> state ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]) -> id;
            $this -> storeItems($request  , $id);
            return redirect()->route('insuranceBalances' ,$request -> type) -> with('success', __('main.saved'));
        } else{
            return  $this -> update($request);
        }
    }

    public function storeItems(Request $request , $id)
    {
    $details = SupplierInsuranceItems::where('insurance_id' , $request -> id) -> get();
        foreach($details as $detail){
            $detail -> delete();
        }

      if ($request->has('item_id') && is_array($request->item_id) && count($request->item_id) > 0) {
            for ($i = 0; $i < count($request->item_id); $i++) {
                SupplierInsuranceItems::create([
                    'insurance_id' => $id,
                    'item_id'      => $request->item_id[$i],
                    'quantity'     => $request->quantity[$i] ?? 0,
                    'weight'       => $request->weight[$i] ?? 0,
                    'user_ins'     => Auth::id(),
                    'user_upd'     => 0,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierInsuranceBalance  $supplierInsuranceBalance
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierInsuranceBalance $supplierInsuranceBalance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierInsuranceBalance  $supplierInsuranceBalance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $doc = SupplierInsuranceBalance::find($id);
        // $details = SupplierInsuranceItems::where('insurance_id' , '=' , $id) -> get();
         $details = DB::table('supplier_insurance_items') -> join('items' , 'supplier_insurance_items.item_id' , '=' , 'items.id')
         -> select('supplier_insurance_items.*' , 'items.code as code' , 'items.name as name')
         -> where('supplier_insurance_items.insurance_id' , '=' , $id) -> get();
         $suppliers = Client::where('type', '<>' , 0)->get();
         $items = Items::all();

        return view('admin.Client.Insurance.edit',   compact('doc' , 'details' ,'suppliers' , 'items'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplierInsuranceBalance  $supplierInsuranceBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = SupplierInsuranceBalance::find($request -> id);
        if($doc){
           $doc -> update([
                'supplier_id' => $request -> supplier_id,
                'date' => Carbon::parse($request -> date) ,
                'balance' => $request -> balance ,
                'notes' => $request -> notes ?? "",
               'state' => $request -> state ,
                'user_upd' => Auth::user() -> id
            ]);
            $this -> storeItems($request ,  $doc -> id);
            return redirect()->route('insuranceBalances' ,$request -> type) -> with('success', __('main.updated'));


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierInsuranceBalance  $supplierInsuranceBalance
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierInsuranceBalance $supplierInsuranceBalance)
    {
        //
    }
}
