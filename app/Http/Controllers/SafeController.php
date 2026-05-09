<?php

namespace App\Http\Controllers;

use App\Models\BoxRecipit;
use App\Models\CatchRecipit;
use App\Models\Loan;
use App\Models\Recipit;
use App\Models\Safe;
use App\Models\SafeBalance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SafeController extends Controller
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
        if (!Gate::allows('page-access', [2, 'view'])) {
            abort(403);
        }
        $safes = DB::table('safes')
            ->leftJoin('safe_balances', 'safes.id', '=', 'safe_balances.safe_id')
            ->select(
                'safes.*',
                DB::raw('(safe_balances.balance + safe_balances.opening_balance) as balance')
            )
            ->get();

        return view('admin.Safe.index', compact('safes'));
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
                    'code' => 'required|unique:safes,code',
                    'name' => 'required|unique:safes,name',
                ]
                , [
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );

            if($request -> isDefault == 1){
                $safes = Safe::where('isDefault' , '=' , 1) -> get() -> first();
                if($safes){
                    $safes -> update([
                        'isDefault' => 0,
                    ]);
                }
            }

          $id = Safe::create([
                'name' => $request -> name,
                'code' => $request -> code,
                'details' => $request -> details ?? "",
                'isDefault' => $request -> isDefault ?? 0,
                'user_ins' => Auth::user()->id,
                'user_upd' => 0
            ]) -> id;

           $this -> createSafeBalance($id);

            return  redirect() -> route('safes') -> with('success', __('main.saved'));
        } else {
            return $this -> update($request);
        }
    }

    function createSafeBalance($id)
    {
        SafeBalance::create([
            'safe_id' => $id,
            'opening_balance' => 0,
            'income' => 0,
            'outcome' => 0,
            'balance' => 0,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Safe  $safe
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $safe = Safe::find($id);
        echo json_encode($safe);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Safe  $safe
     * @return \Illuminate\Http\Response
     */
    public function edit(Safe $safe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Safe  $safe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('safes', 'code')->ignore($request -> id),
            ],
            'name' => [
                'required',
                Rule::unique('safes', 'name')->ignore($request -> id),
            ],
        ], [
            'code.required' => __('main.code_required'),
            'code.unique'   => __('main.code_unique'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);
        $safe = Safe::find($request -> id);
        if($safe){

            if($request -> isDefault == 1){
                $safes = Safe::where('isDefault' , '=' , 1) -> get() -> first();
                if($safes){
                    $safes -> update([
                        'isDefault' => 0,
                    ]);
                }
            }

            $safe -> update([
                'name' => $request -> name,
                'code' => $request -> code,
                'isDefault' => $request -> isDefault,
                'details' => $request -> details ?? "",
                'user_upd' => Auth::user()->id
            ]);

            return  redirect() -> route('safes') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Safe  $safe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $safe = Safe::find($id);
        if($safe){
            $recipits = Recipit::where('safe_id' , $id) -> get();
            $catshes = CatchRecipit::where('safe_id' , $id) -> get();
            $boxRecipits = BoxRecipit::where('safe_id' , $id) -> get();
            $loans = Loan::where('safe_id' , $id) -> get();
            if(count($recipits) == 0 && count($catshes) == 0 && count($boxRecipits) == 0 && count($loans) == 0){
                $safe -> delete();
                return  redirect() -> route('safes') -> with('success', __('main.deleted'));
            } else {
                return  redirect() -> route('safes') -> with('warning', __('main.can_not_delete'));
            }

        }
    }
}
