<?php

namespace App\Http\Controllers;

use App\Models\CheeseMeal;
use App\Models\Items;
use App\Models\StockTransactionIn;
use App\Models\StockTransactionInDetails;
use App\Models\Store;
use App\Models\StoreQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StockTransactionInController extends Controller
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
        if (!Gate::allows('page-access', [18, 'view'])) {
            abort(403);
        }
        if($isAll == 1 ) {
            $docs = DB::table('stock_transaction_ins')->
            join('cheese_meals', 'cheese_meals.id', '=', 'stock_transaction_ins.meal_id')
                ->join('stores', 'stores.id', '=', 'stock_transaction_ins.store_id')
                ->select('stock_transaction_ins.*', 'cheese_meals.code as cheese_meal',
                    'cheese_meals.symbol',
                    'stores.name as store_name')->get();

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
            $docs = DB::table('stock_transaction_ins')->
            join('cheese_meals', 'cheese_meals.id', '=', 'stock_transaction_ins.meal_id')
                ->join('stores', 'stores.id', '=', 'stock_transaction_ins.store_id')
                ->select('stock_transaction_ins.*', 'cheese_meals.code as cheese_meal',
                    'cheese_meals.symbol',
                    'stores.name as store_name')
                ->whereDate('stock_transaction_ins.date', '>=', $startOfWeek->toDateString())
                ->whereDate('stock_transaction_ins.date', '<=', $endOfWeek->toDateString())
                ->get();
        }
        return view('admin.StockIn.index', compact('docs' , 'is_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $meals = CheeseMeal::where('id' , '=' , $id) -> get();
        $stores = Store::all();

        $item = Items::find($meals[0] -> item_id);

        return view('admin.StockIn.create', compact('meals' , 'stores' , 'item'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                'bill_number' => 'required|unique:stock_transaction_ins,bill_number',
                'meal_id' => 'required',
                'store_id' => 'required'
            ]
            , [
                'bill_number.required' => __('main.bill_number_required'),
                'bill_number.unique'   => __('main.bill_number_unique'),
                'meal_id.required' => __('main.cheese_meal_required'),
                'store_id.required' => __('main.store_required'),

            ]
        );

       $id = StockTransactionIn::create([
            'bill_number' => $request ->bill_number ,
            'meal_id' => $request -> meal_id,
            'date' => Carbon::parse($request -> date),
            'store_id' => $request -> store_id,
            'notes' => $request -> notes ?? "",
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;
       if($id){
           $meal = CheeseMeal::find($request -> meal_id);
           $this -> createStockTransactionDetails($meal , $id);
           $this -> updateStoreQuantity($meal , $request -> store_id);
           $this -> postMeal($meal);

       }

       return redirect()->route('stock_in') -> with('success', __('main.saved'));
    }

    function createStockTransactionDetails($meal , $id)
    {
        StockTransactionInDetails::create([
            'transaction_id' => $id,
            'item_id' => $meal -> item_id,
            'quantity' => $meal -> quantity,
            'weight' => $meal -> weight,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]);
    }

    function postMeal($meal)
    {
        $meal -> update([
            'state' => 1
        ]);
    }

    function updateStoreQuantity($meal , $store_id)
    {
        $storeQuantity = StoreQuantity::where('store_id' , '=' , $store_id)
            -> where('item_id' , '=' , $meal -> item_id)
            -> first();
        if($storeQuantity != null){
            $storeQuantity -> update([
                'quantity_in' => $storeQuantity -> quantity_in + $meal -> quantity,
                'balance' => $storeQuantity -> balance + $meal -> quantity
            ]);
        } else {
            StoreQuantity::create([
                'store_id' => $store_id,
                'item_id' => $meal -> item_id,
                'opening_quantity' => 0,
                'quantity_in' => $meal -> quantity,
                'quantity_out' => 0,
                'balance'  => $meal -> quantity,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockTransactionIn  $stockTransactionIn
     * @return \Illuminate\Http\Response
     */
    public function show(StockTransactionIn $stockTransactionIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTransactionIn  $stockTransactionIn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doc = StockTransactionIn::find($id);
        if($doc){
            $meals = CheeseMeal::where('id' , '=' , $doc ->meal_id ) -> get();
            $stores = Store::all();

            $docDetails = DB::table('stock_transaction_in_details')
                ->join('items', 'items.id', '=', 'stock_transaction_in_details.item_id')
                ->select('stock_transaction_in_details.*', 'items.name')
                ->where('stock_transaction_in_details.transaction_id', '=', $id)
                ->get();


            return view('admin.StockIn.view', compact('meals' , 'stores' , 'docDetails' , 'doc'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockTransactionIn  $stockTransactionIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockTransactionIn $stockTransactionIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTransactionIn  $stockTransactionIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockTransactionIn $stockTransactionIn)
    {
        //
    }

    public function getCode(){
        $docs = StockTransactionIn::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'STI' . $padded;
        echo json_encode($code);
        exit();
    }
}
