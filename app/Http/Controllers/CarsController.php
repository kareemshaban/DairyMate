<?php

namespace App\Http\Controllers;

use App\Models\CarDailyMeal;
use App\Models\Cars;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CarsController extends Controller
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
        $cars = Cars::all();
        return view('admin.Cars.index', compact('cars'));

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
                    'code' => 'required|unique:cars,code',
                    'car_number' => 'required|unique:cars,car_number',
                ]
                , [
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'car_number.required' => __('main.car_number_required'),
                    'car_number.unique'   => __('main.car_number_unique'),
                ]
            );
            Cars::create([
                'code' => $request -> code,
                'car_number' => $request -> car_number,
                'driver_name' => $request -> driver_name ?? "",
                'phone' => $request -> phone ?? "",
                'notes' => $request -> notes ?? "",
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);

            return  redirect() ->route('cars') -> with('success', __('main.saved'));
        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = Cars::find($id);
        echo json_encode($car);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function edit(Cars $cars)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'car_number' => [
                'required',
                Rule::unique('cars', 'car_number')->ignore($request -> id),
            ],
            'code' => [
                'required',
                Rule::unique('cars', 'code')->ignore($request -> id),
            ],
        ], [
            'type.required' => __('main.type_required'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);

        $car = Cars::find($request -> id);
        if($car){
            $car -> update([
                'code' => $request -> code,
                'car_number' => $request -> car_number,
                'driver_name' => $request -> driver_name ?? "",
                'phone' => $request -> phone ?? "",
                'notes' => $request -> notes ?? "",
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
            return  redirect() ->route('cars') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Cars::find($id);
        if($car){
            $suppliers = Client::where('car_id' , '=' , $id) -> get();

            if(count($suppliers) == 0){
                $car -> delete();
                return  redirect() ->route('cars') -> with('success', __('main.delete'));
            } else {
                return  redirect() ->route('cars') -> with('warning', __('main.can_not_delete'));
            }

        }
    }
}
