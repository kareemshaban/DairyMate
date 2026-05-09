<?php

namespace App\Http\Controllers;

use App\Models\BoxRecipit;
use App\Models\CatchRecipit;
use App\Models\ClientAccount;
use App\Models\Country;
use App\Models\DailyMilkMeal;
use App\Models\Recipit;
use App\Models\Role;
use App\Models\Roles;
use App\Models\Safe;
use App\Models\Sales;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Constraint\Count;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->previous(Carbon::FRIDAY);
        if ($today->isFriday()) {
            $startOfWeek = $today->copy();
        }
        $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();

        $totalSales = Sales::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('net');

        $totalRecipits = Recipit::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount') ;

        $catches = CatchRecipit::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount') ;

        $expenses = BoxRecipit::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount')  ;


        $debits =  ClientAccount::select(
    DB::raw('SUM(debit + opening_balance_debit - credit - opening_balance_credit) as total')
)->value('total');

$quantities = DB::table('store_quantities')
    ->join('items', 'store_quantities.item_id', '=', 'items.id')
    ->select(
        'items.id',
        'items.name',
        DB::raw('SUM(store_quantities.opening_quantity + store_quantities.balance) as total_quantity')
    )
    ->groupBy('items.id', 'items.name')
    ->get();



// Total minutes in the range
        $totalMinutes = $startOfWeek->diffInMinutes($endOfWeek);

// Minutes passed from start until now
        $minutesPassed = $startOfWeek->diffInMinutes(Carbon::now());

