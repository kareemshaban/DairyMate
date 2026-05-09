<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
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

        return view('admin.Attend.index');
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
        $employeeId = $request->employee_id;
        $date = $request->date;
        $type = $request->type;
        $present = $request->present;

        // Try to get or create a new attendance record
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employeeId,
                'date' => $date,
            ],
            [
                'state' => 0 // <-- default state when inserting
            ]
        );

        // Only update if state == 0
        if ($attendance->state == 0) {
            if ($type == 0) {
                $attendance->morning_present = $present;
            } else {
                $attendance->evening_present = $present;
            }

            $attendance->save();

            return response()->json(['success' => true, 'message' => 'Updated successfully']);
        }

        // Block update if state != 0
        return response()->json(['success' => false, 'message' => 'Update not allowed. State must be 0']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show($start_date , $end_date)
    {
        $startOfWeek = Carbon::parse($start_date)->startOfDay();
        $endOfWeek = Carbon::parse($end_date)->endOfDay();

        $employees = DB::table('employees')->get();

        $docs = DB::table('attendances')
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->select(
                'attendances.*',
                'employees.name as employee_name',
                'employees.id as employee_id'
            )
            ->whereBetween('attendances.date', [$startOfWeek, $endOfWeek])
            ->get();

// Normalize attendance dates (remove time part)
        $docs->transform(function ($att) {
            $att->date = Carbon::parse($att->date)->toDateString();
            return $att;
        });

        $result = collect();

        foreach ($employees as $employee) {
            $current = $startOfWeek->copy();
            $attends = collect();

            while ($current->lte($endOfWeek)) {
                $dateString = $current->toDateString();

                // Match by date and employee
                $attendance = $docs->first(function ($att) use ($employee, $dateString) {
                    return $att->employee_id == $employee->id && $att->date == $dateString;
                });

                $attends->push([
                    'day_name' => $current->translatedFormat('l'),
                    'date' => $dateString,
                    'morning_present' => $attendance ? $attendance->morning_present : 0,
                    'evening_present' => $attendance ? $attendance->evening_present : 0,
                    'state' => $attendance ? $attendance->state : 0,
                ]);

                $current->addDay();
            }

            $result->push([
                'employee_name' => $employee->name,
                'employee_id' => $employee->id,
                'attends' => $attends,
            ]);
        }

       // return $attends ;

        $weekState = $docs->contains(function($att) {
            return $att->state == 1;
        }) ? 1 : 0;
        return view('admin.Attend.create' , compact('result' , 'startOfWeek' , 'endOfWeek' , 'weekState'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function     postData(Request $request){

        $startOfWeek = Carbon::parse($request->start_date)->startOfDay();
        $endOfWeek = Carbon::parse($request->end_date)->endOfDay();

        DB::table('attendances')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->update(['state' => 1]);

        return response()->json(['success' => true, 'message' => 'Updated successfully']);


    }
}
