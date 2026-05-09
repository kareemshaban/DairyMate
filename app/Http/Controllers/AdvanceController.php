<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\CatchRecipit;
use App\Models\ClientAccount;
use App\Models\SafeBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvanceController extends Controller
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

        if($isAll == 1) {
            $docs = DB::table('advances')
                ->join('employees', 'employees.id', '=', 'advances.employee_id')
                ->join('safes', 'safes.id', '=', 'advances.safe_id')
                ->select('advances.*', 'employees.name as employee_name' , 'safes.name as safe_name')
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
            $docs = DB::table('advances')
                ->join('employees', 'employees.id', '=', 'advances.employee_id')
                ->join('safes', 'safes.id', '=', 'advances.safe_id')
                ->select('advances.*', 'employees.name as employee_name' , 'safes.name as safe_name')
                ->whereDate('advances.date', '>=', $startOfWeek->toDateString())
                ->whereDate('advances.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        $employees = DB::table('employees')->get();
        $safes = DB::table('safes')->get();

        return view('admin.Advance.index', compact('docs' , 'is_all' , 'employees', 'safes'));
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
                    'employee_id' => 'required',
                    'amount' => 'required',
                    'safe_id' => 'required',
                ]
                , [
                    'employee_id.required' => __('main.employee_required'),
                    'amount.required' => __('main.amount_required'),
                    'safe_id.required' => __('main.safe_id_required'),
                ]
            );
            $id = Advance::create([
                'date' => Carbon::parse($request -> date),
                'employee_id' => $request -> employee_id,
                'amount' => $request -> amount,
                'safe_id' => $request -> safe_id,
                'description' => $request -> description ?? "",
                'paid_back' => 0 ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]) -> id;

            return $this -> post($id);

        } else{
            return  $this -> update($request);
        }
    }

    public function post($id)
    {
        $doc = Advance::find($id);
        if($doc != null){

            $this -> updateSafeBalance(0 , $doc ->  amount , $doc -> safe_id );

            return redirect()->route('advances') -> with('success', __('main.posted'));
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advance  $advance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = Advance::find($id);
        echo json_encode($doc);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advance  $advance
     * @return \Illuminate\Http\Response
     */
    public function edit(Advance $advance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advance  $advance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
                'employee_id' => 'required',
                'amount' => 'required',
                'safe_id' => 'required',
            ]
            , [
                'employee_id.required' => __('main.employee_required'),
                'amount.required' => __('main.amount_required'),
                'safe_id.required' => __('main.safe_id_required'),
            ]
        );

        $doc = Advance::find($request -> id);
        $oldAmount = $doc -> amount;
        if($doc != null) {
            if ($doc->paid_back == 0) {
                $doc->update([
                    'date' => Carbon::parse($request->date),
                    'employee_id' => $request->employee_id,
                    'amount' => $request->amount,
                    'safe_id' => $request->safe_id,
                    'description' => $request->description ?? "",
                    'paid_back' => 0,
                    'user_upd' => Auth::user()->id,
                ]);
                $amount =  $doc -> amount - $oldAmount;
                $this -> updateSafeBalance(0 , $amount , $doc -> safe_id );

                return redirect()->route('advances') -> with('success', __('main.posted'));

            }
        } else {
            return redirect()->route('advances') -> with('warning', 'عفوا لا يمكن تعديل سلفة مسددة');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advance  $advance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = Advance::find($id);
        if($doc != null){
            if($doc->paid_back == 0){
                $oldAmount = -1 * $doc -> amount;
                $doc -> delete();
                $this -> updateSafeBalance(0 , $oldAmount , $doc -> safe_id );

            } else {
                return redirect()->route('advances') -> with('warning', 'عفوا لا يمكن حذف سلفة مسددة');

            }
        }
    }
}
