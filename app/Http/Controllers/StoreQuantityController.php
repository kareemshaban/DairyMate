<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Material;
use App\Models\MaterialQuantity;
use App\Models\Store;
use App\Models\StoreQuantity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreQuantityController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('items')
            ->join('store_quantities', 'items.id', '=', 'store_quantities.item_id')
            ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
            ->select('items.name as item_name', 'items.id as item_id' ,
                'store_quantities.*', 'stores.name as store_name' , 'store_quantities.id as store_quantity_id')
            ->get();

        $stores = Store::all();
        $items = Items::where('type' , '=' , 0)
            ->orWhere('type' , '=' ,  3)
            -> orWhere('type' , '=' ,  1) // Assuming type 0, 3, and 4 are the types you want to include
            // Assuming type 0 and 1 are the types you want to include
            ->get();
        return  view('admin.items-quantity.index', compact('data' , 'stores' , 'items'));
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
       // return $request ;
        if($request -> id == 0){

            $request->validate([
                    'store_id' => 'required',
                    'item_id' => 'required',
                    'opening_quantity' => 'required',
                ]
                , [
                    'store_id.required' => __('main.store_required'),
                    'item_id.required' => __('main.item_required'),
                    'opening_quantity.required' => __('main.opening_quantity_required'),
                ]
            );

            $sameRecord = StoreQuantity::where('store_id' , '=' , $request -> store_id)
                -> where('item_id' , '=' , $request -> item_id) -> get();
            if($sameRecord->count() > 0){
                return redirect()->route('items-quantity') -> with('warning', 'عفوا لا يمكن انشاء كمية افتتاحية بنفس الصنف و نفس المخزن ');


            }
            StoreQuantity::create([
                'store_id' => $request -> store_id,
                'item_id' => $request -> item_id,
                'cheese_meal_id' => 0 ,
                'opening_quantity' => $request -> opening_quantity,
                'quantity_in' => $request -> quantity_in,
                'quantity_out' => $request -> quantity_out,
                'balance'  => $request -> balance,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);

            return redirect()->route('items-quantity') -> with('success', __('main.saved'));

        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreQuantity  $storeQuantity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $materialQuantity = StoreQuantity::find($id);
        echo json_encode($materialQuantity);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreQuantity  $storeQuantity
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreQuantity $storeQuantity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreQuantity  $storeQuantity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'm_store_id' => 'required',
            'm_item_id' => 'required',
            'opening_quantity' => 'required',
        ], [
            'm_store_id.required' => __('main.store_required'),
            'm_item_id.required' => __('main.material_required'),
            'opening_quantity.required' => __('main.opening_quantity_required'),
        ]);

        $mQuantity = StoreQuantity::find($request -> id);
        $mQuantity -> update([
            'store_id' => $request -> m_store_id,
            'item_id' => $request -> m_item_id,
            'opening_quantity' => $request -> opening_quantity,
            'user_upd' => Auth::user() -> id
        ]);

        return redirect()->route('items-quantity') -> with('success', __('main.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreQuantity  $storeQuantity
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreQuantity $storeQuantity)
    {
        //
    }
}
