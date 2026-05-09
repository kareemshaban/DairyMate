<?php

namespace App\Http\Controllers;

use App\Models\CarMeal;
use App\Models\CarMember;
use App\Models\Cars;
use App\Models\Client;
use App\Models\DailyMilkMeal;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CarMealController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($supplier_id , $startDate )
    {

        $supplier = Client::find($supplier_id);


        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];

        $startDate = Carbon::parse($startDate)->toDateString();
        $meal = DB::table('weakly_milk_meals')
            -> select(
                'weakly_milk_meals.id',
                'weakly_milk_meals.state',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            ) -> whereDate('start_date', $startDate)->first();



        if($meal){
            $meal->from_day_name_ar = $dayTranslations[$meal->from_day_name_en] ?? $meal->from_day_name_en;
            $meal->to_day_name_ar = $dayTranslations[$meal->to_day_name_en] ?? $meal->to_day_name_en;
        }


        $date1 = Carbon::parse($startDate);
        $day   = $date1->day;   // 12
        $month = $date1->month; // 9
        $year  = $date1->year;  // 2025

        $date = $day . '-' . $month . '-' . $year;

        $carbonDate = Carbon::createFromFormat('d-m-Y', $date);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"



        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');


        $members = Client::where('car_supplier_id', $supplier_id)
            ->orderBy('sort', 'asc')
            ->get();



        $setting = Settings::all() -> first();

        foreach ($members as $member){
            $member ->buffalo_price =  $setting -> buffalo_milk_price ?? 0;
            $member -> bovine_price = $setting -> bovine_milk_price ?? 0;
        }

        //  return $members ;
        $setting = Settings::all() -> first();

        $standars = DailyMilkMeal::where('weakly_meal_id', $meal->id)
            ->where('supplier_id', $supplier_id)
            ->select(
                'supplier_id',
                'date',
                DB::raw("
            SUM(CASE WHEN type = 0 THEN bovine_weight ELSE 0 END)
            as sum_of_m_bovine_weight
        "),
                DB::raw("
            SUM(CASE WHEN type = 1 THEN bovine_weight ELSE 0 END)
            as sum_of_e_bovine_weight
        "),
                DB::raw('SUM(bovine_price * bovine_weight) as sum_of_total_price')
            )
            ->groupBy('supplier_id', 'date')
            ->get();


        return view('admin.milkMeals.carDaily', compact('meal' , 'dayName' , 'dayName_ar' ,
            'startOfWeek' , 'endOfWeek' , 'end_dayName_ar' , 'end_dayName' , 'members' , 'setting' , 'supplier' , 'standars'));

    }

    public function getWeakMealsForCars($month , $year , $day)
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
            -> where('weakly_milk_meals.state' , '=' , '0')
            -> get() ;

        echo json_encode($meals);
        exit();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($wid)
    {
        $carMeal = CarMeal::where('weakly_meal_id', $wid)->first();
        return view('admin.CarsMeals.daily' , compact('carMeal', 'wid'));
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
     * @param  \App\Models\CarMeal  $carMeal
     * @return \Illuminate\Http\Response
     */
    public function show(CarMeal $carMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarMeal  $carMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(CarMeal $carMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarMeal  $carMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarMeal $carMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarMeal  $carMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarMeal $carMeal)
    {
        //
    }
}
