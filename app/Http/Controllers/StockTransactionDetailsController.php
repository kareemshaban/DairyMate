<?php

namespace App\Http\Controllers;

use App\Models\StockTransactionDetails;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StockTransactionDetailsController extends Controller
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
     * @param  \App\Models\StockTransactionDetails  $stockTransactionDetails
     * @return \Illuminate\Http\Response
     */
    public function show(StockTransactionDetails $stockTransactionDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTransactionDetails  $stockTransactionDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(StockTransactionDetails $stockTransactionDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockTransactionDetails  $stockTransactionDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockTransactionDetails $stockTransactionDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTransactionDetails  $stockTransactionDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockTransactionDetails $stockTransactionDetails)
    {
        //
    }
}
