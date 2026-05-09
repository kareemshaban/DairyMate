<?php

namespace App\Http\Controllers;

use App\Models\BoxRecipit;
use App\Models\Client;
use App\Models\Recipit;
use App\Models\RecipitType;
use App\Models\Safe;
use App\Models\SafeBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BoxRecipitController extends Controller
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
        if (!Gate::allows('page-access', [17, 'view'])) {
            abort(403);
        }
        if($isAll == 1 ) {
            $docs = DB::table('box_recipits')
                ->join('recipit_types', 'box_recipits.recipit_type', '=', 'recipit_types.id')
                ->join('safes', 'box_recipits.safe_id', '=', 'safes.id')
                ->select('box_recipits.*', 'recipit_types.name', 'safes.name as safe')
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

            $docs = DB::table('box_recipits')
                ->join('recipit_types', 'box_recipits.recipit_type', '=', 'recipit_types.id')
                ->join('safes', 'box_recipits.safe_id', '=', 'safes.id')
                ->select('box_recipits.*', 'recipit_types.name', 'safes.name as safe')
                ->whereDate('box_recipits.date', '>=', $startOfWeek->toDateString())
                ->whereDate('box_recipits.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        $types = RecipitType::all();
        $safes = Safe::all();
        return view('admin.BoxRecipits.index', compact('docs' , 'types' , 'safes' , 'is_all'));
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
                    'bill_number' => 'required|unique:recipits,bill_number',
                    'recipit_type' => 'required',
                    'amount' => 'required',
                    'safe_id' => 'required',
                ]
                , [
                    'bill_number.required' => __('main.bill_number_required'),
                    'recipit_type.required' => __('main.recipit_type_required'),
                    'bill_number.unique'   => __('main.bill_number_unique'),
                    'amount.required' => __('main.amount_required'),
                    'safe_id.required' => __('main.safe_id_required'),
                ]
            );

           $id = BoxRecipit::create([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'recipit_type' => $request -> recipit_type,
                'amount' => $request -> amount,
                'safe_id' => $request -> safe_id,
                'notes' => $request -> notes ?? "",
                'state' => 0 ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]) -> id;

          if ($request->has('isPost')) {
            return $this -> post($id);


        } else {
        return redirect() -> route('boxRecipits') -> with('success', __('main.saved'));

        }

        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BoxRecipit  $boxRecipit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $doc = BoxRecipit::find($id);
         echo json_encode($doc);
         exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BoxRecipit  $boxRecipit
     * @return \Illuminate\Http\Response
     */
    public function edit(BoxRecipit $boxRecipit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BoxRecipit  $boxRecipit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = BoxRecipit::find($request -> id);
        if($doc != null){
            $oldAmount = $doc -> amount;
            $doc -> update([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'recipit_type' => $request -> recipit_type,
                'amount' => $request -> amount,
                'safe_id' => $request -> safe_id,
                'notes' => $request -> notes ?? "",
                'state' => 0 ,
                'user_upd' => Auth::user() -> id
            ]);
            $netAmount = $request -> amount - $oldAmount;
         //   $this -> updateSafeBalance(0 , $netAmount , $request -> safe_id);
        }
        return redirect()->route('boxRecipits') -> with('success', __('main.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoxRecipit  $boxRecipit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = BoxRecipit::find($id);
        if($doc != null){
            $oldAmount = $doc -> amount;

            $netAmount = 0 - $oldAmount;
         //   $this -> updateSafeBalance(0 , $netAmount , $doc->safe_id );
            $doc -> delete();




            return redirect()->route('boxRecipits') -> with('success', __('main.deleted'));

        }
    }

    public function getCode(){
        $docs = BoxRecipit::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'BB' . $padded;

        $defaultSafe = Safe::where('isDefault' , '=' , 1) -> first();

        return response()->json([
            'code' => $code,
            'safe' => $defaultSafe
        ]);
    }

    public function updateSafeBalance($income , $outcome , $safe_id)
    {
        $balance = SafeBalance::where('safe_id' , $safe_id)->first();
        if($balance){
            $balance->update([
                'income' => $balance -> income + $income ,
                'outcome' => $balance -> outcome + $outcome,
                'balance' => $balance -> balance + $income - $outcome,
            ]);
        }
    }

    public function post($id)
    {
        $doc = BoxRecipit::find($id);
        if($doc != null){
            $doc -> update([
                'state' => 1 ,
            ]);

            $this -> updateSafeBalance(0 , $doc -> amount , $doc -> safe_id);
            return redirect()->route('boxRecipits') -> with('success', __('main.posted'));


        }

    }
}
