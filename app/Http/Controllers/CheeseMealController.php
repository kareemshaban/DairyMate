<?php

namespace App\Http\Controllers;

use App\Models\CheeseMeal;
use App\Models\CheeseMilkMeals;
use App\Models\Client;
use App\Models\DailyMilkMeal;
use App\Models\Items;
use App\Models\Settings;
use App\Models\StockTransactionIn;
use App\Models\StockTransactionInDetails;
use App\Models\Store;
use App\Models\StoreQuantity;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CheeseMealController extends Controller
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
        if (!Gate::allows('page-access', [10, 'edit'])) {
            abort(403);
        }

        $weaklyMeals = WeaklyMilkMeal::where('state' , '=' , 0) -> get ();
        $suppliers = Client::where('type' , '<>' , 0) -> get();

        if($isAll == 1 ){
            $meals = DB::table('cheese_meals')
                -> join('items', 'cheese_meals.item_id', '=', 'items.id')
                -> select('cheese_meals.*', 'items.name as item') -> get();

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
            $meals = DB::table('cheese_meals')
                ->join('items', 'cheese_meals.item_id', '=', 'items.id')
                ->select('cheese_meals.*', 'items.name as item')
                ->whereDate('cheese_meals.date', '>=', $startOfWeek->toDateString())
                ->whereDate('cheese_meals.date', '<=', $endOfWeek->toDateString())
                ->get();
        }

        return view('admin.CheeseMeals.index', compact('meals' , 'weaklyMeals' , 'suppliers' , 'is_all'));
    }

    public function index2()
    {
        if (!Gate::allows('page-access', [10, 'edit'])) {
            abort(403);
        }

        $meals = DB::table('cheese_meals')
            -> join('items', 'cheese_meals.item_id', '=', 'items.id')
            -> select('cheese_meals.*', 'items.name as item')
            -> where('cheese_meals.state' , '=' , 1)
            -> get();

        $weaklyMeals = WeaklyMilkMeal::where('state' , '=' , 0) -> get ();
        $suppliers = Client::where('type' , '<>' , 0) -> get();

        return view('admin.CheeseMeals.posted', compact('meals' , 'weaklyMeals' , 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        if (!Gate::allows('page-access', [10, 'edit'])) {
            abort(403);
        }

        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];

//        $dailyMeals = DB::table('daily_milk_meals')
//            ->select(
//                DB::raw('DAYNAME(daily_milk_meals.date) as day_name_en'),
//                DB::raw('DATE(daily_milk_meals.date) as date'),
//                'daily_milk_meals.type',
//                DB::raw('MIN(daily_milk_meals.id) as id'),
//            )
//            ->where('daily_milk_meals.isManufactured', '=', 0)
//            ->groupBy(
//                DB::raw('DAYNAME(daily_milk_meals.date)'),
//                DB::raw('DATE(daily_milk_meals.date)'),
//                'daily_milk_meals.type',
//
//            )
//            ->orderBy('date', 'asc')
//            ->get();
//
//        foreach ($dailyMeals as $meal) {
//            $meal->day_name_ar = $dayTranslations[$meal->day_name_en] ?? $meal->day_name_en;
//        }





       // return $meals ;
        $items = Items::all();

        $setting = Settings::all() -> first();
        $defaultPrice = 0 ;
        if ($setting){
            $defaultPrice = ($setting -> buffalo_milk_price + $setting  -> bovine_milk_price) / 2 ;
        }

       // return view('admin.CheeseMeals.create', compact('dailyMeals' , 'items' , 'defaultPrice'));
         return view('admin.CheeseMeals.step1', compact( 'items' , 'defaultPrice'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save a record of cheese_meal
        // then update the daily_meal to be isManufactured = 1
        // no stock update until post !


        $request->validate([
                'code' => 'required|unique:cheese_meals,code',
                'daily_milk_meal' => 'required',
                'item_id' => 'required',
                'milk_weight' => 'required',
                'quantity' => 'required',
                'weight' => 'required',
                'average_weight_per_milk_litter' => 'required',
                'average_productivity_per_cheese_disk' => 'required',
                'productivity' => 'required',
                'cost_per_cheese_kilo' => 'required',
            ]
            , [
                'code.required' => __('main.code_required'),
                'code.unique'   => __('main.code_unique'),
                'daily_milk_meal.required' => __('main.daily_meal_required'),
                'item_id.required' => __('main.item_required'),
                'milk_weight.required' => __('main.milk_weight_required'),
                'quantity.required' => __('main.quantity_required'),
                'weight.required' => __('main.weight_required'),
                'average_weight_per_milk_litter.required' => __('main.average_milk_price_required'),
                'average_productivity_per_cheese_disk.required' => __('main.average_productivity_per_cheese_disk_required'),
                'productivity.required' => __('main.productivity_required'),
                'cost_per_cheese_kilo.required' => __('main.cost_per_cheese_kilo_required'),

            ]
        );
        $code = $this -> getCode();
       $id = CheeseMeal::create([
            'code' => $code,
            'symbol' => $request -> symbol ,
            'daily_milk_meal' => $request -> daily_milk_meal,
            'item_id' => $request -> item_id,
            'milk_weight' => $request -> milk_weight,
            'quantity' => $request -> quantity,
            'weight' => $request -> weight,
            'average_weight_per_milk_litter' => $request -> average_weight_per_milk_litter,
            'average_productivity_per_cheese_disk' => $request -> average_productivity_per_cheese_disk,
            'productivity' => $request -> productivity,
            'cost_per_cheese_kilo' => $request -> cost_per_cheese_kilo,
            'notes' => $request -> notes ?? "",
            'state' => 0, // 0 new 1 posted
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]) -> id;

        if($id != null){
            $meal = DailyMilkMeal::find($request ->daily_milk_meal );

            $meals = DailyMilkMeal::whereDate('date', '=', Carbon::parse($meal -> date) )
                ->where('type', '=', $meal -> type)
                ->where('weakly_meal_id', '=', $meal -> weakly_meal_id)
                ->where('isManufactured', '=', 0)
                -> get() ;
            foreach ($meals as $dailyMeal) {
                if($dailyMeal != null){
                    $dailyMeal -> update([
                        'isManufactured' => 1
                    ]);
                }
            }

        }
        return redirect()->route('cheese-meals') -> with('success', __('main.saved'));
    }

    public function storeAjax(Request $request)
    {
       try {

//           return response()->json([
//               'status' => 'debug',
//               'request' => $request -> all()
//           ]);
           //check create or edit
           if ($request->cheese_meal_id == 0) {
               //save
               // save a record of cheese_meal
               // then update the daily_meal to be isManufactured = 1 and cheese_meal_id = $id


               $id = CheeseMeal::create([
                   'code' => $request -> code ?? $this -> getCode(),
                   'symbol' => $request -> symbol ,
                   'type' => $request -> type ,
                   'date' => Carbon::parse($request->date),
                   'white_cheese_price' => $request->white_cheese_price,
                   'cheese_price' => $request->cheese_price,
                   'cream_price' => $request->cream_price,
                   'protein_price' => $request->protein_price,
                   'milk_weight' => $request->milk_weight,
                   'bovine_price' => $request->bovine_price,
                   'item_id' => $request->item_id,
                   'quantity' => $request->quantity,
                   'weight' => $request->weight,
                   'disk_weight' => $request->disk_weight,
                   'disk_cost' => $request->disk_cost,
                   'productivity' => $request->productivity,
                   'cost_per_cheese_kilo' => $request->cost_per_cheese_kilo,
                   'cream_weight' => $request->cream_weight ?? 0,
                   'cream_of_kilo_milk' => $request->cream_of_kilo_milk ?? 0,
                   'protein_weight' => $request->protein_weight ?? 0,
                   'protein_of_kilo_milk' => $request->protein_of_kilo_milk ?? 0,
                   'net' => $request->net,
                   'notes' => "",
                   'state' => 0, // 0 new 1 posted
                   'user_ins' => Auth::user()->id,
               ])->id;

               $dailyMeals = DailyMilkMeal::where('weakly_meal_id', '=', $request->wid)
                   -> where('type' , '=' , $request -> type)
                   ->whereDate('date', '=', Carbon::parse($request->date))->get();

               foreach ($dailyMeals as $dailyMeal) {
                   $dailyMeal->update([
                       'isManufactured' => 1,
                       'cheese_meal_id' => $id
                   ]);
               }


               return response()->json([
                   'status' => 'success',
                   'message' => 'تم حفظ البيانات بنجاح',
                   'cheese_meal_id' => $id
               ]);


           } else {
               //update
              $cheeseMeal = CheeseMeal::find($request->cheese_meal_id);
              $cheeseMeal -> update([
                //  'code' => $request -> code ?? "",
                  'symbol' => $request ->symbol ?? "",
                  'type' => $request -> type,
                  'date' => Carbon::parse($request->date),
                  'white_cheese_price' => $request -> white_cheese_price ,
                  'cheese_price' => $request->cheese_price,
                  'cream_price' => $request->cream_price,
                  'protein_price' => $request->protein_price,
                  'milk_weight' => $request->milk_weight,
                  'bovine_price' => $request->bovine_price,
                  'item_id' => $request->item_id,
                  'quantity' => $request->quantity,
                  'weight' => $request->weight,
                  'disk_weight' => $request->disk_weight,
                  'disk_cost' => $request->disk_cost,
                  'productivity' => $request->productivity,
                  'cost_per_cheese_kilo' => $request->cost_per_cheese_kilo,
                  'cream_weight' => $request->cream_weight ?? 0,
                  'cream_of_kilo_milk' => $request->cream_of_kilo_milk ?? 0,
                  'protein_weight' => $request->protein_weight ?? 0,
                  'protein_of_kilo_milk' => $request->protein_of_kilo_milk ?? 0,
                  'net' => $request->net,
                  'notes' => "",
                  'state' => 0, // 0 new 1 posted
                  'user_ins' => Auth::user()->id,
              ]);
               $dailyMeals = DailyMilkMeal::where('weakly_meal_id', '=', $request->wid)
                   -> where('type' , '=' , $request -> type)
                   ->whereDate('date', '=', Carbon::parse($request->date))->get();

               foreach ($dailyMeals as $dailyMeal) {
                   $dailyMeal->update([
                       'isManufactured' => 1,
                       'cheese_meal_id' => $cheeseMeal -> id
                   ]);
               }

               return response()->json([
                   'status' => 'success',
                   'message' => 'تم تعديل البيانات بنجاح',
                   'cheese_meal_id' => $cheeseMeal -> id
               ]);

           }
       } catch (QueryException $exception){
           return response()->json([
               'status' => 'error',
               'message' => 'حدث خطأ في حفظ البيانات',
               'debug' => $exception -> getMessage()
           ]);
       }
    }


    public function cheese_meals_prices_update(Request $request)
    {
        try {

             $dailyMeals = DailyMilkMeal::where('weakly_meal_id', '=', $request->wid)
                ->where('cheese_meal_id', '<>', 0)->get();




            $net = 0;
            if(count($dailyMeals) > 0){
            foreach ($dailyMeals as $dailyMeal) {
                $cheeseMeal = CheeseMeal::find($dailyMeal->cheese_meal_id);
                if ($cheeseMeal) {
                    $price = $cheeseMeal -> item_id == 1 ? $request->cheese_price : $request->white_cheese_price ;
                    $net = ($cheeseMeal->weight *  $price) + ($cheeseMeal->cream_weight * $request->cream_price)
                        + ($cheeseMeal->protein_weight * $request->protein_price) - ($cheeseMeal->milk_weight * $cheeseMeal->bovine_price);

                    $cream_of_kilo_milk = (($cheeseMeal -> cream_weight * $request->cream_price) /$cheeseMeal->milk_weight );
                    $protein_of_kilo_milk = (($cheeseMeal -> protein_weight * $request->protein_price) /$cheeseMeal->milk_weight );


                    $cheeseMeal->update([
                        'cheese_price' => $request->cheese_price,
                        'white_cheese_price' => $request->white_cheese_price,
                        'cream_price' => $request->cream_price,
                        'protein_price' => $request->protein_price,
                        'cream_of_kilo_milk' => $cream_of_kilo_milk,
                        'protein_of_kilo_milk' => $protein_of_kilo_milk,
                        'net' => $net
                    ]);


                }

            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ البيانات بنجاح',
                'cheese_meal_id' =>  $cheeseMeal->id
            ]);
        } else {

                $s = Settings::first();
                $setting = [
                    'cheese_price' => $s -> cheese_price,
                    'cream_price' => $s -> cream_price,
                    'protein_price' => $s -> protein_price,
                    'white_cheese_price' => $s -> white_cheese_price,
                ];

              return response()->json([
                'status' => 'warning',
                'message' => 'برجاء إدخال تفاصيل الوجبات أولا قبل تعديل الأسعار',
                'settings' => $setting
               // 'cheese_meal_id' =>  $cheeseMeal->id
            ]);
        }
        } catch (QueryException $exception){
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في حفظ البيانات',
                'debug' => $exception -> getMessage()
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CheeseMeal  $cheeseMeal
     * @return \Illuminate\Http\Response
     */
    public function show(CheeseMeal $cheeseMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CheeseMeal  $cheeseMeal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $meal = CheeseMeal::find($id);


        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];
        $dailyMeals = DB::table('daily_milk_meals')
            ->select(
                DB::raw('DAYNAME(daily_milk_meals.date) as day_name_en'),
                DB::raw('DATE(daily_milk_meals.date) as date'),
                'daily_milk_meals.type',
                DB::raw('MIN(daily_milk_meals.id) as id'),
            )
            ->where('daily_milk_meals.id', '=', $meal -> daily_milk_meal)
            ->groupBy(
                DB::raw('DAYNAME(daily_milk_meals.date)'),
                DB::raw('DATE(daily_milk_meals.date)'),
                'daily_milk_meals.type',

            )
            ->orderBy('date', 'asc')
            ->get();

        foreach ($dailyMeals as $dmeal) {
            $dmeal->day_name_ar = $dayTranslations[$dmeal->day_name_en] ?? $dmeal->day_name_en;
        }

      //  return $dailyMeals ;


        $items = Items::all();
        return view('admin.CheeseMeals.edit', compact('dailyMeals' , 'items' , 'meal'));
    }

    public function view($id)
    {
        $meal = CheeseMeal::find($id);
        $dailyMeals = DB::table('daily_milk_meals')
            -> join('clients', 'daily_milk_meals.supplier_id', '=', 'clients.id')
            ->select('daily_milk_meals.*', 'clients.name as client_name')
            ->where('daily_milk_meals.id' , '=' , $meal -> daily_milk_meal)
            -> get();
        $items = Items::all();
        return view('admin.CheeseMeals.view', compact('dailyMeals' , 'items' , 'meal'));
    }

    public function post($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CheeseMeal  $cheeseMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $meal = CheeseMeal::find($request -> id);
        if($meal != null){
            if($meal -> state == 0){
                $meal -> update([
                    'code' => $request -> code,
                    'daily_milk_meal' => $request -> daily_milk_meal,
                    'item_id' => $request -> item_id,
                    'milk_weight' => $request -> milk_weight,
                    'quantity' => $request -> quantity,
                    'weight' => $request -> weight,
                    'average_weight_per_milk_litter' => $request -> average_weight_per_milk_litter,
                    'average_productivity_per_cheese_disk' => $request -> average_productivity_per_cheese_disk,
                    'productivity' => $request -> productivity,
                    'cost_per_cheese_kilo' => $request -> cost_per_cheese_kilo,
                    'notes' => $request -> notes ?? "",
                    'state' => 0, // 0 new 1 posted
                    'user_upd' => Auth::user() -> id
                ]);
                return  redirect() ->route('cheese-meals') -> with('success', __('main.updated'));

            } else {
                return  redirect() ->route('cheese-meals') -> with('warning', __('main.can_not_edit_posted_meal'));

            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CheeseMeal  $cheeseMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meal = CheeseMeal::find($id);
        if($meal){
            if($meal -> state == 0){
                $dailyMeal = DailyMilkMeal::find($meal -> daily_milk_meal);
                if($dailyMeal){
                    $dailyMeal -> update([
                        'isManufactured' => 0
                    ]);
                    $meal->delete();
                }
                return  redirect() ->route('cheese-meals') -> with('success', __('main.delete'));
            } else {
                return  redirect() ->route('cheese-meals') -> with('warning', __('main.can_not_delete_posted_meal'));
            }



        }
    }

    public function getCode(){
        $meals = CheeseMeal::all();
        $dId = 0;
        if (count($meals) > 0) {
            $dId = count($meals)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'CM' . $padded;

        return $code ;
//        echo json_encode($code);
//        exit();
    }

    public function step1( )
    {
        return view('admin.CheeseMeals.step1');
    }

    public function getWeakMealsToFactory( $month , $year , $day)
    {
        $date = $day . '-' . $month . '-' . $year;
        $meals = DB::table('weakly_milk_meals')
            -> select(
                'weakly_milk_meals.id',
                'weakly_milk_meals.state',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),  )
            -> whereDate('start_date',  Carbon::parse($date))
            -> get() ;

        foreach($meals as $meal){
            $dmeals = DailyMilkMeal::where('weakly_meal_id' , '=' , $meal->id)
                -> where('isManufactured' , '=' , 0) -> get() ;

            // $dmeals == 0 =>   مصنعة بالكامل
            // $dmeals > 0

            if(count($dmeals) > 0){
                // all is 0 => جاهزة للتصنيع
                // some is 0 and other is 1 => جاري تصنيعها
                $allDMeals= DailyMilkMeal::where('weakly_meal_id' , '=' , $meal->id) -> get() ;
                if($dmeals ==$allDMeals ){
                    $meal -> isManufactured =   0   ;
                } else {
                    $meal -> isManufactured =   2   ;
                }

            }

            else
                $meal -> isManufactured =   1   ;

            $meal->from_day_name_ar = $dayTranslations[$meal->from_day_name_en] ?? $meal->from_day_name_en;
            $meal->to_day_name_ar = $dayTranslations[$meal->to_day_name_en] ?? $meal->to_day_name_en;
        }

        echo json_encode($meals);
        exit();
    }

    public function step2($id)
    {
        $meal = WeaklyMilkMeal::find($id);

        $items = Items::where('type' , '=' , 0) -> get();

        $dmeals = DailyMilkMeal::where('weakly_meal_id', $id)
            ->selectRaw(' DATE(date) as date,
                type,
                SUM(bovine_weight) as total_bovine_weight,
                SUM(bovine_weight * bovine_price) / NULLIF(SUM(bovine_weight), 0) as bovine_price,
                MAX(isManufactured) as isManufactured,
                MAX(cheese_meal_id) as cheese_meal_id
            ')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->orderBy('type')
            ->get();



        $obj = [] ;
        $proceccedMeals = DailyMilkMeal::where('weakly_meal_id', $id)
            -> where('isManufactured' , '=' , 1) -> get();
        if(count($proceccedMeals) > 0){
            $m0 = $proceccedMeals[0];
            $cheeseMeal0 = CheeseMeal::find($m0 -> cheese_meal_id);
            if($cheeseMeal0){
                $setting = [
                    'cheese_price' => $cheeseMeal0 -> cheese_price,
                    'cream_price' => $cheeseMeal0 -> cream_price,
                    'protein_price' => $cheeseMeal0 -> protein_price,
                    'white_cheese_price' => $cheeseMeal0 -> white_cheese_price,

                ];

            } else {
                $s = Settings::first();
                $setting = [
                    'cheese_price' => $s -> cheese_price,
                    'cream_price' => $s -> cream_price,
                    'protein_price' => $s -> protein_price,
                    'white_cheese_price' => $s -> white_cheese_price,
                ];
            }
        } else {
            $s = Settings::first();
            $setting = [
                'cheese_price' => $s -> cheese_price,
                'cream_price' => $s -> cream_price,
                'protein_price' => $s -> protein_price,
                'white_cheese_price' => $s -> white_cheese_price
            ];
        }

        $data = array();
        foreach($dmeals as $dmeal){
            if($dmeal -> isManufactured == 0){
                $obj = [
                  'code' => '' ,
                  'symbol' => '',
                  'type' => $dmeal -> type,
                  'cheese_meal_id' => 0 ,
                  'date' => $dmeal -> date,
                  'milk_weight' => $dmeal -> total_bovine_weight,
                  'bovine_price' => $dmeal -> bovine_price,
                  'item_id' => 0 ,
                  'quantity' => 0 ,
                  'weight' => 0 ,
                  'disk_weight' => 0 ,
                  'disk_cost' => 0 ,
                  'productivity' => 0 ,
                  'cost_per_cheese_kilo' => 0 ,
                  'cream_weight' => 0 ,
                  'cream_price' => 0 ,
                  'cream_of_kilo_milk' => 0 ,
                  'protein_weight' => 0 ,
                  'protein_price' => 0 ,
                  'protein_of_kilo_milk' => 0 ,
                  'net' => 0,
                  'state' => 0,
                  'daily_meal_state' => $dmeal -> state
                ];
            } else {

                $cheeseMeal = CheeseMeal::find($dmeal -> cheese_meal_id);

                if($cheeseMeal ){
                    $obj = [
                        'type' => $dmeal -> type,
                        'code' => $cheeseMeal -> code,
                        'symbol' => $cheeseMeal -> symbol,
                        'cheese_meal_id' => $cheeseMeal -> id ,
                        'date' => $dmeal -> date,
                        'milk_weight' => $dmeal -> total_bovine_weight,
                        'bovine_price' => $dmeal -> bovine_price,
                        'item_id' => $cheeseMeal -> item_id,
                        'quantity' => $cheeseMeal -> quantity ,
                        'weight' => $cheeseMeal -> weight,
                        'disk_weight' => $cheeseMeal -> disk_weight ,
                        'disk_cost' => $cheeseMeal -> disk_cost ,
                        'productivity' => $cheeseMeal -> productivity ,
                        'cost_per_cheese_kilo' => $cheeseMeal -> cost_per_cheese_kilo ,
                        'cream_weight' => $cheeseMeal -> cream_weight ,
                        'cream_price' => $cheeseMeal -> cream_price ,
                        'cream_of_kilo_milk' =>  $cheeseMeal -> cream_of_kilo_milk    ,
                        'protein_weight' => $cheeseMeal -> protein_weight ,
                        'protein_price' => $cheeseMeal -> protein_price ,
                        'protein_of_kilo_milk' => $cheeseMeal -> protein_of_kilo_milk ,
                        'net' =>  $cheeseMeal -> net,
                        'state' => $cheeseMeal -> state,
                        'daily_meal_state' => $dmeal -> state
                    ];
                }
                else {
                    $obj = [
                        'type' => $dmeal -> type,
                        'code' => '',
                        'cheese_meal_id' => 0 ,
                        'date' => $dmeal -> date,
                        'milk_weight' => $dmeal -> total_bovine_weight,
                        'bovine_price' => $dmeal -> bovine_price,
                        'item_id' => 0 ,
                        'quantity' => 0 ,
                        'weight' => 0 ,
                        'disk_weight' => 0 ,
                        'disk_cost' => 0 ,
                        'productivity' => 0 ,
                        'cost_per_cheese_kilo' => 0 ,
                        'cream_weight' => 0 ,
                        'cream_price' => 0 ,
                        'cream_of_kilo_milk' => 0 ,
                        'protein_weight' => 0 ,
                        'protein_price' => 0 ,
                        'protein_of_kilo_milk' => 0 ,
                        'net' => 0,
                        'state' => 0,
                        'daily_meal_state' => $dmeal -> state
                    ];
                }
            }
            $data[] = $obj;
        }

       // return $data ;

        return view('admin.CheeseMeals.step2' , compact('meal' , 'setting' , 'data' , 'items'));
    }


    public function cheese_meals_carry_over($meal_id)
    {
        try{
            $store = Store::where('isDefault' , '=' , 1) -> first();
            if($store){

                $docs = StockTransactionIn::all();
                $dId = 0;
                if (count($docs) > 0) {
                    $dId = count($docs)  + 1;
                } else {
                    $dId = 1;
                }

                $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
                $code =  'STI' . $padded;

                $id = StockTransactionIn::create([
                    'bill_number' => $code ,
                    'meal_id' => $meal_id,
                    'date' => Carbon::now(),
                    'store_id' => $store -> id,
                    'notes' => "",
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0
                ]) -> id;


                if($id){
                    $meal = CheeseMeal::find($meal_id);
                    $this -> createStockTransactionDetails($meal , $id);
                    $this -> updateStoreQuantity($meal , $store -> id);
                    $this -> postMeal($meal);

                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم الترحيل بنجاح',
                ]);

            } else {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'لا يمكن إتمام العملية لعدم وجود مخزن افتراضي',
                ]);
            }

        } catch (QueryException $exception){
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ في ترحيل الوجبة',
                'debug' => $exception -> getMessage()
            ]);
        }

    }


    function createStockTransactionDetails($meal , $id)
    {
        // should has 3 items
        //item details
        StockTransactionInDetails::create([
            'transaction_id' => $id,
            'item_id' => $meal -> item_id,
            'quantity' => $meal -> quantity,
            'weight' => $meal -> weight,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0
        ]);

        //cream details
        if($meal -> cream_weight > 0) {
            $cream = Items::where('type', '=', 1)->first();
            if ($cream) {
                StockTransactionInDetails::create([
                    'transaction_id' => $id,
                    'item_id' => $cream->id,
                    'quantity' => $meal->cream_weight,
                    'weight' => $meal->cream_weight,
                    'user_ins' => Auth::user()->id,
                    'user_upd' => 0
                ]);
            }
        }

        //protein details

        if($meal -> protein_weight > 0) {
            $protein = Items::where('type' , '=' , 2) -> first();
            if($protein){
                StockTransactionInDetails::create([
                    'transaction_id' => $id,
                    'item_id' => $protein -> id,
                    'quantity' => $meal -> protein_weight,
                    'weight' => $meal -> protein_weight,
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0
                ]);
            }
        }


    }

    function postMeal($meal)
    {
        $meal -> update([
            'state' => 1
        ]);
    }

    function updateStoreQuantity($meal , $store_id)
    {
        //update store for item
        $storeQuantity = StoreQuantity::where('store_id' , '=' , $store_id)
            -> where('item_id' , '=' , $meal -> item_id)
            -> where('cheese_meal_id' , '=' , $meal -> id)
            -> first();
        if($storeQuantity != null){
            $storeQuantity -> update([
                'quantity_in' => $storeQuantity -> quantity_in + $meal -> quantity,
                'balance' => $storeQuantity -> balance + $meal -> quantity
            ]);
        }
        else {
            StoreQuantity::create([
                'store_id' => $store_id,
                'item_id' => $meal -> item_id,
                'cheese_meal_id' => $meal -> id ,
                'opening_quantity' => 0,
                'quantity_in' => $meal -> quantity,
                'quantity_out' => 0,
                'balance'  => $meal -> quantity,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);
        }

        //update store for cream
        if($meal -> cream_weight > 0) {
            $cream = Items::where('type', '=', 1)->first();
            if ($cream) {
                $storeQuantityCream = StoreQuantity::where('store_id', '=', $store_id)
                    ->where('item_id', '=', $cream->id)
                    -> where('cheese_meal_id' , '=' , $meal -> id)
                    ->first();
                if ($storeQuantityCream != null) {
                    $storeQuantityCream->update([
                        'quantity_in' => $storeQuantityCream->quantity_in + $meal->cream_weight,
                        'balance' => $storeQuantityCream->balance + $meal->cream_weight
                    ]);
                } else {
                    StoreQuantity::create([
                        'store_id' => $store_id,
                        'item_id' => $cream->id,
                        'cheese_meal_id' => $meal->id,
                        'opening_quantity' => 0,
                        'quantity_in' => $meal->cream_weight,
                        'quantity_out' => 0,
                        'balance' => $meal->cream_weight,
                        'user_ins' => Auth::user()->id,
                        'user_upd' => 0,
                    ]);
                }
            }
        }


        //update store for protein
       if($meal -> protein_weight > 0) {
           $protein = Items::where('type', '=', 2)->first();
           if ($protein) {
               $storeQuantityProtein = StoreQuantity::where('store_id', '=', $store_id)
                   ->where('item_id', '=', $protein->id)
                   -> where('cheese_meal_id' , '=' , $meal -> id)
                   ->first();
               if ($storeQuantityProtein != null) {
                   $storeQuantityProtein->update([
                       'quantity_in' => $storeQuantityProtein->quantity_in + $meal->protein_weight,
                       'balance' => $storeQuantityProtein->balance + $meal->protein_weight
                   ]);
               } else {
                   StoreQuantity::create([
                       'store_id' => $store_id,
                       'item_id' => $protein->id,
                       'cheese_meal_id' => $meal->id,
                       'opening_quantity' => 0,
                       'quantity_in' => $meal->protein_weight,
                       'quantity_out' => 0,
                       'balance' => $meal->protein_weight,
                       'user_ins' => Auth::user()->id,
                       'user_upd' => 0,
                   ]);
               }
           }
       }


    }

    public function cheese_meal_code_check($id , $code)
    {
        $query = DB::table('cheese_meals')->where('code', $code);

        if ($id > 0) {
            $query->where('id', '!=', $id);
        }

        $exists = $query->exists();

        return response()->json([
            'status' => $exists ? 'warning' : 'success',
            'is_unique' => !$exists
        ]);
    }
}
