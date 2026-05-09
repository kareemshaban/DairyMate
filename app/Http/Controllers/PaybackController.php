<?php

namespace App\Http\Controllers;

use App\Models\Payback;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaybackController extends Controller
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
     * @param  \App\Models\Payback  $payback
     * @return \Illuminate\Http\Response
     */
    public function show(Payback $payback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payback  $payback
     * @return \Illuminate\Http\Response
     */
    public function edit(Payback $payback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payback  $payback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payback $payback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payback  $payback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payback $payback)
    {
        //
    }
}
