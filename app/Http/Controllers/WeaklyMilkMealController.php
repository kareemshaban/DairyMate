<?php

namespace App\Http\Controllers;

use App\Models\CarDailyMeal;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\DailyMilkMeal;
use App\Models\Safe;
use App\Models\Settings;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class WeaklyMilkMealController extends Controller
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
        $meals = WeaklyMilkMeal::all();
        return view('admin.WeaklyMeals.index', compact('meals'));
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
        if ($request->id == 0) {
            $request->validate(
                [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'code' => 'required|unique:weakly_milk_meals,code',
                ]
                ,
                [
                    'code.required' => __('main.code_required'),
                    'code.unique' => __('main.code_unique'),
                    'start_date.required' => __('main.start_date_required'),
                    'end_date.unique' => __('main.end_date_required'),
                ]
            );

            $openMeals = WeaklyMilkMeal::where('state', '=', 0)->get();
            if (count($openMeals) > 0) {
                return redirect()->route('weakly_meals')->with('success', __('main.can_not_open_new_weakly_meal'));


            } else {
                WeaklyMilkMeal::create([
                    'start_date' => Carbon::parse($request->start_date),
                    'end_date' => Carbon::parse($request->end_date),
                    'code' => $request->code,
                    'state' => $request->state,
                    'price_buffalo' => $request->price_buffalo ?? 0,
                    'price_bovine' => $request->price_bovine ?? 0,
                    'total_buffalo_weight' => $request->total_buffalo_weight ?? 0,
                    'total_bovine_weight' => $request->total_bovine_weight ?? 0,
                    'total_money' => $request->total_money ?? 0,
                    'number_of_daily_meals' => $request->number_of_daily_meals ?? 0,
                    'notes' => $request->notes ?? "",
                    'user_ins' => Auth::user()->id,
                    'user_upd' => 0
                ]);

                return redirect()->route('weakly_meals')->with('success', __('main.saved'));
            }

        } else {
            return $this->update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WeaklyMilkMeal  $weaklyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function show($id, $post)
    {
        $meal = WeaklyMilkMeal::find($id);
        $meals = DailyMilkMeal::where('weakly_meal_id', $id)->get();
        $meal->number_of_daily_meals = count($meals);
        $setting = Settings::all()->first();
        if ($setting && $post == 1) {
            $meal->price_buffalo = $setting->buffalo_milk_price;
            $meal->price_bovine = $setting->bovine_milk_price;
        } else {
            $meal->price_buffalo = 0;
            $meal->price_bovine = 0;
        }
        echo json_encode($meal);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WeaklyMilkMeal  $weaklyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(WeaklyMilkMeal $weaklyMilkMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeaklyMilkMeal  $weaklyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'start_date' => [
                'required'
            ],
            'end_date' => [
                'required'
            ],
            'code' => [
                'required',
                Rule::unique('weakly_milk_meals', 'code')->ignore($request->id),
            ],
        ], [
            'code.required' => __('main.code_required'),
            'code.unique' => __('main.code_unique'),
            'start_date.required' => __('main.start_date_required'),
            'end_date.unique' => __('main.end_date_required'),
        ]);
        $meal = WeaklyMilkMeal::find($request->id);
        if ($meal) {
            $meal->update([
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date),
                'code' => $request->code,
                'state' => $request->state,
                'price_buffalo' => $request->price_buffalo ?? 0,
                'price_bovine' => $request->price_bovine ?? 0,
                'total_buffalo_weight' => $request->total_buffalo_weight ?? 0,
                'total_bovine_weight' => $request->total_bovine_weight ?? 0,
                'total_money' => $request->total_money ?? 0,
                'number_of_daily_meals' => $request->number_of_daily_meals ?? 0,
                'notes' => $request->notes ?? "",
                'user_upd' => Auth::user()->id
            ]);
            return redirect()->route('weakly_meals')->with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WeaklyMilkMeal  $weaklyMilkMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meal = WeaklyMilkMeal::find($id);
        if ($meal) {
            $meal->delete();
            return redirect()->route('weakly_meals')->with('success', __('main.deleted'));
        }
    }

    public function getCode()
    {
        $meals = WeaklyMilkMeal::all();
        $id = 0;
        if (count($meals) > 0) {
            $id = $meals[count($meals) - 1]->id + 1;
        } else {
            $id = 1;
        }
        $prefix = 'WM';
        $padded = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        echo json_encode($padded);
        exit();

    }

    public function carryingOver(Request $request)
    {
        $meal = WeaklyMilkMeal::find($request->id);
        $meals = DailyMilkMeal::where('weakly_meal_id', '=', $request->id)->get();
        $bouns = 0;
        $w_mealTotal = 0;
        $setting = Settings::all()->first();
        if ($setting) {
            $bouns = $setting->bonus_value;
        } else {
            $bouns = 0;
        }
        $meal_bouns = 0;
        $meal_total = 0;

        foreach ($meals as $d_meal) {
            if ($d_meal->hasBonus == 1) {
                $meal_bouns = $bouns;
            } else {
                $meal_bouns = 0;
            }
            $meal_total = (($request->price_buffalo + $meal_bouns) * $d_meal->buffalo_weight)
                + (($request->price_bovine + $meal_bouns) * $d_meal->bovine_weight);

            $d_meal->update([
                'bonus' => $meal_bouns,
                'total' => $meal_total
            ]);

            $w_mealTotal += $meal_total;
            $this->updateClientAccount(0, $meal_total, $d_meal->supplier_id);
        }

        $meal->update([
            'state' => 1,
            'price_buffalo' => $request->price_buffalo,
            'price_bovine' => $request->price_bovine,
            'total_money' => $w_mealTotal,
            'post_date' => Carbon::now(),
            'user_upd' => Auth::user()->id
        ]);
        return redirect()->route('weakly_meals')->with('success', __('main.posted'));
    }

    public function updateClientAccount($debit, $credit, $client_id)
    {
        $account = ClientAccount::where('client_id', $client_id)->first();
        if ($account) {
            $account->update([
                'client_id' => $client_id,
                'debit' => $account->debit + $debit,
                'credit' => $account->credit + $credit,
                'balance' => $account->balance + $credit - $debit,
                'user_ins' => Auth::user()->id,
                'user_upd' => 0
            ]);
        } else {
            ClientAccount::create([
                'client_id' => $client_id,
                'debit' => $debit,
                'credit' => $credit,
                'opening_balance_debit' => 0,
                'opening_balance_credit' => 0,
                'balance' => $credit - $debit,
                'user_ins' => Auth::user()->id,
                'user_upd' => 0
            ]);
        }
    }


    public function viewDetails($id)
    {
        $wmeal = DB::table('weakly_milk_meals')
            ->select(
                'weakly_milk_meals.id',
                'weakly_milk_meals.state',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            )->where('weakly_milk_meals.id', '=', $id)

            ->get()->first();

        if ($wmeal) {
            $wmeal->from_day_name_ar = $dayTranslations[$wmeal->from_day_name_en] ?? $wmeal->from_day_name_en;
            $wmeal->to_day_name_ar = $dayTranslations[$wmeal->to_day_name_en] ?? $wmeal->to_day_name_en;
        }

        $meals = DB::table('daily_milk_meals')
            ->join('clients', 'daily_milk_meals.supplier_id', '=', 'clients.id')
            ->join('weakly_milk_meals', 'weakly_milk_meals.id', '=', 'daily_milk_meals.weakly_meal_id')
            ->select(
                'clients.name as client_name',
                'weakly_milk_meals.code as weakly_meal_code',
                'weakly_milk_meals.start_date',
                'weakly_milk_meals.end_date',

                DB::raw('MIN(daily_milk_meals.id) as meal_id'),

                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 0 THEN daily_milk_meals.buffalo_weight END) as morning_buffalo_weight'),
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 0 THEN daily_milk_meals.bovine_weight END) as morning_bovine_weight'),

                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 1 THEN daily_milk_meals.buffalo_weight END) as evening_buffalo_weight'),
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 1 THEN daily_milk_meals.bovine_weight END) as evening_bovine_weight'),

                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 0 THEN daily_milk_meals.total END) as morning_total'),
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 1 THEN daily_milk_meals.total END) as evening_total'),

                // Meal code by type
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 0 THEN daily_milk_meals.code END) as morning_meal'),
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 1 THEN daily_milk_meals.code END) as evening_meal')
            )
            ->where('daily_milk_meals.weakly_meal_id', '=', $id)
            ->groupBy(
                'clients.name',
                'weakly_milk_meals.code',
                'weakly_milk_meals.start_date',
                'weakly_milk_meals.end_date'
            )
            ->orderBy('meal_id', 'asc')
            ->get();

        $weaklyMeals = WeaklyMilkMeal::all();
        $suppliers = Client::all();

        return view('admin.WeaklyMeals.view', compact(
            'meals',
            'wmeal',
            'weaklyMeals',
            'suppliers'
        ));




    }

    public function milk_meals()
    {
        if (!Gate::allows('page-access', [8, 'edit'])) {
            abort(403);
        }
        return view('admin.milkMeals.index');
    }

    public function posted_milk_meals()
    {
        if (!Gate::allows('page-access', [8, 'edit'])) {
            abort(403);
        }

        $meals = WeaklyMilkMeal::where('state', '=', 1)->get();
        return view('admin.milkMeals.posted', compact('meals'));
    }

    public function getWeaklyMeals($month, $year, $day)
    {
        $date = $day . '-' . $month . '-' . $year;
        $meals = WeaklyMilkMeal::whereDate('start_date', Carbon::parse($date))
            ->get();

        return $meals;
        echo json_encode($meals);
        exit();
    }

    public function getWeaklyMeal($id, $month, $year, $day)
    {

        $dayTranslations = [
            'Sunday' => 'الأحد',
            'Monday' => 'الإثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
        ];
        $startDate = Carbon::createFromDate($year, $month, $day);
        $meal = DB::table('weakly_milk_meals')
            ->select(
                'weakly_milk_meals.id',
                'weakly_milk_meals.state',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            )->whereDate('start_date', $startDate)->first();




        if ($meal) {
            $meal->from_day_name_ar = $dayTranslations[$meal->from_day_name_en] ?? $meal->from_day_name_en;
            $meal->to_day_name_ar = $dayTranslations[$meal->to_day_name_en] ?? $meal->to_day_name_en;
        }


        $date = $day . '-' . $month . '-' . $year;

        $carbonDate = Carbon::createFromFormat('d-m-Y', $date);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"



        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');


        $suppliers = Client::where('type', '<>', 0)
            ->where('is_farmer', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $setting = Settings::all()->first();

        foreach ($suppliers as $supplier) {
            $supplier->buffalo_price = $setting->buffalo_milk_price ?? 0;
            $supplier->bovine_price = $setting->bovine_milk_price ?? 0;
        }





        return view('admin.milkMeals.daily', compact(
            'meal',
            'dayName',
            'dayName_ar',
            'startOfWeek',
            'endOfWeek',
            'end_dayName_ar',
            'end_dayName',
            'suppliers',
            'setting',
            'startDate'
        ));

    }


    public function postMeal(Request $request)
    {

        try {
            $mMeal = DailyMilkMeal::whereDate('date', Carbon::parse($request->date))->first();
            if ($mMeal) {
                if ($mMeal->isManufactured == 1) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'عفوا لا يمكن حفظ اي وجبات في هذا اليوم لانه تم تصنيع وجباته بالفعل',
                    ]);
                }
            }

            $wId = 0;

            $startDate = Carbon::parse($request->input('start')); // Convert to Carbon instance

            // Query WeaklyMilkMeal where start_date matches
            $wmeal = WeaklyMilkMeal::whereDate('start_date', $startDate)->first();

            if (!$wmeal) {
                //check for open meals

                $wmealsOpen = WeaklyMilkMeal::where('state', '=', 0)->get();
                if (count($wmealsOpen) > 0) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'عذراً، هناك وجبة أسبوعية مفتوحة بالفعل.'
                    ]);
                }



                // should create the weakly first

                $meals = WeaklyMilkMeal::all();
                $id = 0;
                if (count($meals) > 0) {
                    $id = $meals[count($meals) - 1]->id + 1;
                } else {
                    $id = 1;
                }
                $prefix = 'WM';
                $padded = $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
                $wId = WeaklyMilkMeal::create([
                    'start_date' => Carbon::parse($request->start),
                    'end_date' => Carbon::parse($request->end),
                    'code' => $padded,
                    'state' => 0,
                    'price_buffalo' => 0,
                    'price_bovine' => 0,
                    'total_buffalo_weight' => 0,
                    'total_bovine_weight' => 0,
                    'total_money' => 0,
                    'number_of_daily_meals' => 0,
                    'notes' => "",
                    'user_ins' => Auth::user()->id,
                    'user_upd' => 0
                ])->id;
            } else {
                $wId = $wmeal->id;
            }

            if ((int) $request->type < 3) {
                $daily = DailyMilkMeal::where('weakly_meal_id', '=', $wId)
                    ->where('supplier_id', '=', $request->supplier)
                    ->where('type', '=', $request->type)
                    ->whereDate('date', '=', Carbon::parse($request->date))
                    ->first();





                if (!$daily) {
                    $dmeals = DailyMilkMeal::where('weakly_meal_id', '=', $wId)->get();


                    $dId = 0;
                    if (count($dmeals) > 0) {
                        $dId = count($dmeals) + 1;
                    } else {
                        $dId = 1;
                    }

                    $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }


                    $code2 = $wId . '-' . $padded;


                    if ($request->type == 0)
                        $code2 = 'DMM' . $code2;
                    else
                        $code2 = 'DME' . $code2;

                    $total = 0;

                    if ($request->field == 1)
                        $total = $request->value * ($request->buffaloPrice ?? 0);
                    else
                        $total = $request->value * ($request->bovinePrice ?? 0);
                    DailyMilkMeal::create([
                        'code' => $code2,
                        'weakly_meal_id' => $wId,
                        'type' => $request->type,
                        'date' => Carbon::parse($request->date),
                        'supplier_id' => $request->supplier,
                        'buffalo_weight' => $request->field == 1 ? $request->value : 0,
                        'bovine_weight' => $request->field == 0 ? $request->value : 0,
                        'hasBonus' => 0,
                        'bonus' => 0,
                        'notes' => "",
                        'isManufactured' => 0,
                        'car_meal_id' => 0,
                        'buffalo_price' => $request->buffaloPrice ?? 0,
                        'bovine_price' => $request->bovinePrice ?? 0,
                        'isPaid' => 0,
                        'state' => 0,
                        'total' => $total,
                        'user_ins' => Auth::user()->id
                    ]);


                    $buffalo_weight = $request->field == 1 ? $request->value : 0;
                    $bovine_weight = $request->field == 0 ? $request->value : 0;



                    $this->updateWeaklyMilkMealWeight($buffalo_weight, $bovine_weight, $wId);

                } else {


                    $old_buffalo_weight = $daily->buffalo_weight;
                    $old_bovine_weight = $daily->bovine_weight;




                    if ($request->field == 1)
                        $total = ($request->value * ($request->buffaloPrice ?? 0)) + ($daily->bovine_weight * ($request->bovinePrice ?? 0));
                    else
                        $total = $request->value * ($request->bovinePrice ?? 0) + ($daily->buffalo_weight * ($request->buffaloPrice ?? 0));

                    $daily->update([
                        'buffalo_weight' => $request->field == 1 ? $request->value : $daily->buffalo_weight,
                        'bovine_weight' => $request->field == 0 ? $request->value : $daily->bovine_weight,
                        'bovine_price' => $request->bovinePrice,
                        'buffalo_price' => $request->buffaloPrice,
                        'total' => $total,

                    ]);

                    $buffalo_weight = $request->field == 1 ? ($request->value - $old_buffalo_weight) : 0;
                    $bovine_weight = $request->field == 0 ? ($request->value - $old_bovine_weight) : 0;
                    $this->updateWeaklyMilkMealWeight($buffalo_weight, $bovine_weight, $wId);
                }
            } else {
                // update daily price
                $dailys = DailyMilkMeal::where('weakly_meal_id', '=', $wId)
                    ->where('supplier_id', '=', $request->supplier)
                    ->get();

                foreach ($dailys as $daily) {

                    $buffalo_price = $request->field == 3 ? $request->value : $daily->buffalo_price;
                    $bovine_price = $request->field == 2 ? $request->value : $daily->bovine_price;


                    $total = ($daily->buffalo_weight * $buffalo_price) + ($daily->bovine_weight * $bovine_price);
                    $daily->update([
                        'buffalo_price' => $buffalo_price,
                        'bovine_price' => $bovine_price,
                        'total' => $total
                    ]);
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ البيانات بنجاح',
                'wId' => $wId
            ]);

        } catch (QueryException $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ البيانات.',
                'debug' => $err->getMessage() // Only include in development!
            ]);//

        }


    }



    public function postCarMeal(Request $request)
    {

        try {
            $price_b = 0;
            $price = 0;

            if ((int) $request->type < 3) {

                $daily = CarDailyMeal::where('weakly_meal_id', '=', $request->wMeal)
                    ->where('supplier_id', '=', $request->supplier)
                    ->where('member_id', '=', $request->member)
                    ->where('type', '=', $request->type)
                    ->whereDate('date', '=', Carbon::parse($request->date))
                    ->first();
                if (!$daily) {
                    $dmeals = CarDailyMeal::where('weakly_meal_id', '=', $request->wMeal)->get();
                    $dId = 0;
                    if (count($dmeals) > 0) {
                        $dId = count($dmeals) + 1;
                    } else {
                        $dId = 1;
                    }

                    $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }


                    $code2 = $request->wMeal . '-' . $padded;


                    if ($request->type == 0)
                        $code2 = 'CDMM' . $code2;
                    else
                        $code2 = 'CDME' . $code2;

                    $total = 0;

                    if ($request->field == 1)
                        $total = $request->value * ($request->buffaloPrice ?? 0);
                    else
                        $total = $request->value * ($request->bovinePrice ?? 0);

                    CarDailyMeal::create([
                        'code' => $code2,
                        'date' => Carbon::parse($request->date),
                        'type' => $request->type, // 0 morning 1 evening
                        'weakly_meal_id' => $request->wMeal,
                        'supplier_id' => $request->supplier, // the main supplier (car owner)
                        'member_id' => $request->member,
                        'weight' => $request->field == 0 ? $request->value : 0,
                        'price' => $request->bovinePrice,
                        'weight_b' => $request->field == 1 ? $request->value : 0,
                        'price_b' => $request->buffaloPrice,
                        'total' => $total,
                        'user_ins' => Auth::user()->id,
                        'user_upd' => 0
                    ]);
                } else {



                    if ($request->field == 1)
                        $total = ($request->value * ($request->buffaloPrice ?? 0)) + ($daily->weight * ($request->bovinePrice ?? 0));
                    else
                        $total = $request->value * ($request->bovinePrice ?? 0) + ($daily->weight_b * ($request->buffaloPrice ?? 0));


                    $daily->update([
                        'weight_b' => $request->field == 1 ? $request->value : $daily->weight_b,
                        'weight' => $request->field == 0 ? $request->value : $daily->weight,
                         'price' => $request->bovinePrice,
                        'price_b' => $request->buffaloPrice,
                        'total' => $total,

                    ]);

                }

            } else {

                // update car daily price
                $dailys = CarDailyMeal::where('weakly_meal_id', '=', $request->wMeal)
                    ->where('supplier_id', '=', $request->supplier)
                    ->where('member_id', '=', $request->member)
                    ->get();
                foreach ($dailys as $daily) {

                    $price_b = $request->field == 3 ? $request->value : $daily->price_b;
                    $price = $request->field == 2 ? $request->value : $daily->price;
                    $total = ($daily->weight * $price) + ($daily->weight_b * $price_b);

                    $daily->update([
                        'price' => $price,
                        'price_b' => $price_b,
                        'total' => $total,
                    ]);
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ البيانات بنجاح',
                'wId' => $request->wMeal,
                'field' => $request->field,
                'price_b' => $price_b,
                'price' => $price
            ]);

        } catch (QueryException $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ البيانات.',
                'debug' => $err->getMessage() // Only include in development!
            ]);//

        }


    }


    public function weakMeals($wid, $supplier_id = null)
    {
        //$meals = DailyMilkMeal::where('weakly_meal_id', $wid);
        $meals = DB::table('daily_milk_meals')
            ->join('clients', 'clients.id', '=', 'daily_milk_meals.supplier_id')
            ->where('weakly_meal_id', $wid)
            ->select('daily_milk_meals.*', 'clients.car_id');

        if (!is_null($supplier_id)) {
            $meals->where('supplier_id', $supplier_id);
        }

        $meals = $meals->get();



        $totals = CarDailyMeal::where('weakly_meal_id', $wid)
            ->selectRaw('supplier_id, ROUND(SUM(total),2) as total_sum')
            ->groupBy('supplier_id')
            ->pluck('total_sum', 'supplier_id')
            ->toArray();


        $prices = CarDailyMeal::where('weakly_meal_id', $wid)
            ->selectRaw('
                supplier_id,
                ROUND(COALESCE(SUM(total) / NULLIF(SUM(weight), 0), 0), 2) as result_ratio
            ')
            ->groupBy('supplier_id')
            ->pluck('result_ratio', 'supplier_id')
            ->toArray();

        $rows = CarDailyMeal::where('weakly_meal_id', $wid)
            ->pluck('price');


        $data = [
            'meals' => $meals,
            'prices' => $prices,
            'totals' => $totals,

        ];


        echo json_encode($data);
        exit();
    }

    public function weakCarMeals($wid, $supplier_id = null)
    {
        $meals = CarDailyMeal::where('weakly_meal_id', $wid);

        if (!is_null($supplier_id)) {
            $meals->where('supplier_id', $supplier_id);
        }

        $meals = $meals->get();
        echo json_encode($meals);
        exit();
    }

    public function weakCarMealsView($wid, $supplier_id = null)
    {
        $meals = CarDailyMeal::where('weakly_meal_id', $wid);

        if (!is_null($supplier_id)) {
            $meals->where('member_id', $supplier_id);
        }

        $meals = $meals->get();
        echo json_encode($meals);
        exit();
    }


    public function updateWeaklyMilkMealWeight($buffalo_weight, $bovine_weight, $weakly_meal_id)
    {
        $meal = WeaklyMilkMeal::find($weakly_meal_id);
        if ($meal) {
            $meal->update([
                'total_buffalo_weight' => $meal->total_buffalo_weight + $buffalo_weight,
                'total_bovine_weight' => $meal->total_bovine_weight + $bovine_weight,
            ]);

        }
    }

    public function carryingOverWeak($wMealId)
    {
        try {
            $meal = WeaklyMilkMeal::find($wMealId);
            $meals = DailyMilkMeal::where('weakly_meal_id', '=', $wMealId)->get();

            //update
            $w_mealTotal = 0;


            foreach ($meals as $d_meal) {
                $w_mealTotal += $d_meal->total;
                $this->updateClientAccount(0, $d_meal->total, $d_meal->supplier_id);
            }

            $meal->update([
                'state' => 1,
                'total_money' => $w_mealTotal,
                'post_date' => Carbon::now(),
                'user_upd' => Auth::user()->id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'تم الترحيل بنجاح'
            ]);
        } catch (QueryException $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء ترحيل البيانات.',
                'debug' => $err->getMessage() // Only include in development!
            ]);//

        }
    }


    public function supplierMealsCarryOver($wMealId, $supplier_id)
    {
        try {
            $meal = WeaklyMilkMeal::find($wMealId);
            $meals = DailyMilkMeal::where('weakly_meal_id', '=', $wMealId)
                ->where('supplier_id', '=', $supplier_id)
                ->get();

            //update
            $w_mealTotal = 0;


            foreach ($meals as $d_meal) {
                if ($d_meal->state == 0) {
                    $d_meal->update([
                        'state' => 1
                    ]);
                    $w_mealTotal += $d_meal->total;

                }

            }
            if ($w_mealTotal > 0) {
                $this->updateClientAccount(0, $w_mealTotal, $d_meal->supplier_id);

            }
            // now i need to check if all dmeals is posted then we will post the weak meal

            $remain_meals = DailyMilkMeal::where('weakly_meal_id', '=', $wMealId)
                ->where('state', '=', 0)
                ->get();
            $state = count($remain_meals) == 0 ? 1 : 0;
            $meal->update([
                'state' => $state,
                'total_money' => $meal->total_money + $w_mealTotal,
                'post_date' => Carbon::now(),
                'user_upd' => Auth::user()->id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'تم الترحيل بنجاح'
            ]);
        } catch (QueryException $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء ترحيل البيانات.',
                'debug' => $err->getMessage() // Only include in development!
            ]);//

        }
    }

    public function supplierCarMealsCarryOver($wMealId, $supplier_id)
    {
        try {
            $meal = WeaklyMilkMeal::find($wMealId);
            $meals = CarDailyMeal::where('weakly_meal_id', '=', $wMealId)
                ->where('member_id', '=', $supplier_id)
                ->get();




            $w_mealTotal = 0;
            $sId = 0;
            foreach ($meals as $d_meal) {
                $sId = $d_meal->supplier_id;
                if ($d_meal->state == 0) {
                    $d_meal->update([
                        'state' => 1
                    ]);
                    $w_mealTotal += $d_meal->total;
                }
            }
            if ($w_mealTotal > 0) {
                $this->updateClientAccount(0, $w_mealTotal, $d_meal->member_id);

            }

            if ($sId > 0) {
                $standars = DailyMilkMeal::where('weakly_meal_id', '=', $wMealId)
                    ->where('supplier_id', '=', $sId)->get();
                foreach ($standars as $m) {
                    if ($m->state == 0) {
                        $m->update([
                            'state' => 1
                        ]);
                    }
                }
            }
            $remain_meals = DailyMilkMeal::where('weakly_meal_id', '=', $wMealId)
                ->where('state', '=', 0)
                ->get();
            $state = count($remain_meals) == 0 ? 1 : 0;
            $meal->update([
                'state' => $state,
                'total_money' => $meal->total_money + $w_mealTotal,
                'post_date' => Carbon::now(),
                'user_upd' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم الترحيل بنجاح'
            ]);
        } catch (QueryException $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء ترحيل البيانات.',
                'debug' => $err->getMessage() // Only include in development!
            ]);//

        }
    }

    public function weakMealDetails($id, $supplier_id, $start)
    {
        $suppliers = Client::where('type', '<>', 0)
            ->where('id', '=', $supplier_id)
            ->get();

        $meal = WeaklyMilkMeal::find($id);
        $meals = DailyMilkMeal::where('weakly_meal_id', '=', $id)
            ->where('supplier_id', '=', $supplier_id)
            ->get();

        $carbonDate = Carbon::parse($start);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"
        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');

        $beforeBalance = 0;
        $weekBalance = 0;

        $weekBalance = $meals->sum('total'); //14450

        $weekPaid = $meals->sum('paid'); // 12450

        // i need to get beforBalance
        // 1- previous weeks milk
        // 2- previous recipits
        // 3- previous catches
        // 4- previous sales



        $safes = Safe::all();
        $beforeBalance = $this->getBeforeBalance($supplier_id, $id, $startOfWeek, $endOfWeek);

        //return $beforeBalance ;

        return view('admin.milkMeals.meal', compact(
            'suppliers',
            'meal',
            'meals',
            'startOfWeek',
            'endOfWeek',
            'dayName',
            'end_dayName',
            'dayName_ar',
            'end_dayName_ar',
            'weekBalance',
            'beforeBalance',
            'safes',
            'weekPaid'
        ));
    }

    public function weakCarMealDetails($id, $supplier_id, $start)
    {
        $suppliers = Client::where('type', '<>', 0)
            ->where('id', '=', $supplier_id)
            ->get();

        $meal = WeaklyMilkMeal::find($id);
        $meals = CarDailyMeal::where('weakly_meal_id', '=', $id)
            ->where('member_id', '=', $supplier_id)
            ->get();

        $carbonDate = Carbon::parse($start);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"
        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');

        $beforeBalance = 0;
        $weekBalance = 0;

        $weekBalance = $meals->sum('total'); //14450

        $weekPaid = $meals->sum('paid'); // 12450

        // i need to get beforBalance
        // 1- previous weeks milk
        // 2- previous recipits
        // 3- previous catches
        // 4- previous sales



        $safes = Safe::all();
        $beforeBalance = $this->getBeforeBalance($supplier_id, $id, $startOfWeek, $endOfWeek);

        //  return $beforeBalance ;

        return view('admin.milkMeals.carMeal', compact(
            'suppliers',
            'meal',
            'meals',
            'startOfWeek',
            'endOfWeek',
            'dayName',
            'end_dayName',
            'dayName_ar',
            'end_dayName_ar',
            'weekBalance',
            'beforeBalance',
            'safes',
            'weekPaid'
        ));
    }

    public function getBeforeBalance($supplier_id, $wid, $startOfWeek, $endOfWeek): float
    {

        $recipits = DB::table('recipits')
            ->where('supplier_id', $supplier_id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('amount');

        $catchs = DB::table('catch_recipits')
            ->where('client_id', $supplier_id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('amount');

        $dailyMeals = DB::table('daily_milk_meals')
            ->where('state', 1)
            ->where('supplier_id', $supplier_id)
            ->where('weakly_meal_id', '<>', $wid)
            ->whereDate('created_at', '<=', $startOfWeek)  // تم التعديل: تاريخ أقل من بداية الأسبوع
            ->sum('total');

        $sales = DB::table('sales')
            ->where('client_id', $supplier_id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('net');

        $beforeBalance = $dailyMeals + $catchs - $recipits - $sales;

        return $beforeBalance;
    }


    public function weakMealDetailsPrint($id, $supplier_id, $start)
    {
        $supplier = Client::where('type', '<>', 0)
            ->where('id', '=', $supplier_id)
            ->first();

        $meal = WeaklyMilkMeal::find($id);
        $meals = DailyMilkMeal::where('weakly_meal_id', '=', $id)
            ->where('supplier_id', '=', $supplier_id)
            ->get();

        $totalWeight = $meals->sum('bovine_weight');
        $totalMoney = $meals->sum('total');

        $carbonDate = Carbon::parse($start);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"
        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');

        $beforeBalance = 0;
        $weekBalance = 0;

        $weekBalance = $meals->sum('total'); //14450

        $weekPaid = $meals->sum('paid'); // 12450

        //   $clientAccount = ClientAccount::where('client_id' , '=' , $supplier_id) -> get() -> first(); // 0


        //   $beforeBalance = $clientAccount->balance ; // 0 - 14250 +12450 = -2000

        //  return $beforeBalance ;

        $beforeBalance = $this->getBeforeBalance($supplier_id, $id, $startOfWeek, $endOfWeek);

        //return $beforeBalance ;

        return view('admin.milkMeals.print', compact(
            'supplier',
            'meal',
            'meals',
            'startOfWeek',
            'endOfWeek',
            'dayName',
            'end_dayName',
            'dayName_ar',
            'end_dayName_ar',
            'weekBalance',
            'beforeBalance',
            'weekPaid',
            'totalWeight',
            'totalMoney'
        ));
    }

    public function weakCarMealDetailsPrint($id, $supplier_id, $start)
    {
        $supplier = Client::where('type', '<>', 0)
            ->where('id', '=', $supplier_id)
            ->first();

        $meal = WeaklyMilkMeal::find($id);
        $meals = CarDailyMeal::where('weakly_meal_id', '=', $id)
            ->where('member_id', '=', $supplier_id)
            ->get();

        $totalWeight = $meals->sum('weight');
        $totalMoney = $meals->sum('total');

        $carbonDate = Carbon::parse($start);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::FRIDAY);

        $endOfWeek = $startOfWeek->copy()->addDays(6);

        $dayName = $carbonDate->format('l'); // e.g., "Friday"
        $end_dayName = $endOfWeek->format('l');

        Carbon::setLocale('ar'); // Arabic
        $dayName_ar = $carbonDate->translatedFormat('l');
        $end_dayName_ar = $endOfWeek->translatedFormat('l');

        $beforeBalance = 0;
        $weekBalance = 0;

        $weekBalance = $meals->sum('total'); //14450

        $weekPaid = $meals->sum('paid'); // 12450

        $clientAccount = ClientAccount::where('client_id', '=', $supplier_id)->get()->first(); // 0


        $beforeBalance = $this->getBeforeBalance($supplier_id, $id, $startOfWeek, $endOfWeek);

        //  return $beforeBalance ;



        return view('admin.milkMeals.printCar', compact(
            'supplier',
            'meal',
            'meals',
            'startOfWeek',
            'endOfWeek',
            'dayName',
            'end_dayName',
            'dayName_ar',
            'end_dayName_ar',
            'weekBalance',
            'beforeBalance',
            'weekPaid',
            'totalWeight',
            'totalMoney'
        ));
    }
    
    
 public function getSuppliersBeforeBalance($wid, $startOfWeek, $endOfWeek)
{
    $clients = DB::table('clients')->select('id', 'name')->get();

    $result = [];

    foreach ($clients as $supplier) {

        $recipits = DB::table('recipits')
            ->where('supplier_id', $supplier->id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('amount');

        $catchs = DB::table('catch_recipits')
            ->where('client_id', $supplier->id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('amount');

        $dailyMeals = DB::table('daily_milk_meals')
            ->where('state', 1)
            ->where('supplier_id', $supplier->id)
            ->where('weakly_meal_id', '<>', $wid)
            ->whereDate('created_at', '<=', $startOfWeek)
            ->sum('total');

        $sales = DB::table('sales')
            ->where('client_id', $supplier->id)
            ->whereDate('date', '<=', $endOfWeek)
            ->sum('net');

        $beforeBalance = $dailyMeals + $catchs - $recipits - $sales;

        $suppliers[] = [
            'supplier_name' => $supplier->name,
            'before_balance' => $beforeBalance
        ];
    }

     return view('admin.Client.before', compact('suppliers'));
}


}
