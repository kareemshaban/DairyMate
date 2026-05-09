<?php

namespace App\Http\Controllers;

use App\Models\CheeseMeal;
use App\Models\Items;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetails;
use App\Models\StockTransactionIn;
use App\Models\Store;
use App\Models\StoreQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StockTransactionController extends Controller
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

        if (!Gate::allows('page-access', [13, 'view'])) {
            abort(403);
        }
        if($isAll == 1) {
            $docs = DB::table('stock_transactions')
                ->join('stores as fromStore', 'fromStore.id', '=', 'stock_transactions.from_store')
                ->join('stores as toStore', 'toStore.id', '=', 'stock_transactions.to_store')
                ->select('stock_transactions.*',
                    'fromStore.name as store_name_from', 'toStore.name as store_name_to')->get();
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

            $docs = DB::table('stock_transactions')
                ->join('stores as fromStore', 'fromStore.id', '=', 'stock_transactions.from_store')
                ->join('stores as toStore', 'toStore.id', '=', 'stock_transactions.to_store')
                ->select('stock_transactions.*',
                    'fromStore.name as store_name_from', 'toStore.name as store_name_to')
                ->whereDate('stock_transactions.date', '>=', $startOfWeek->toDateString())
                ->whereDate('stock_transactions.date', '<=', $endOfWeek->toDateString())
                ->get();
        }
        return view('admin.StockTransaction.index', compact('docs' , 'is_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::all();
        $items = Items::all();
        $meals = CheeseMeal::all();
        return view('admin.StockTransaction.create', compact('stores', 'items' ,'meals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request ;
        // save the exchange bond header
        //save the exchange bond details
        // for each item in the bond details do the next : -
        // 1- remove the quantity from the from_store (increase the outing_qnt and decrease the balance)
        // add the quantity to the to_store (increase the entering_quantity and increase the balance)

        $request->validate([
                'bill_number' => 'required|unique:stock_transactions,bill_number',
                'message_code' => 'required|unique:stock_transactions,message_code' ,
                'from_store' => 'required',
                'to_store' => 'required',
            ]
            , [
                'bill_number.required' => __('main.bill_number_required'),
                'bill_number.unique'   => __('main.bill_number_unique'),
                'message_code.required' => 'يجب ادخال كود الرسالة',
                'message_code.unique'   => 'لا يمكن تكرار كود الرسالة',
                'from_store.required' => 'يجب تحديد مخزن الصادر',
                'to_store.required' => 'يجب تحديد مخزن الوارد',
            ]
        );

        $id =  StockTransaction::create([
            'bill_number' => $request -> bill_number,
            'message_code' => $request -> message_code ,
            'date' => Carbon::parse($request -> date),
            'from_store' => $request -> from_store,
            'to_store' => $request -> to_store,
            'notes' => $request -> notes ?? "",
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;
        if($id > 0){
            $this -> storeDetails($request , $id);
        }
        if ($request->has('isPost')) {
            return $this -> post($id);


        } else {
            return redirect() -> route('stock_exchange') -> with('success', __('main.saved'));

        }

    }
    public function storeDetails(Request $request , $id){
        $details = StockTransactionDetails::where('transaction_id' , '=' , $id) -> get();
        foreach ($details as $detail){
            $detail -> delete() ;
        }
        for ($i = 0 ; $i < count($request -> item_id ) ; $i++){
            StockTransactionDetails::create([
                'transaction_id' => $id,
                'item_id' => $request -> item_id[$i],
                'meal_id' => $request -> meal_id[$i],
                'store_id' => $request -> item_store_id[$i] ,
                'quantity' => $request -> quantity[$i],
                'weight' => $request -> weight[$i],
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);

            // $this -> updatStores($request -> quantity[$i] , $request -> from_store , $request -> to_store ,$request -> item_id[$i] );

        }
    }
    public function updatStores ($qyantity , $fstore , $t_store , $item , $meal_id){
        $from_store = StoreQuantity::where('store_id' , $fstore )
            -> where('item_id' , '=' , $item)
           // -> where('cheese_meal_id' , '=' , $meal_id)
            -> first();

        $from_store -> update([
            'quantity_out' => $from_store -> quantity_out + $qyantity ,
            'balance' => $from_store -> balance - $qyantity
        ]);

        $to_store =  StoreQuantity::where('store_id' , $t_store )
            -> where('item_id' , '=' , $item)
           // -> where('cheese_meal_id' , '=' , $meal_id)
            ->first();
        if($to_store != null){
            $to_store -> update([
                'quantity_in' => $to_store -> quantity_in + $qyantity ,
                'balance' => $to_store -> balance + $qyantity
            ]);
        } else {
            StoreQuantity::create([
                'store_id' => $t_store,
                'cheese_meal_id' => $meal_id ,
                'item_id' => $item,
                'opening_quantity' => 0,
                'quantity_in' => $qyantity,
                'quantity_out' => 0,
                'balance'  => $qyantity,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockTransaction  $stockTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTransaction  $stockTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doc = StockTransaction::find($id);
        $details = DB::table('stock_transaction_details')
            -> join('items' , 'items.id' , '=' , 'stock_transaction_details.item_id')
            -> select('stock_transaction_details.id as details_id' , 'items.id as id',
                'stock_transaction_details.store_id as store_id', 'stock_transaction_details.meal_id as cheese_meal_id',
                'stock_transaction_details.quantity' , 'stock_transaction_details.weight' , 'items.name' ,
                'items.code' ) -> where('stock_transaction_details.transaction_id' , '=' , $id) -> get();



        foreach ($details as $detail) {
            $balance = StoreQuantity::where('item_id', $detail->id)
                ->first();

            if ($balance) {
                $detail->available_quantity = $balance->balance; // fixed typo here
            } else {
                $detail->available_quantity = 0; // fallback if no balance found
            }
        }

        $stores = Store::all();
        $items = Items::all();

        return view('admin.StockTransaction.edit', compact('stores', 'items' , 'doc' , 'details'));

    }

    public function view($id)
    {
        $doc = StockTransaction::find($id);
        $details = DB::table('stock_transaction_details')
            -> join('items' , 'items.id' , '=' , 'stock_transaction_details.item_id')
            -> select('stock_transaction_details.id as details_id' , 'items.id as id',
                'stock_transaction_details.quantity' , 'stock_transaction_details.weight' , 'items.name' ,
                'items.code' ) -> where('stock_transaction_details.transaction_id' , '=' , $id) -> get();

        $stores = Store::all();
        $items = Items::all();

        return view('admin.StockTransaction.view', compact('stores', 'items' , 'doc' , 'details'));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockTransaction  $stockTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $doc = StockTransaction::find($request -> id) ;
        if($doc != null){
            if($doc -> state == 0){
                $doc -> update([
                    'bill_number' => $request -> bill_number,
                    'message_code' => $request -> message_code ,
                    'date' => Carbon::parse($request -> date),
                    'from_store' => $request -> from_store,
                    'to_store' => $request -> to_store ,
                    'notes' => $request -> notes ?? "",
                    'user_upd' => Auth::user() -> id
                ]);
                $this -> storeDetails($request , $doc -> id);
                return redirect()->route('stock_exchange') -> with('success', __('main.updated'));

            } else {
                return redirect()->route('stock_exchange') -> with('warning', __('main.can_not_edit'));

            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTransaction  $stockTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = StockTransaction::find($id);
        if($doc){
            if($doc -> state == 0){
                $details = StockTransactionDetails::where('transaction_id' , '=' , $id) -> get();
                foreach ($details as $detail){
                    $detail -> delete();
                }
                $doc -> delete();
                return redirect()->route('stock_exchange') -> with('success', __('main.deleted'));

            } else {
                return redirect()->route('stock_exchange') -> with('warning', __('main.can_not_delete'));

            }
        }
    }

    public function getCode(){
        $docs = StockTransaction::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'STE' . $padded;
        echo json_encode($code);
        exit();
    }


    public function post($id)
    {
        $doc = StockTransaction::find($id);
        if($doc != null){
            $details = StockTransactionDetails::where('transaction_id' , '=' , $id) -> get();
            foreach ($details as $detail){
                $this -> updatStores($detail -> quantity , $doc -> from_store , $doc -> to_store ,
                    $detail -> item_id  , $detail -> meal_id);

            }
            $doc -> update([
                'state' => 1,
            ]);
            return redirect()->route('stock_exchange') -> with('success', __('main.updated'));
        }
    }
}
