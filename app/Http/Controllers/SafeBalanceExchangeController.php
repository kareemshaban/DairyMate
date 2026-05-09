<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\SafeBalance;
use App\Models\SafeBalanceExchange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafeBalanceExchangeController extends Controller
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
        if ($isAll == 1) {
            $docs = DB::table('safe_balance_exchanges')
                ->join('safes as source', 'safe_balance_exchanges.from_safe_id', '=', 'source.id')
                ->join('safes as destiantion', 'safe_balance_exchanges.to_safe_id', '=', 'destiantion.id')
                ->select('safe_balance_exchanges.*', 'source.name as source_name', 'destiantion.name as destiantion_name')
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

            $docs = DB::table('safe_balance_exchanges')
                ->join('safes as source', 'safe_balance_exchanges.from_safe_id', '=', 'source.id')
                ->join('safes as destiantion', 'safe_balance_exchanges.to_safe_id', '=', 'destiantion.id')
                ->select('safe_balance_exchanges.*', 'source.name as source_name', 'destiantion.name as destiantion_name')
                ->whereDate('safe_balance_exchanges.date', '>=', $startOfWeek->toDateString())
                ->whereDate('safe_balance_exchanges.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        $safes = Safe::all();

        return view('admin.Safe.transactions', compact('docs', 'safes' , 'is_all'));
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

        if ($request->id == 0) {
            $request->validate(
                [
                    'bill_number' => 'required|unique:safe_balance_exchanges,bill_number',
                    'from_safe_id' => 'required',
                    'balance' => 'required',
                    'to_safe_id' => 'required',
                ]
                ,
                [
                    'bill_number.required' => __('main.bill_number_required'),
                    'from_safe_id.required' => __('main.safe_id_required'),
                    'bill_number.unique' => __('main.bill_number_unique'),
                    'amount.required' => __('main.amount_required'),
                    'to_safe_id.required' => __('main.safe_id_required'),
                ]
            );
            $balance = SafeBalance::where(
                'safe_id',
                '=',
                $request->from_safe_id
            )->first();

            if (!$balance || ((float)$balance->balance + (float)$balance->opening_balance) < (float)$request->balance)
                return redirect()->route('balance_transactions')->with('warning', 'عفوا رصيد الخزنة لا يسمح');
            SafeBalanceExchange::create( [
                'date' => Carbon::parse($request->date),
                'bill_number' => $request->bill_number,
                'from_safe_id' => $request->from_safe_id,
                'to_safe_id' => $request->to_safe_id,
                'balance' => $request->balance,
                'notes' => $request->notes ?? "",
                'user_ins' => Auth::user()->id,
                'user_upd' => 0,
            ]);


            $this->updateSafeBalance($request->from_safe_id, $request->to_safe_id, $request->balance);


            return redirect()->route('balance_transactions')->with('success', __('main.saved'));
        } else {
            return $this->update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SafeBalanceExchange  $safeBalanceExchange
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = SafeBalanceExchange::find($id);
        echo (json_encode($doc));
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SafeBalanceExchange  $safeBalanceExchange
     * @return \Illuminate\Http\Response
     */
    public function edit(SafeBalanceExchange $safeBalanceExchange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SafeBalanceExchange  $safeBalanceExchange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = SafeBalanceExchange::find($request->id);
        $oldBalance = 0;
        if ($doc) {
            $oldBalance = $doc->balance;
            $doc->update([
                'date' => Carbon::parse($request->date),
                'bill_number' => $request->bill_number,
                'from_safe_id' => $request->from_safe_id,
                'to_safe_id' => $request->to_safe_id,
                'balance' => $request->balance,
                'notes' => $request->notes ?? "",
                'user_upd' => Auth::user()->id,

            ]);
            $balance = $request->balance - $oldBalance;

            $this->updateSafeBalance($request->from_safe_id, $request->to_safe_id, $balance);
            return redirect()->route( 'balance_transactions')->with('success', __('main.updated'));


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SafeBalanceExchange  $safeBalanceExchange
     * @return \Illuminate\Http\Response
     */
    public function destroy(SafeBalanceExchange $safeBalanceExchange)
    {
        //
    }


    public function getcode()
    {
        $docs = SafeBalanceExchange::all();
        $dId = 0;
        if (count( $docs) > 0) {
            $dId = count($docs) + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code = 'SE' . $padded;
        echo json_encode($code);
        exit();
    }


    public function updateSafeBalance($from_safe, $to_safe, $amount)
    {
        $source_balance = SafeBalance::where('safe_id' , '=' , $from_safe) -> first();
        $destination_balance = SafeBalance::where('safe_id' , '=' , $to_safe) -> first();
        if ($source_balance) {
            $source_balance->update([
                'outcome' => $source_balance->outcome + $amount,
                'balance' => $source_balance->balance - $amount

            ]);
        }
        if ($destination_balance) {
            $destination_balance->update([
                'income' => $destination_balance->income + $amount,
                'balance' => $destination_balance->balance + $amount

            ]);

        }
    }
}
