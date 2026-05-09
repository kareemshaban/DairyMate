<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DailyMilkMeal;
use App\Models\Settings;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DailyMilkMealController extends Controller
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
        $meals = DB::table('daily_milk_meals')
            ->join('weakly_milk_meals', 'daily_milk_meals.weakly_meal_id', '=', 'weakly_milk_meals.id')
            ->join('clients', 'daily_milk_meals.supplier_id', '=', 'clients.id')
            ->select(
                'daily_milk_meals.*',
                'clients.name as client_name',
                'weakly_milk_meals.code as weakly_meal_code',
                'weakly_milk_meals.start_date as start_date',
                'weakly_milk_meals.end_date as end_date'
            )
            ->orderBy('daily_milk_meals.id', 'asc') // or 'desc' for descending
            ->get();
        $suppliers = Client::where('type' , '<>' , 0) -> get();
        $weaklyMeals = WeaklyMilkMeal::where('state' , '=' , 0) -> get();

        return view('admin.DailyMeals.index', compact('meals' , 'suppliers' , 'weaklyMeals'));

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
                    'type' => 'required',
                    'weakly_meal_id' => 'required',
                    'code' => 'required|unique:daily_milk_meals,code',
                    'buffalo_weight' => 'required_without:bovine_weight',
                    'bovine_weight' => 'required_without:buffalo_weight',
                ]
                , [
                    'supplier_id.required' => __('main.supplier_required'),
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
               $id = DailyMilkMeal::create([
                    'code' => $request -> code,
                    'weakly_meal_id' => $request -> weakly_meal_id,
                    'type' => $request -> type,
                    'date' => Carbon::parse($request -> date),
                    'supplier_id' => $request -> supplier_id,
                    'buffalo_weight' => $request -> buffalo_weight ,
                    'bovine_weight' => $request -> bovine_weight,
                    'hasBonus' => $request -> hasBonus ?? 0,
                    'notes' => $request -> notes ?? "",
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0
                ]) -> id ;
               if($id > 0){
                   $this -> updateWeaklyMilkMealWeight($request -> buffalo_weight  , $request -> bovine_weight , $request -> weakly_meal_id);

               }
                return redirect() -> route('daily_meals') -> with('success', __('main.saved'));
            }  else {
                return redirect() -> route('daily_meals') -> with('warning', __('main.can_not_add_to_posted_meal'));
            }




        } else {
            $wMeal = WeaklyMilkMeal::find($request -> weakly_meal_id_hidden);
            if($wMeal -> state == 0){
                return $this -> update($request);
            } else {
                return redirect() -> route('daily_meals') -> with('warning', __('main.can_not_edit_posted_meal'));
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyMilkMeal  $dailyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meal = DB::table('daily_milk_meals') ->
        join('weakly_milk_meals', 'daily_milk_meals.weakly_meal_id',
            '=', 'weakly_milk_meals.id') ->
        where('daily_milk_meals.id', '=', $id)
            -> select('daily_milk_meals.*' , 'weakly_milk_meals.code as weak_meal')
            -> get() -> first();

        $wMeal = WeaklyMilkMeal::find( $meal -> weakly_meal_id) ;
        $meal -> buffalo_price = $wMeal -> price_buffalo ;
        $meal -> bovine_price = $wMeal -> price_bovine ;
        $meal -> state = $wMeal -> state ;
        echo json_encode($meal);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyMilkMeal  $dailyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyMilkMeal $dailyMilkMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyMilkMeal  $dailyMilkMeal
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

        $meal = DailyMilkMeal::find($request -> id);
        if($meal){
            $old_buffalo_weight = $meal -> buffalo_weight ;
            $old_bovine_weight = $meal -> bovine_weight ;
            $meal -> update([
                'code' => $request -> code,
                'weakly_meal_id' => $request -> weakly_meal_id,
                'type' => $request -> type,
                'date' => Carbon::parse($request -> date),
                'supplier_id' => $request -> supplier_id,
                'buffalo_weight' => $request -> buffalo_weight ,
                'bovine_weight' => $request -> bovine_weight,
                'hasBonus' => $request -> hasBonus ?? 0,
                'notes' => $request -> notes ?? "",
                'user_upd' => Auth::user() -> id
            ]);

            $buffalo_weight = $request -> buffalo_weight - $old_buffalo_weight ;
            $bovine_weight = $request -> bovine_weight - $old_bovine_weight ;


            $this -> updateWeaklyMilkMealWeight($buffalo_weight , $bovine_weight , $meal -> weakly_meal_id);


            return redirect() -> route('daily_meals') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyMilkMeal  $dailyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dailyMeal = DailyMilkMeal::find($id);
        $wMeal = WeaklyMilkMeal::find( $dailyMeal -> weakly_meal_id );
        if($wMeal -> state == 0){
            if($dailyMeal){
                $old_buffalo_weight = $dailyMeal -> buffalo_weight ;
                $old_bovine_weight = $dailyMeal -> bovine_weight ;
                $buffalo_weight =  -1 * $old_buffalo_weight ;
                $bovine_weight =  -1 * $old_bovine_weight ;

                $this -> updateWeaklyMilkMealWeight($buffalo_weight , $bovine_weight , $dailyMeal -> weakly_meal_id);

                $dailyMeal -> delete();




                return redirect() -> route('daily_meals') -> with('success', __('main.deleted'));
            }
        } else {
            return redirect() -> route('daily_meals') -> with('warning', __('main.can_not_delete_posted_meal'));

        }

    }

    public function getCode(Request $request , $id)
    {
        $meals = DailyMilkMeal::where('weakly_meal_id' , '=' , $id) -> get();
        $dId = 0;
        if (count($meals) > 0) {
            $dId = count($meals)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code = $id . '-' . $padded;
        if ($request->ajax()) {
            echo json_encode($code);
            exit();
        } else {
            return $code ;
        }


    }

    public function bounsCheck($type , $date)
    {
        $setting = Settings::all() -> first();
        if($setting){
            //morning
            if($type == 0){
                $time = Carbon::parse($setting->morning_bonus_time)->format('H:i');
                $bonusTime = Carbon::createFromFormat('H:i', $time);

                $time2 = Carbon::parse($date)->format('H:i');
                $currentTime = Carbon::createFromFormat('H:i', $time2);



                if ($currentTime <= $bonusTime) {
                   echo json_encode("1");
                   exit();
                } else {
                    echo json_encode("0");
                    exit();
                }
            }
            //evening
            else {
                $time = Carbon::parse($setting->evening_bonus_time)->format('H:i');
                $bonusTime = Carbon::createFromFormat('H:i', $time);

                $time2 = Carbon::parse($date)->format('H:i');
                $currentTime = Carbon::createFromFormat('H:i', $time2);

                if ($currentTime <= $bonusTime) {
                    echo json_encode("1");
                    exit();
                } else {
                    echo json_encode("0");
                    exit();
                }
            }
        }
    }

    public function updateWeaklyMilkMealWeight($buffalo_weight , $bovine_weight , $weakly_meal_id){
        $meal = WeaklyMilkMeal::find($weakly_meal_id) ;
        if($meal){
            $meal -> update([
                'total_buffalo_weight' => $meal -> total_buffalo_weight + $buffalo_weight,
                'total_bovine_weight' => $meal -> total_bovine_weight + $bovine_weight,
            ]);

        }
    }

    public function getMealByCode($code)
    {


        $meal = DB::table('daily_milk_meals') ->
        join('weakly_milk_meals', 'daily_milk_meals.weakly_meal_id',
            '=', 'weakly_milk_meals.id') ->
        where('daily_milk_meals.code', '=', $code)
            -> select('daily_milk_meals.*' , 'weakly_milk_meals.code as weak_meal')
            -> get() -> first();


        $wMeal = WeaklyMilkMeal::find( $meal -> weakly_meal_id) ;
        $meal -> buffalo_price = $wMeal -> price_buffalo ;
        $meal -> bovine_price = $wMeal -> price_bovine ;
        $meal -> state = $wMeal -> state ;
        echo json_encode($meal);
        exit();
    }

    public function dailyMealTotalMilk($id)
    {
        $meal = DailyMilkMeal::find($id);
       if($meal != null){
           $sum = DB::table('daily_milk_meals')
               ->whereDate('date', '=', Carbon::parse($meal -> date) )
               ->where('type', '=', $meal -> type)
               ->where('weakly_meal_id', '=', $meal -> weakly_meal_id)
               ->where('isManufactured', '=', 0)
               ->selectRaw(' SUM(buffalo_weight) as total_buffalo_weight, SUM(bovine_weight) as
               total_bovine_weight') ->first();

         echo  json_encode($sum) ;
         exit();
       }


    }
}
