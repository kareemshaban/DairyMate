<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Cars;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
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
        $employees = employee::all();
        return view('admin.employee.index', compact('employees'));
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
                    'name' => 'required|unique:employees,name',
                    'weekly_salary' => 'required',
                ]
                , [
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                    'weekly_salary.required' => __('main.daily_salary_required'),
                ]
            );
            Employee::create([
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'address' => $request -> address ?? "",
                'weekly_salary' => $request -> weekly_salary ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);

            return  redirect() ->route('employees') -> with('success', __('main.saved'));
        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        echo json_encode($employee);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('employees', 'name')->ignore($request -> id),
            ],
            'weekly_salary' => 'required',
        ], [
            'name.required' => __('main.name_required'),
            'weekly_salary.required' => __('main.daily_salary_required'),
            'name.unique'   => __('main.name_unique'),
        ]);

        $employee = Employee::find($request -> id);
        if($employee){
            $employee -> update([
                'name' => $request -> name,
                'phone' => $request -> phone ?? "",
                'address' => $request -> address ?? "",
                'weekly_salary' => $request -> weekly_salary ,
                'user_upd' => Auth::user() -> id,
            ]);
            return  redirect() ->route('employees') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        if($employee){
            $attendences = Attendance::where('employee_id' , '=' , $id) -> get();
            if(count($attendences) == 0){
                $employee->delete();
                return  redirect() ->route('employees') -> with('success', __('main.delete'));

            } else {
                return  redirect() ->route('employees') -> with('warning', __('main.can_not_delete'));

            }
        }
    }
}
