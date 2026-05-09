<?php

namespace App\Http\Controllers;

use App\Models\CheeseMeal;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\Items;
use App\Models\Sales;
use App\Models\SalesDetails;
use App\Models\Store;
use App\Models\StoreQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SalesController extends Controller
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
    public function index($isAll = null)
    {
        $is_all = $isAll ?? 0 ;
        if (!Gate::allows('page-access', [11, 'view'])) {
            abort(403);
        }
        if($isAll == 1) {
            $docs = DB::table('sales')
                ->join('clients', 'clients.id', '=', 'sales.client_id')
                ->leftJoin('stores', 'stores.id', '=', 'sales.store_id')
                ->select('sales.*', 'clients.name as client_name', 'stores.name as store_name')
                ->get();
        } else {
            $today = Carbon::now();

            if ($today->dayOfWeek < Carbon::FRIDAY) {
                // If today is before Friday, go back to last week's Friday
                $startOfWeek = $today->copy()->subDays(7 - (Carbon::FRIDAY - $today->dayOfWeek));
            } elseif ($today->dayOfWeek > Carbon::FRIDAY) {
                // If today is after Friday, go back to this week's Friday
                $startOfWeek = $today->copy()->subDays($today->dayOfWeek - Carbon::FRIDAY);
            } else {
                // If today is exactly Friday
                $startOfWeek = $today->copy();
            }

            $endOfWeek = $startOfWeek->copy()->addDays(6);
            $docs = DB::table('sales')
                ->join('clients', 'clients.id', '=', 'sales.client_id')
                ->leftJoin('stores', 'stores.id', '=', 'sales.store_id')
                ->select('sales.*', 'clients.name as client_name', 'stores.name as store_name')
                ->whereDate('sales.date', '>=', $startOfWeek->toDateString())
                ->whereDate('sales.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        return view('admin.Sales.index', compact('docs' , 'is_all'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $stores = Store::all();
        $items = Items::all();
        $meals = CheeseMeal::all();

        return view('admin.Sales.create', compact('clients', 'stores' , 'items' , 'meals'));
    }

    function getStoreMeals($store_id)
    {
        $meals = DB::table('store_quantities')
            ->join('cheese_meals', 'cheese_meals.id', '=', 'store_quantities.cheese_meal_id')
            ->select('cheese_meals.id', 'cheese_meals.code as meal_code')
            -> where ('store_quantities.store_id' , $store_id)
            ->groupBy('cheese_meals.id', 'cheese_meals.code')
            ->get();

        echo json_encode($meals);
        exit();
    }

    public function getMealItems($meal_id , $store_id )
    {
        $meals = DB::table('store_quantities')
            ->join('items', 'items.id', '=', 'store_quantities.item_id')
            ->select('items.*', 'store_quantities.balance as balance' , 'store_quantities.cheese_meal_id')
            -> where ('store_quantities.cheese_meal_id' , $meal_id)
            -> where ('store_quantities.store_id' , $store_id)
            -> where ('store_quantities.balance' , '>' , 0)
            ->get();

        echo json_encode($meals);
        exit();
    }

    public function get_store_items( $id)
    {
        $items = DB::table('store_quantities')
            ->join('items', 'items.id', '=', 'store_quantities.item_id')
            ->leftJoin('cheese_meals', 'store_quantities.cheese_meal_id', '=', 'cheese_meals.id')
            ->select(
                'items.*',
                'cheese_meals.code as cheese_meal_code',
                'cheese_meals.symbol',
                'cheese_meals.id as meal_id',
                DB::raw('(store_quantities.balance + store_quantities.opening_quantity) as balance')
            )
            ->where('store_quantities.store_id', $id)
            ->whereRaw('(store_quantities.balance + store_quantities.opening_quantity) > 0')
            ->when(request()->filled('meal_id'), function ($q) {
                $q->where('store_quantities.cheese_meal_id', request('meal_id'));
            })
            ->get();

        echo json_encode($items);
        exit();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request ;
        $request->validate([
                'bill_number' => 'required|unique:sales,bill_number',
                'client_id' => 'required',
            ]
            , [
                'bill_number.required' => __('main.bill_number_required'),
                'bill_number.unique'   => __('main.bill_number_unique'),
                'client_id.required' => __('main.client_required'),
            ]
        );
        //create invoice header

        $storeIds = $request->item_store_id ?? [];
        $storeIds = array_filter($storeIds);
        $uniqueStores = array_unique($storeIds);
        if (count($uniqueStores) == 1) {
            $headerStoreId = reset($uniqueStores);
        } else {
            $headerStoreId = 0;
        }
        $id = Sales::create([
            'bill_number' => $request -> bill_number,
            'date' => Carbon::parse($request -> date),
            'client_id' => $request -> client_id,
            'store_id' => $headerStoreId,
            'total' => $request -> billTotal,
            'discount' => $request ->discount ?? 0 ,
            'net' => $request -> net,
            'notes' => $request -> notes ?? "",
            'state' => 0 ,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;

        //store invoice details
        $this -> storeDetails($request , $id) ;

        if ($request->has('isPost')) {
            return $this -> post($id);


        } else {
            return redirect() -> route('sales') -> with('success', __('main.saved'));

        }


    }

    public function storeDetails(Request $request , $id)
    {
        $details = SalesDetails::where('bill_id' , '=' , $id) -> get();
        foreach ($details as $detail) {
            $detail -> delete();
        }
        for ($i = 0 ; $i < count($request -> item_id ) ; $i++){
            SalesDetails::create([
                'bill_id' => $id,
                'date' => Carbon::parse($request ->date),
                'item_id' => $request -> item_id[$i],
                'store_id' => $request -> item_store_id[$i],
                'meal_id' =>  0,
                'quantity' => $request -> quantity[$i],
                'weight' => $request -> weight[$i],
                'price' => $request -> price[$i],
                'total' => $request -> total[$i],
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);



            // $this -> updatStores($request -> quantity[$i] , $request -> from_store , $request -> to_store ,$request -> item_id[$i] );

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //view
        $doc = DB::table('sales') -> join('clients' , 'clients.id', '=', 'sales.client_id')
            -> leftJoin('stores' , 'stores.id', '=', 'sales.store_id')
            -> select('sales.*', 'clients.name as client_name', 'stores.name as store_name')
            -> where('sales.id' , $id) -> first();

        //  return $doc ;

        $details = DB::table('sales_details')
            -> join('items' , 'items.id', '=', 'sales_details.item_id')
            -> join('stores' , 'stores.id', '=', 'sales_details.store_id')
            -> select('sales_details.*' , 'items.name as item_name' , 'items.code as item_code' , 'stores.name as store_name')
            -> where('sales_details.bill_id' , $id) -> get();

        $clients = Client::where('type' , '<>' , 1) -> get();
        $stores = Store::all();
        $items = Items::all();
        return view('admin.Sales.view', compact('clients', 'stores' , 'doc' , 'details' , 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doc = Sales::find($id);
        $details = DB::table('sales_details')
            -> join('items' , 'items.id', '=', 'sales_details.item_id')
            -> join('stores' , 'stores.id', '=', 'sales_details.store_id')
            -> select('sales_details.*' , 'items.name as name' , 
                'items.code as code' , 'stores.name as store_name' , 'stores.id as item_store_id')
            -> where('sales_details.bill_id' , $id) -> get();

        foreach ($details as $detail) {
            $balance = StoreQuantity::where('store_id', $detail->store_id)
                ->where('item_id', $detail->item_id)
                ->first();

            if ($balance) {
                $detail->available_quantity = $balance->balance; // fixed typo here
            } else {
                $detail->available_quantity = 0; // fallback if no balance found
            }
        }

        $clients = Client::all();
        $stores = Store::all();

        $items = Items::all();

        // return $details ;
        return view('admin.Sales.edit', compact('clients', 'stores' , 'doc' , 'details' , 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // return $request ;
        $sale = Sales::find($request -> id);
        if($sale != null) {
            if($sale -> state == 0){

                $storeIds = $request->item_store_id ?? [];
                $storeIds = array_filter($storeIds);
                $uniqueStores = array_unique($storeIds);
                if (count($uniqueStores) == 1) {
                    $headerStoreId = reset($uniqueStores);
                } else {
                    $headerStoreId = 0;
                }

                $sale -> update([
                    'bill_number' => $request -> bill_number,
                    'date' => Carbon::parse($request -> date),
                    'client_id' => $request -> client_id,
                    'store_id' => $headerStoreId,
                    'total' => $request -> billTotal,
                    'discount' => $request ->discount ?? 0 ,
                    'net' => $request -> net,
                    'notes' => $request -> notes ?? "",
                    'state' => 0 ,
                    'user_upd' => Auth::user() -> id
                ]);
                $this -> storeDetails($request , $sale -> id) ;
                return redirect() -> route('sales') -> with('success', __('main.updated'));

            } else {
                return redirect('sales') -> with('warning', __('main.can_not_edit_posted_invoice'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sales = Sales::find($id);
        if($sales != null) {
            if($sales -> state == 0){
                $details = SalesDetails::where('bill_id' , '=' , $id) -> get();
                foreach ($details as $detail) {
                    $detail -> delete();
                }
                $sales -> delete();

                return redirect('sales') -> with('success', __('main.deleted'));

            } else {
                return redirect('sales') -> with('warning', __('main.can_not_delete_posted_invoice'));
            }
        }
    }

    public function getCode(){
        $docs = Sales::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'SI' . $padded;
        echo json_encode($code);
        exit();
    }


    public function post($id)
    {
        $doc = Sales::find($id);
        if($doc != null){
            $this -> updateClientAccount($doc -> client_id , $doc -> net);
            $details = SalesDetails::where('bill_id' , '=' , $id) -> get();
            foreach ($details as $detail){
                $this -> updatStores($detail -> quantity ,  $detail -> item_id  , $detail -> store_id , $detail -> meal_id  );

            }
            $doc -> update([
                'state' => 1,
            ]);
            return redirect()->route('sales') -> with('success', __('main.updated'));
        }
    }

    public function updatStores ($qyantity , $item , $store_id , $meal_id ){
        $store = StoreQuantity::where('store_id' , $store_id )
            -> where('item_id' , '=' , $item)
          //  -> where('cheese_meal_id' , '=' , $meal_id)
            -> first();

        if($store != null){
            $store -> update([
                'quantity_out' => $store -> quantity_out + $qyantity ,
                'balance' => $store -> balance - $qyantity
            ]);
        } else {
            StoreQuantity::create([
                'store_id' => $store_id,
                'cheese_meal_id' => $meal_id,
                'item_id' => $item,
                'opening_quantity' => 0,
                'quantity_in' => 0,
                'quantity_out' => $qyantity,
                'balance'  => -1 * $qyantity,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
        }
    }

    public function updateClientAccount($client_id , $net)
    {
        $client_account = ClientAccount::where('client_id' , '=' , $client_id) -> first();
        if($client_account != null){
            $client_account -> update([
                'debit' => $client_account -> debit + $net ,
                'balance' => $client_account -> balance  - $net,
            ]);
        } else {
            ClientAccount::create([
                'client_id' => $client_id,
                'debit' => $net,
                'credit' => 0,
                'opening_balance_debit' => 0,
                'opening_balance_credit' => 0,
                'balance' =>  -1 * $net,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
        }
    }


}
