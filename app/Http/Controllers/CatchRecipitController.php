<?php

namespace App\Http\Controllers;

use App\Models\CatchRecipit;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\Safe;
use App\Models\SafeBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CatchRecipitController extends Controller
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
        if (!Gate::allows('page-access', [16, 'view'])) {
            abort(403);
        }

        if($isAll == 1 ) {
            $docs = DB::table('catch_recipits')
                ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
                ->join('safes', 'catch_recipits.safe_id', '=', 'safes.id')
                ->select('catch_recipits.*', 'clients.name', 'safes.name as  safe')
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

            $docs = DB::table('catch_recipits')
                ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
                ->join('safes', 'catch_recipits.safe_id', '=', 'safes.id')
                ->select('catch_recipits.*', 'clients.name', 'safes.name as  safe')
                ->whereDate('catch_recipits.date', '>=', $startOfWeek->toDateString())
                ->whereDate('catch_recipits.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        $clients = Client::where('type' ,'<>' , 1) -> get();
        $safes = Safe::all();
        return view('admin.Catches.index', compact('docs' , 'clients' , 'safes' , 'is_all'));
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
                    'client_id' => 'required',
                    'amount' => 'required',
                    'safe_id' => 'required',
                    'payment_method' => 'required',
                ]
                , [
                    'bill_number.required' => __('main.bill_number_required'),
                    'client_id.required' => __('main.client_required'),
                    'bill_number.unique'   => __('main.bill_number_unique'),
                    'amount.required' => __('main.amount_required'),
                    'safe_id.required' => __('main.safe_id_required'),
                    'payment_method.required' => __('main.payment_method_required'),
                ]
            );
           $id = CatchRecipit::create([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'client_id' => $request -> client_id,
                'amount' => $request -> amount,
                'payment_method' => $request -> payment_method,
                'safe_id' => $request -> safe_id,
                'notes' => $request -> notes ?? "",
                'state' => 0 ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]) -> id;

          //  $this -> updateClientAccount(0, $request -> amount  , $request -> client_id );
           // $this -> updateSafeBalance($request ->  amount, 0 , $request -> safe_id );

           if ($request->has('isPost')) {
                return $this -> post($id);


            } else {
            return redirect() -> route('catches') -> with('success', __('main.saved'));

            }

        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CatchRecipit  $catchRecipit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = CatchRecipit::find($id);
        echo json_encode($doc);
        exit();
    }
    public function print($id)
    {
        $doc = DB::table('catch_recipits')
            ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
            ->join('safes', 'catch_recipits.safe_id', '=', 'safes.id')
            ->select('catch_recipits.*', 'clients.name', 'safes.name as safe')
            -> where('catch_recipits.id', $id)
            ->first();

        return view('admin.Catches.print' , compact('doc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CatchRecipit  $catchRecipit
     * @return \Illuminate\Http\Response
     */
    public function edit(CatchRecipit $catchRecipit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CatchRecipit  $catchRecipit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = CatchRecipit::find($request -> id);
        if($doc != null){
            $oldAmount = $doc -> amount;
            $doc -> update([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'client_id' => $request -> client_id,
                'amount' => $request -> amount,
                'payment_method' => $request -> payment_method,
                'safe_id' => $request -> safe_id,
                'state' => 0 ,
                'notes' => $request -> notes ?? "",
                'user_upd' => Auth::user() -> id
            ]);
            $netAmount = $request -> amount - $oldAmount;

         //   $this -> updateClientAccount(0 , $netAmount , $request -> client_id );
         //   $this -> updateSafeBalance($netAmount , 0 , $request -> safe_id );

        }
        return redirect()->route('catches') -> with('success', __('main.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CatchRecipit  $catchRecipit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = CatchRecipit::find($id);
        if($doc != null){
            $oldAmount = $doc -> amount;

            $doc -> delete();
            $netAmount = 0 - $oldAmount;

          //  $this -> updateClientAccount(0 , $netAmount , $doc -> client_id );
          //  $this -> updateSafeBalance($netAmount , 0 , $doc -> safe_id );

            return redirect()->route('catches') -> with('success', __('main.deleted'));

        }
    }

    public function getCode(){
        $docs = CatchRecipit::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'CB' . $padded;

        $defaultSafe = Safe::where('isDefault' , '=' , 1) -> first();

        return response()->json([
            'code' => $code,
            'safe' => $defaultSafe
        ]);
    }

    public function updateClientAccount($debit , $credit , $client_id)
    {
        $account = ClientAccount::where('client_id' , $client_id)->first();
        if($account){
            $account->update([
                'client_id' => $client_id,
                'debit' => $account -> debit  +  $debit,
                'credit' => $account -> credit  + $credit,
                'balance' => $account -> balance + $credit - $debit,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
        } else {
            ClientAccount::create([
                'client_id' => $client_id,
                'debit' => $debit,
                'credit' => $credit,
                'opening_balance_debit' => 0,
                'opening_balance_credit' => 0,
                'balance' => $credit - $debit,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
        }
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
        $doc = CatchRecipit::find($id);
        if($doc != null){
            $doc -> update([
                'state' => 1,
            ]);
             $this -> updateClientAccount(0, $doc -> amount  , $doc -> client_id );
             $this -> updateSafeBalance($doc ->  amount, 0 , $doc -> safe_id );

            return redirect()->route('catches') -> with('success', __('main.posted'));
        }
    }
}