// Clamp to 100%
        $progress = min(100, round(($minutesPassed / $totalMinutes) * 100, 1));




            $wMeal = WeaklyMilkMeal::where('state' , '=' , 0) ->first();
            $totalMilk = 0 ;
            if($wMeal){
                $totalMilk = $wMeal -> total_bovine_weight ;
            } else {
                $totalMilk = 0;
            }

            $safes = DB::table('safes') ->
            join('safe_balances' , 'safe_balances.safe_id' , '=' , 'safes.id')
                -> select('safes.*' , 'safe_balances.balance' , 'safe_balances.opening_balance') -> get();

           $stores = Store::all();
        return view('home' , compact('totalSales' , 'totalRecipits' , 'catches' , 'expenses' ,
        'progress' , 'totalMilk' , 'safes' , 'stores' , 'debits' , 'quantities'));
    }
    public function totalRefresh($which)
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->previous(Carbon::FRIDAY);
        if ($today->isFriday()) {
            $startOfWeek = $today->copy();
        }
        $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();

        if($which == 0){
            $totalSales = Sales::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('net');
            echo json_encode($totalSales);
        } elseif ($which == 1){
            $totalRecipits = Recipit::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount') ;
            echo json_encode($totalRecipits);
        } elseif ($which == 2){
            $catches = CatchRecipit::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount') ;
            echo json_encode($catches);
        } elseif ($which == 3){
            $expenses = BoxRecipit::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount')  ;
            echo json_encode($expenses);
        }

        exit();


    }

    public function safeBalanceRefresh()
    {
        $safes = DB::table('safes') ->
        join('safe_balances' , 'safe_balances.safe_id' , '=' , 'safes.id')
            -> select('safes.*' , 'safe_balances.balance' , 'safe_balances.opening_balance') -> get();

        echo json_encode($safes);
        exit();
    }


    public function getGrowthData(): \Illuminate\Http\JsonResponse
    {
        $today = Carbon::today();

// Calculate start of the current week (Friday)
        $currentWeekStart = $today->copy()->previous(Carbon::FRIDAY);
        if ($today->isFriday()) {
            $currentWeekStart = $today->copy();
        }
        $currentWeekEnd = $currentWeekStart->copy()->addDays(6)->endOfDay(); // Thursday end

// Calculate start and end of the previous week
        $lastWeekStart = $currentWeekStart->copy()->subWeek(); // Previous Friday
        $lastWeekEnd = $currentWeekEnd->copy()->subWeek();     // Previous Thursday

// Query total sales for each week
        $currentWeekSales = DB::table('sales')
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->sum('net');

        $lastWeekSales = DB::table('sales')
            ->whereBetween('date', [$lastWeekStart, $lastWeekEnd])
            ->sum('net');

// Calculate growth
        $growth = 0;
        if ($lastWeekSales > 0) {
            $growth = ($currentWeekSales / $lastWeekSales) * 100;
        } elseif ($currentWeekSales > 0) {
            $growth = 100;
        } else {
            $growth = 0;
        }

// Return response
        return response()->json([
            'growth' => round($growth, 2),
            'current' => $currentWeekSales,
            'last' => $lastWeekSales
        ]);
    }


    public function revenueExpenseChartData(): \Illuminate\Http\JsonResponse
    {
        $year = now()->year;

        // Step 1: Monthly revenue from sales
        $sales = DB::table('sales')
            ->selectRaw('MONTH(date) as month, SUM(net) as amount')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('amount', 'month');

        // Step 2: Monthly expenses from recipits
        $recipits = DB::table('recipits')
            ->selectRaw('MONTH(date) as month, SUM(amount) as amount')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('amount', 'month');

        // Step 3: Monthly expenses from box_recipits
        $boxRecipits = DB::table('box_recipits')
            ->selectRaw('MONTH(date) as month, SUM(amount) as amount')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('amount', 'month');

        // Step 4: Prepare monthly arrays
        $revenues = array_fill(0, 12, 0);
        $expenses = array_fill(0, 12, 0);

        for ($i = 1; $i <= 12; $i++) {
            $revenues[$i - 1] = round($sales[$i] ?? 0, 2);
            $expenses[$i - 1] = round(($recipits[$i] ?? 0) + ($boxRecipits[$i] ?? 0), 2);
        }

        return response()->json([
            'revenues' => $revenues,
            'expenses' => $expenses,
        ]);
    }

    public function incomeChartData(): \Illuminate\Http\JsonResponse
    {
        $year = now()->year;

        // Get monthly totals from sales table
        $sales = DB::table('sales')
            ->selectRaw('MONTH(date) as month, SUM(net) as income')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('income', 'month'); // returns: [1 => 1000, 2 => 2000, ...]

        $total = DB::table('sales')
            ->whereYear('date', $year)
            ->sum('net');

        // Create array for all months Jan–Dec (index 0–11)
        $monthlyIncome = array_fill(0, 12, 0);

        foreach ($sales as $month => $value) {
            $monthlyIncome[$month - 1] = round($value, 2);
        }

        // Optionally return only Jan–Jul if your chart shows 7 points
        $data = array_slice($monthlyIncome, 0, 7); // Jan–Jul

        return response()->json([
            'data' => $data,
            'total' =>  $total
        ]);
    }

    public function weeklyExpensesPercentage(): \Illuminate\Http\JsonResponse
    {
        $today = Carbon::now();

        // This week: from Monday to today
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        // Last week: previous Monday to Sunday
        $startOfLastWeek = $startOfWeek->copy()->subWeek();
        $endOfLastWeek = $endOfWeek->copy()->subWeek();

        // Sum current week expenses
        $currentWeekExpense = DB::table('box_recipits')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount'); // adjust column name if needed

        // Sum last week expenses
        $lastWeekExpense = DB::table('box_recipits')
            ->whereBetween('date', [$startOfLastWeek, $endOfLastWeek])
            ->sum('amount');

        // Calculate growth or comparison %
        $percentage = 0;
        if ($lastWeekExpense > 0) {
            $percentage = ($currentWeekExpense / $lastWeekExpense) * 100;
        }

        return response()->json([
            'percentage' => round($percentage, 1),
            'current' => round($currentWeekExpense, 2),
            'last' => round($lastWeekExpense, 2)
        ]);
    }

    public function employee()
    {
        return view('welcome');
    }

    public function users()
    {
        if (!Gate::allows('page-access', [20, 'view'])) {
            abort(403);
        }

        $users = DB::table('users')
            -> leftJoin('roles', 'users.role_id', '=', 'roles.id')
            -> select('users.*', 'roles.name as role' )
            -> get() ;
        $roles = Role::all();
        return view('admin.Users.index' , compact('users' ,'roles'));
    }
    public function storeUser(Request $request)
    {

        if($request -> id == 0){

            User::create([
                'name' => $request -> name,
                'email' => $request -> email,
                'role_id' => $request -> role_id ,
                'password' => Hash::make($request -> password),
            ]);

                return redirect()->route('users')->with('success', __('main.saved'));

        } else {
            $user = User::find($request -> id);
            if($user){
                $user -> update([
                    'name' => $request -> name,
                    'email' => $request -> email,
                    'role_id' => $request -> role_id ,
                    'password' => Hash::make($request -> password),
                ]);

                    return redirect()->route('users')->with('success', __('main.updated'));

            }
        }

    }

    public function showUser($id)
    {
        $user = User::find($id);
        echo json_encode($user);
        exit();
    }
    public function destroyUser($id)
    {
        $user = User::find($id);
        if($user){
            $user -> delete();
            return redirect()->route('users')->with('success', __('main.deleted'));
        }
    }

    public function getUserProfile($id)
    {
        $user = DB::table('users')
            -> leftJoin('roles', 'users.role_id', '=', 'roles.id')
            -> select('users.*', 'roles.name as role')
            -> where('users.id', '=', $id)
            -> get() -> first();

        if($user){

            $auths = DB::table('authentications')
                -> join('roles', 'roles.id', '=', 'authentications.role_id')
                -> join('forms', 'forms.id', '=', 'authentications.form_id')
                -> select('authentications.*', 'roles.name as role' ,'forms.name as form' )
                -> where('authentications.role_id', '=', $user -> role_id)
                -> get();



            return view('admin.Users.profile' , compact('user' , 'auths' ));
        }


    }

    public function resetPassword(Request $request)
    {

        $user = User::find($request -> id);
        if($user){
            $user -> update([
                'password' => Hash::make($request -> new_password),
            ]);
            return redirect()->route('getUserProfile' , $user -> id)->with('success', __('main.updated'));
        }
    }

    public function updatePassword($id)
    {
        $user = User::find($id);

        return view ('admin.Users.updatePassword' , compact('user'));

    }


}
