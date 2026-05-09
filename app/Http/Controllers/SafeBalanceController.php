<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\SafeBalance;
use App\Models\SafeBalanceExchange;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SafeBalanceController extends Controller
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
        $balance = SafeBalance::where('safe_id', '=', $request->safe_id)->first();
        if ($balance) {
            $balance->update([
                'opening_balance' => $request->opening_balance
            ]);
        }
        return redirect()->route('safes')->with('success', __('main.updated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SafeBalance  $safeBalance
     * @return \Illuminate\Http\Response
     */
    public function show($safe_id)
    {
        $safe = DB::table('safes')
            ->join('safe_balances', 'safes.id', '=', 'safe_balances.safe_id')
            ->select('safes.*', 'safe_balances.opening_balance')
            ->where('safes.id', $safe_id)
            ->get()->first();

        echo json_encode($safe);
        exit();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SafeBalance  $safeBalance
     * @return \Illuminate\Http\Response
     */
    public function edit(SafeBalance $safeBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SafeBalance  $safeBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SafeBalance $safeBalance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SafeBalance  $safeBalance
     * @return \Illuminate\Http\Response
     */
    public function destroy(SafeBalance $safeBalance)
    {
        //
    }
}
