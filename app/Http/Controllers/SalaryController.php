<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Safe;
use App\Models\SafeBalance;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
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
        $startOfWeek = Carbon::parse($request->start_date)->startOfDay();
        $endOfWeek = Carbon::parse($request->end_date)->endOfDay();

        $docs = DB::table('attendances')
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->where('attendances.state', 1)
            ->whereBetween('attendances.date', [$startOfWeek, $endOfWeek])
            ->select(
                'employees.id as employee_id',
                'employees.name as employee_name',
                'employees.weekly_salary as weekly_salary',
                DB::raw('SUM(CASE WHEN morning_present = 1 THEN 1 ELSE 0 END) as total_morning'),
                DB::raw('SUM(CASE WHEN evening_present = 1 THEN 1 ELSE 0 END) as total_evening')
            )
            ->groupBy('employees.id', 'employees.name', 'employees.weekly_salary')
            ->get();


        //advances

        // Get advances totals per employee
        $advances = DB::table('advances')
            ->whereBetween('advances.date', [$startOfWeek, $endOfWeek])
            ->select('employee_id', DB::raw('SUM(amount) as total_advance'))
            ->groupBy('employee_id')
            ->pluck('total_advance', 'employee_id'); // key = employee_id, value = total_advance

// Merge advances into docs
        $docs->transform(function ($item) use ($advances) {
            $item->advances = $advances[$item->employee_id] ?? 0; // default 0 if no advance found
            return $item;
        });

        $safe = Safe::where('isDefault' , '=' , 1) -> first();
        foreach ($docs as $doc) {
            $totalShifts = $doc  -> total_morning + $doc -> total_evening;
            $dayCount = $totalShifts / 2;
            $dailySalary = $doc -> weekly_salary;
            $salary = round(($dailySalary / 7) * $dayCount);
            $advance_val = $doc -> advances;
            $net = $salary - $advance_val;

            if($net > 0){
                    Salary::create([
                    'employee_id' => $doc->employee_id,
                    'week_start' => Carbon::parse($startOfWeek),
                    'week_end' => Carbon::parse($endOfWeek),
                    'total_amount' => $net,
                    'safe_id' => $safe->id,
                    'isPaid' => 1,
                    'user_ins' => Auth::user()->id,
                    'user_upd' => 0
                ]);
            }
            elseif($net < 0) {

                Advance::create([
                    'date' => Carbon::now(),
                    'employee_id' => $doc -> employee_id,
                    'amount' =>  -1 * $net,
                    'safe_id' => $safe->id,
                    'description' => "عند صرف راتب الأسبوع كانت القيمة المستحقة سالبة لذا تم إنشاء سلفة بها ",
                    'paid_back' => 0 ,
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0,
                ]);
            }

            DB::table('advances')
                ->where('employee_id', $doc->employee_id)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->update(['paid_back' => 1]);

            $this -> updateSafeAccount($net > 0 ? $net : -1 * $net , $safe->id);



        }

        return response()->json(true);


    }

     public function updateSafeAccount($val , $id)
     {
         $safeAccount = SafeBalance::where('safe_id' , '=' , $id) -> first();
         if($safeAccount){
             $safeAccount -> update([
                 'outcome' => $safeAccount -> outcome + $val,
                 'balance' => $safeAccount -> balance - $val,
             ]);
         }

     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      try {
          $startOfWeek = Carbon::createFromFormat(
              'Y-m-d',
              substr($request->start_date, 0, 10)
          )->startOfDay();

          $endOfWeek = Carbon::createFromFormat(
              'Y-m-d',
              substr($request->end_date, 0, 10)
          )->endOfDay();

          //echo $startOfWeek ;

          $docs = DB::table('attendances')
              ->join('employees', 'employees.id', '=', 'attendances.employee_id')
              ->where('attendances.state', 1)
              ->whereBetween('attendances.date', [$startOfWeek, $endOfWeek])
              ->select(
                  'employees.id as employee_id',
                  'employees.name as employee_name',
                  'employees.weekly_salary as weekly_salary',
                  DB::raw('SUM(CASE WHEN morning_present = 1 THEN 1 ELSE 0 END) as total_morning'),
                  DB::raw('SUM(CASE WHEN evening_present = 1 THEN 1 ELSE 0 END) as total_evening')
              )
              ->groupBy('employees.id', 'employees.name', 'employees.weekly_salary')
              ->get();


          //advances

          // Get advances totals per employee
          $advances = DB::table('advances')
              ->whereBetween('advances.date', [$startOfWeek, $endOfWeek])
              ->select('employee_id', DB::raw('SUM(amount) as total_advance'))
              ->groupBy('employee_id')
              ->pluck('total_advance', 'employee_id'); // key = employee_id, value = total_advance

// Merge advances into docs
          $docs->transform(function ($item) use ($advances) {
              $item->advances = $advances[$item->employee_id] ?? 0; // default 0 if no advance found
              return $item;
          });


          return response()->json($docs);
      }catch (\Throwable $e) {

          // يظهرلك الخطأ في Network tab
          return response()->json([
              'error' => true,
              'message' => $e->getMessage(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
          ], 500);
      }



    }

    public function show2($start , $end)
    {
        $startOfWeek = Carbon::parse($start)->startOfDay();
        $endOfWeek = Carbon::parse($end)->endOfDay();

        $isSalaryCreated = Salary::where('week_start', $startOfWeek)
            ->where('week_end', $endOfWeek)
            ->exists();
        return view('admin.Salary.create' , compact( 'startOfWeek' , 'endOfWeek' , 'isSalaryCreated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
