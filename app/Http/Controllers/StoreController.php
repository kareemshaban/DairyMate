<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\Store;
use App\Models\StoreQuantity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreController extends Controller
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

        if (!Gate::allows('page-access', [1, 'view'])) {
            abort(403);
        }

        $stores = Store::all();
        return view('admin.Store.index', compact('stores'));
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
                    'code' => 'required|unique:stores,code',
                    'name' => 'required|unique:stores,name',
                ]
                , [
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );

            if($request -> isDefault == 1){
                $stores = Store::where('isDefault' , '=' , 1) -> get() -> first();
                if($stores){
                    $stores -> update([
                        'isDefault' => 0,
                    ]);
                }
            }

            Store::create([
                'code' => $request -> code,
                'name' => $request -> name,
                'details' => $request -> details ?? "",
                'isDefault' => $request -> isDefault ?? 0,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);


            return redirect()->route('stores') -> with('success', __('main.saved'));

        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::find($id);
        echo json_encode($store);
        exit();
    }

    public function store_balance($id)
    {
        $store = Store::find($id);
       $stores = DB::table('store_quantities')
        ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
        ->join('items', 'store_quantities.item_id', '=', 'items.id')
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            DB::raw('SUM(store_quantities.balance + store_quantities.opening_quantity) as total_quantity')
        )
        ->where('stores.id', '=', $id)
        ->groupBy('items.id', 'items.name')
        ->get();





        return response()->json([
            'state' => 'success',
            'stores' => $stores,
            'store' => $store
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('stores', 'code')->ignore($request -> id),
            ],
            'name' => [
                'required',
                Rule::unique('stores', 'name')->ignore($request -> id),
            ],
        ], [
            'code.required' => __('main.code_required'),
            'code.unique'   => __('main.code_unique'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);
        $store = Store::find($request -> id);
        if($store){

            if($request -> isDefault == 1){
                $stores = Store::where('isDefault' , '=' , 1) -> get() -> first();
                if($stores){
                    $stores -> update([
                        'isDefault' => 0,
                    ]);
                }
            }

            $store -> update([
                'code' => $request -> code,
                'name' => $request -> name,
                'details' => $request -> details ?? "",
                'isDefault' => $request -> isDefault,
                'user_upd' => Auth::user() -> id,
            ]);
            return redirect()->route('stores') -> with('success', __('main.updated'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::find($id);
        if($store){
            $storeQuantities = StoreQuantity::where('store_id' , '=' , $id) -> get();
            if(count($storeQuantities) > 0 ){
                return redirect()->route('stores') -> with('warning', __('main.can_not_delete'));
            } else {
                $store -> delete();
                return redirect()->route('stores') -> with('success', __('main.deleted'));
            }

        }
    }
}
