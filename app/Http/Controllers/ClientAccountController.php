<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientAccountController extends Controller
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientAccount  $clientAccount
     * @return \Illuminate\Http\Response
     */
    public function show(ClientAccount $clientAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientAccount  $clientAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientAccount $clientAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientAccount  $clientAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientAccount $clientAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientAccount  $clientAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientAccount $clientAccount)
    {
        //
    }


    public function clientsAccountRebase()
    {
        $clients = Client::all();
        foreach ($clients as $client) {
            $recipits = DB::table('recipits')
                -> where('recipits.supplier_id' , '=' , $client -> id)
                ->select('recipits.amount as amount') ->sum('amount');

            $catchs = DB::table('catch_recipits')
                ->where('catch_recipits.client_id', '=',  $client -> id)
                ->select('catch_recipits.amount as amount') ->sum('amount');

            $dailyMeals = DB::table('daily_milk_meals')
                ->where('daily_milk_meals.state', '=', 1)
                ->where('daily_milk_meals.supplier_id', '=', $client -> id)
                ->select('daily_milk_meals.total as amount') ->sum('daily_milk_meals.total');

            $sales = DB::table('sales')
                -> where('sales.client_id' , '=' , $client -> id)
                ->select('sales.net as amount') ->sum('sales.net');

            $totalDepit = $sales + $recipits ;
            $totalCredit = $dailyMeals  + $catchs;

            $clientAccount = ClientAccount::where('client_id' , '=' , $client -> id) -> first();
            if($clientAccount){
                $clientAccount->update([
                    'client_id' => $client -> id,
                    'debit' =>   $totalDepit,
                    'credit' =>  $totalCredit,
                    'balance' =>  $totalCredit - $totalDepit,
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0
                ]);
            }
            
        }
        
     return "Client Account Rebase";

    }
}
