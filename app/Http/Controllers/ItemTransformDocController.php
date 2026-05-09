<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\ItemTransformDoc;
use App\Models\ItemTransformDocDetails;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetails;
use App\Models\Store;
use App\Models\StoreQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemTransformDocController extends Controller
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
    public function index($isAll = null)
    {
        $is_all = $isAll ?? 0 ;

        if($isAll == 1 ) {
            $docs = DB::table('item_transform_docs')
                ->join('stores as f_store', 'item_transform_docs.from_store', '=', 'f_store.id')
                ->join('stores as t_store', 'item_transform_docs.to_store', '=', 't_store.id')
                ->select('item_transform_docs.*', 'f_store.name as from_store_name', 't_store.name as to_store_name')
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

            $docs = DB::table('item_transform_docs')
                ->join('stores as f_store', 'item_transform_docs.from_store', '=', 'f_store.id')
                ->join('stores as t_store', 'item_transform_docs.to_store', '=', 't_store.id')
                ->select('item_transform_docs.*', 'f_store.name as from_store_name', 't_store.name as to_store_name')
                ->whereDate('item_transform_docs.date', '>=', $startOfWeek->toDateString())
                ->whereDate('item_transform_docs.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        return view('admin.ItemTransformDoc.index', compact('docs' , 'is_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::all();
        $allItems = Items::all();
        return view('admin.ItemTransformDoc.create', compact('stores' , 'allItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     //   return $request ;
        $id =  ItemTransformDoc::create([
            'bill_number' => $request -> bill_number,
            'date' => Carbon::parse($request -> date),
            'from_store' => $request -> from_store,
            'to_store' => $request -> to_store,
            'notes' => $request -> notes ?? "",
            'state' => 0 ,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;
        if($id > 0){
            $this -> storeDetails($request , $id);
        }
           if ($request->has('isPost')) {
            return $this -> postDoc($id);


        } else {
                 return redirect() -> route( 'item_transform_docs') -> with('success', __('main.saved'));

        }

    }

    public function storeDetails(Request $request, $id)
    {
        $details = ItemTransformDocDetails::where('doc_id' , $id) -> get();
        foreach($details as $detail){
            $detail -> delete();
        }

        for ($i = 0 ; $i < count($request -> from_item_id ) ; $i++){
            ItemTransformDocDetails::create([
                'doc_id' => $id,
                'from_item_id' => $request -> from_item_id[$i],
                'to_item_id' => $request -> to_item_id[$i],
                'quantity' => $request -> quantity[$i] ,
                'weight' => $request -> weight[$i] ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTransformDoc  $itemTransformDoc
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = DB::table('item_transform_docs')
            -> join('stores as f_store', 'item_transform_docs.from_store', '=', 'f_store.id')
            -> join('stores as t_store', 'item_transform_docs.to_store', '=', 't_store.id')
            ->select('item_transform_docs.*' , 'f_store.name as from_store_name' , 't_store.name as to_store_name')
            -> where('item_transform_docs.id' , $id) -> first();

        $details = DB::table('item_transform_doc_details')
            -> join('item_transform_docs' , 'item_transform_docs.id' , '=' , 'item_transform_doc_details.doc_id' )
            -> join('items as f_items', 'item_transform_doc_details.from_item_id', '=', 'f_items.id')
            -> join('items as t_items', 'item_transform_doc_details.to_item_id', '=', 't_items.id')
            -> select('item_transform_doc_details.*' , 'item_transform_docs.from_store as item_store_id' ,
                'f_items.name as from_item_name' , 't_items.name as to_item_name' , 'f_items.code as from_item_code' , 't_items.code as to_item_code')
            -> where('item_transform_doc_details.doc_id' , $id) -> get();

        return view('admin.ItemTransformDoc.view', compact('doc', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTransformDoc  $itemTransformDoc
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doc = DB::table('item_transform_docs')
            -> join('stores as f_store', 'item_transform_docs.from_store', '=', 'f_store.id')
            -> join('stores as t_store', 'item_transform_docs.to_store', '=', 't_store.id')
            ->select('item_transform_docs.*' , 'f_store.name as from_store_name' , 't_store.name as to_store_name')
            -> where('item_transform_docs.id' , $id) -> first();

        $details = DB::table('item_transform_doc_details')
           -> join('item_transform_docs' , 'item_transform_docs.id' , '=' , 'item_transform_doc_details.doc_id' )
            -> join('items as f_items', 'item_transform_doc_details.from_item_id', '=', 'f_items.id')
            -> join('items as t_items', 'item_transform_doc_details.to_item_id', '=', 't_items.id')
            -> select('item_transform_doc_details.*' , 'item_transform_docs.from_store as item_store_id' ,
                'f_items.name as from_item_name' , 't_items.name as to_item_name' , 'f_items.code as from_item_code' , 't_items.code as to_item_code')
            -> where('item_transform_doc_details.doc_id' , $id) -> get();

        foreach($details as $detail){
            $store = StoreQuantity::where('store_id' , '=' , $detail -> item_store_id)
                -> where('item_id' , '=' , $detail -> from_item_id)
                -> first();
            if($store) {
                $detail -> available_quantity = $store -> balance + $store -> opening_quantity;
            }
        }



        $stores = Store::all();
        $allItems = Items::all();

        return view('admin.ItemTransformDoc.edit', compact('doc', 'details' , 'stores' , 'allItems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTransformDoc  $itemTransformDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      //  return $request ;
        $doc = ItemTransformDoc::find($request -> id);
        if($doc){
            $doc -> update([
                'bill_number' => $request -> bill_number,
                'date' => Carbon::parse($request -> date),
                'from_store' => $request -> from_store,
                'to_store' => $request -> to_store,
                'notes' => $request -> notes ?? "",
                'state' => 0 ,
                'user_upd' => Auth::user() -> id
            ]);

            $this -> storeDetails($request , $doc -> id);
            return redirect()->route('item_transform_docs') -> with('success', __('main.updated'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTransformDoc  $itemTransformDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = ItemTransformDoc::find($id);
        if($doc){
            if($doc -> state == 0){
                $details = ItemTransformDocDetails::where('doc_id' , '=' , $id) -> get();
                foreach ($details as $detail){
                    $detail -> delete();
                }
                $doc -> delete();
                return redirect()->route('item_transform_docs') -> with('success', __('main.deleted'));

            } else {
                return redirect()->route('item_transform_docs') -> with('warning', __('main.can_not_delete'));

            }
        }
    }

    public function getCode()
    {
        $docs = ItemTransformDoc::all();

        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'ITD' . $padded;
        echo json_encode($code);
        exit();
    }

    public function postDoc($id)
    {
        $doc = ItemTransformDoc::find($id);
        if($doc != null){
            $details = ItemTransformDocDetails::where('doc_id' , '=' , $id) -> get();
            foreach ($details as $detail){
                $this -> updatStores($detail -> quantity , $doc -> from_store , $doc -> to_store ,
                    $detail -> from_item_id  , $detail -> to_item_id);

            }
            $doc -> update([
                'state' => 1,
            ]);
            return redirect()->route('item_transform_docs') -> with('success', __('main.updated'));
        }
    }

    public function updatStores ($qyantity , $fstore , $t_store , $from_item , $to_item){
        $from_store = StoreQuantity::where('store_id' , $fstore )
            -> where('item_id' , '=' , $from_item) -> get() -> first();

        $from_store -> update([
            'quantity_out' => $from_store -> quantity_out + $qyantity ,
            'balance' => $from_store -> balance - $qyantity
        ]);

        $to_store =  StoreQuantity::where('store_id' , $t_store )
            -> where('item_id' , '=' , $to_item) -> get() -> first();
        if($to_store != null){
            $to_store -> update([
                'quantity_in' => $to_store -> quantity_in + $qyantity ,
                'balance' => $to_store -> balance + $qyantity
            ]);
        } else {
            StoreQuantity::create([
                'store_id' => $t_store,
                'cheese_meal_id' => 0 ,
                'item_id' => $to_item,
                'opening_quantity' => 0,
                'quantity_in' => $qyantity,
                'quantity_out' => 0,
                'balance'  => $qyantity,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
        }
    }
}
