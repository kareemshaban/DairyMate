<?php

namespace App\Http\Controllers;

use App\Models\CarDailyMeal;
use App\Models\Cars;
use App\Models\Client;
use App\Models\DailyMilkMeal;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CarDailyMealController extends Controller
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
        $meals = DB::table('car_daily_meals')
            ->join('weakly_milk_meals', 'car_daily_meals.weakly_meal_id', '=', 'weakly_milk_meals.id')
            ->join('clients', 'car_daily_meals.supplier_id', '=', 'clients.id')
            ->join('cars', 'car_daily_meals.car_id', '=', 'cars.id')
            ->select(
                'car_daily_meals.*',
                'cars.code as car_code',
                'cars.car_number as car_number',
                'cars.driver_name as driver_name',
                'clients.name as client_name',
                'weakly_milk_meals.code as weakly_meal_code',
                'weakly_milk_meals.start_date as start_date',
                'weakly_milk_meals.end_date as end_date'
            )
            ->orderBy('car_daily_meals.id', 'asc') // or 'desc' for descending
            ->get();
        $suppliers = Client::where('type' , '<>' , 0) -> get();
        $weaklyMeals = WeaklyMilkMeal::where('state' , '=' , 0) -> get();
        $cars = Cars::all();

        return view('admin.CarsMeals.index', compact('meals' , 'suppliers' , 'weaklyMeals' , 'cars'));
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
                    'supplier_id' => 'required',
                    'car_id' => 'required',
                    'type' => 'required',
                    'weakly_meal_id' => 'required',
                    'code' => 'required|unique:car_daily_meals,code',
                    'buffalo_weight' => 'required_without:bovine_weight',
                    'bovine_weight' => 'required_without:buffalo_weight',
                ]
                , [
                    'supplier_id.required' => __('main.supplier_required'),
                    'car_id.required' => __('main.car_required'),
                    'type.required' => __('main.daily_meal_type_required'),
                    'weakly_meal_id.required' => __('main.weakly_meal_required'),
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'buffalo_weight.required_without' => __('main.one_milk_weight_required'),
                    'bovine_weight.required_without'   => __('main.one_milk_weight_required'),
                ]
            );
            $wMeal = WeaklyMilkMeal::find($request -> weakly_meal_id);
            if($wMeal -> state == 0){
                CarDailyMeal::create([
                    'code' => $request -> code,
                    'car_id' => $request -> car_id ,
                    'weakly_meal_id' => $request -> weakly_meal_id,
                    'type' => $request -> type,
                    'date' => Carbon::parse($request -> date),
                    'supplier_id' => $request -> supplier_id,
                    'buffalo_weight' => $request -> buffalo_weight ,
                    'bovine_weight' => $request -> bovine_weight,
                    'notes' => $request -> notes ?? "",
                    'buffalo_weight_difference' => 0 ,
                    'bovine_weight_difference' => 0 ,
                    'state' => 0 ,
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0
                ]) ;
               // $this -> updateWeaklyMilkMealWeight($request -> buffalo_weight  , $request -> bovine_weight , $request -> weakly_meal_id);
               // return redirect() -> route('car_meals') -> with('success', __('main.saved'));
            }  else {
               // return redirect() -> route('car_meals') -> with('warning', __('main.can_not_add_to_posted_meal'));
            }

        } else {
            $wMeal = WeaklyMilkMeal::find($request -> weakly_meal_id_hidden);
            if($wMeal -> state == 0){
                return $this -> update($request);
            } else {
             //   return redirect() -> route('car_meals') -> with('warning', __('main.can_not_edit_posted_meal'));
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarDailyMeal  $carDailyMeal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meal = DB::table('car_daily_meals') ->
        join('weakly_milk_meals', 'car_daily_meals.weakly_meal_id',
            '=', 'weakly_milk_meals.id') ->
        where('car_daily_meals.id', '=', $id)
            -> select('car_daily_meals.*' , 'weakly_milk_meals.code as weak_meal')
            -> get() -> first();

        echo json_encode($meal);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarDailyMeal  $carDailyMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(CarDailyMeal $carDailyMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarDailyMeal  $carDailyMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'supplier_id' => [
                'required'
            ],
            'type' => [
                'required'
            ],
            'weakly_meal_id' => [
                'required'
            ],
            'code' => [
                'required',
                Rule::unique('daily_milk_meals', 'code')->ignore($request -> id),
            ],
            'buffalo_weight' => [
                'required_without:bovine_weight'
            ],
            'bovine_weight' => [
                'required_without:buffalo_weight'
            ],
        ], [
            'supplier_id.required' => __('main.supplier_required'),
            'type.required' => __('main.daily_meal_type_required'),
            'weakly_meal_id.required' => __('main.weakly_meal_required'),
            'code.required' => __('main.code_required'),
            'code.unique'   => __('main.code_unique'),
            'buffalo_weight.required_without' => __('main.one_milk_weight_required'),
            'bovine_weight.required_without'   => __('main.one_milk_weight_required'),
        ]);

        $meal = CarDailyMeal::find($request -> id);
        if($meal){
//            $old_buffalo_weight = $meal -> buffalo_weight ;
//            $old_bovine_weight = $meal -> bovine_weight ;
            if($meal -> state == 0) {
                $meal -> update([
                    'code' => $request -> code,
                    'weakly_meal_id' => $request -> weakly_meal_id,
                    'type' => $request -> type,
                    'date' => Carbon::parse($request -> date),
                    'supplier_id' => $request -> supplier_id,
                    'buffalo_weight' => $request -> buffalo_weight ,
                    'bovine_weight' => $request -> bovine_weight,
                    'notes' => $request -> notes ?? "",
                    'user_upd' => Auth::user() -> id
                ]);
              //  return redirect() -> route('car_meals') -> with('success', __('main.updated'));
            } else {
             //   return redirect() -> route('car_meals') -> with('warning', __('main.can_not_edit_posted_meal'));
            }


//            $buffalo_weight = $request -> buffalo_weight - $old_buffalo_weight ;
//            $bovine_weight = $request -> bovine_weight - $old_bovine_weight ;
//
//
//            $this -> updateWeaklyMilkMealWeight($buffalo_weight , $bovine_weight , $meal -> weakly_meal_id);



        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarDailyMeal  $carDailyMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meal = CarDailyMeal::find($id);
        if($meal){
            if($meal -> state == 0){
                $meal -> delete();
               // return redirect() -> route('car_meals') -> with('success' , __('main.deleted'));
            } else {
              //  return redirect() -> route('car_meals') -> with('warning' , __('main.can_not_delete'));
            }
        }

    }

    public function getCode($id)
    {
        $meals = CarDailyMeal::where('weakly_meal_id' , '=' , $id) -> get();
        $dId = 0;
        if (count($meals) > 0) {
            $dId = count($meals)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code = $id . '-' . $padded;
        echo json_encode($code);
        exit();

    }

    public function check()
    {
        $meals = DB::table('car_daily_meals')
            ->join('weakly_milk_meals', 'car_daily_meals.weakly_meal_id', '=', 'weakly_milk_meals.id')
            ->join('clients', 'car_daily_meals.supplier_id', '=', 'clients.id')
            ->join('cars', 'car_daily_meals.car_id', '=', 'cars.id')
            ->select(
                'car_daily_meals.*',
                'cars.code as car_code',
                'cars.car_number as car_number',
                'cars.driver_name as driver_name',
                'clients.name as client_name',
                'weakly_milk_meals.code as weakly_meal_code',
                'weakly_milk_meals.start_date as start_date',
                'weakly_milk_meals.end_date as end_date'
            )
            -> where('car_daily_meals.state' , '=' , 0)
            ->orderBy('car_daily_meals.id', 'asc') // or 'desc' for descending
            ->get();
        $suppliers = Client::where('type' , '<>' , 0) -> get();
        $weaklyMeals = WeaklyMilkMeal::where('state' , '=' , 0) -> get();
        $cars = Cars::all();

        return view('admin.CarsMeals.check', compact('meals' , 'suppliers' , 'weaklyMeals' , 'cars'));
    }

    public function carryingOver(Request $request){
        $carMeal = CarDailyMeal::find( $request -> id);
        $dailyMeal = new DailyMilkMealController();
        $c  =  $dailyMeal -> getCode(request() , $carMeal -> weakly_meal_id);
        if($carMeal -> type == 0){
            $code = 'DMM' . $c ;
        } else {
            $code = 'DME' . $c ;
        }


       $id = DailyMilkMeal::create([
            'code' => $code,
            'weakly_meal_id' => $carMeal -> weakly_meal_id,
            'type' => $carMeal -> type,
            'date' => Carbon::parse($carMeal -> date),
            'supplier_id' => $carMeal -> supplier_id,
            'buffalo_weight' => $request -> actual_buffalo_weight ,
            'bovine_weight' => $request -> actual_bovine_weight,
            'hasBonus' =>  0,
            'notes' => $request -> notes ?? "",
            'car_meal_id' => $carMeal -> id ,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;

         if($id > 0){
             $dailyMeal -> updateWeaklyMilkMealWeight($request -> actual_buffalo_weight , $request -> actual_bovine_weight , $carMeal -> weakly_meal_id);
             $carMeal -> update([
                 'buffalo_weight_difference' => $carMeal -> buffalo_weight - $request -> actual_buffalo_weight,
                 'bovine_weight_difference' => $carMeal -> bovine_weight - $request -> actual_bovine_weight,
                 'state' => 1
             ]);
         }

        return redirect() -> route('car_meals-check') -> with('success', __('main.saved'));


    }
}
