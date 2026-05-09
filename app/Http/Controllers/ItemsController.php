<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Store;
use App\Models\StoreQuantity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ItemsController extends Controller
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
        if (!Gate::allows('page-access', [4, 'view'])) {
            abort(403);
        }

        $items = Items::all();
        return view('admin.Items.index', compact('items'));
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
                    'code' => 'required|unique:items,code',
                    'name' => 'required|unique:items,name',
                ]
                , [
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );
            Items::create([
                'code' => $request -> code,
                'name' => $request -> name,
                'details' => $request -> details ?? "",
                'default_selling_price' => $request -> default_selling_price ?? "0",
                'type' => $request -> type ?? 0,
                'user_ins' => Auth::user()->id,
                'user_upd' => 0
            ]);

            return redirect()->route('items') -> with('success', __('main.saved'));

        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Items::find($id);
        echo json_encode($item);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function edit(Items $items)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('items', 'code')->ignore($request -> id),
            ],
            'name' => [
                'required',
                Rule::unique('items', 'name')->ignore($request -> id),
            ],
        ], [
            'code.required' => __('main.code_required'),
            'code.unique'   => __('main.code_unique'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);

        $item = Items::find($request -> id) ;
        if($item){
            $item -> update([
                'code' => $request -> code,
                'name' => $request -> name,
                'details' => $request -> details ?? "",
                'default_selling_price' => $request -> default_selling_price ?? "0",
                'type' => $request -> type ?? 0,
                'user_upd' => Auth::user()->id
            ]);
            return redirect()->route('items') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Items::find($id);
        if($item){
            $storeQuantity = StoreQuantity::where('item_id', $id) -> get();
            if(count($storeQuantity ) == 0){
                $item -> delete();
                return redirect()->route('items') -> with('success', __('main.deleted'));
            } else {
                return redirect()->route('items') -> with('warning', __('main.can_not_delete'));
            }

        }
    }

    public function itemSelect($id , $store , $meal)
    {
        $item = Items::find($id);
        if($item){
            $storeQuantity = StoreQuantity::where('store_id' , '=' , $store)
                -> where('item_id' , '=' , $id)
                -> where('cheese_meal_id' , '=' , $meal)
                -> get() -> first();


            if($storeQuantity){
                $item -> available_quantity = $storeQuantity -> balance  + $storeQuantity -> opening_quantity;
                $item -> item_store_id = $storeQuantity -> store_id ;
                $item -> item_meal_id = $storeQuantity -> cheese_meal_id ;
            } else {
                $item -> available_quantity = 0;
                $item -> item_store_id = $store;
                $item -> item_meal_id = $meal;
            }

            echo json_encode($item);
            exit();
        }
    }

    public function itemSelectWithoutMeal($id , $store )
    {
        $item = Items::find($id);
        if($item){
            $storeQuantity = StoreQuantity::where('store_id' , '=' , $store)
                -> where('item_id' , '=' , $id)
                -> get() -> first();
            
            $store = Store::find($store);


            if($storeQuantity){
                $item -> available_quantity = $storeQuantity -> balance  + $storeQuantity -> opening_quantity;
                $item -> item_store_id = $storeQuantity -> store_id ;
                $item -> item_meal_id = $storeQuantity -> cheese_meal_id ;
                $item -> store_name = $store -> name ;
            } else {
                $item -> available_quantity = 0;
                $item -> item_store_id = $store;
                $item -> item_meal_id = '';
                $item -> store_name = $store -> name ;
            }

            echo json_encode($item);
            exit();
        }
    }

    public function itemPairSelect($fItem , $tItem , $store)
    {
        $item = Items::find($fItem);
        $to_Item = Items::find($tItem);
        if($item){
            $storeQuantity = StoreQuantity::where('store_id' , '=' , $store)
                -> where('item_id' , '=' , $fItem)
                -> get() -> first();


            if($storeQuantity){
                $item -> available_quantity = $storeQuantity -> balance +  $storeQuantity -> opening_quantity;
                $item -> item_store_id = $storeQuantity -> store_id ;
            } else {
                $item -> available_quantity = 0;
                $item -> item_store_id = $store;
            }

            return response()->json([
                'status' => 'success',
                'from_item_object' => $item,
                'to_item_object' => $to_Item
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'error' => 'Item not found.'
            ], 404);
        }
    }


}
