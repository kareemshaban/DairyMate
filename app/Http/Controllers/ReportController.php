<?php

namespace App\Http\Controllers;

use App\Models\CheeseMeal;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\Items;
use App\Models\Recipit;
use App\Models\Safe;
use App\Models\SafeBalance;
use App\Models\Store;
use App\Models\WeaklyMilkMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function clientAccountSearch()
    {

        if (!Gate::allows('page-access', [19, 'view'])) {
            abort(403);
        }
        $clients = Client::all();
        return view('admin.Reports.clientAccountSearch', compact('clients'));
    }
    public function clientAccountReport(Request $request){


        $client = Client::find($request -> client_id);
        $brfors = [];
        $before = [
            'client_name' => '',
            'debit' => 0 ,
            'credit' => 0
        ];

        if($request -> has('isDateFrom')){
            $from_date = $request -> from_date ;
        } else {
            $from_date = "" ;
        }
        if($request -> has('isDateTo')){
            $to_date = $request -> to_date ;
        } else {
            $to_date = "" ;
        }

        $account = DB::table('client_accounts')
            -> join('clients' , 'client_accounts.client_id' , '=' , 'clients.id')
            ->where('client_id' , $request -> client_id)
            -> select('client_accounts.*' , 'clients.name as client' )
            -> first();

        $recipits = DB::table('recipits')
            ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
            -> where('recipits.supplier_id' , '=' , $request -> client_id)
            ->select(
                'recipits.id as docId',
                'recipits.bill_number as docNumber',
                'recipits.date as docDate',
                'recipits.amount as amount',
                'clients.name as client',
                DB::raw('0 as type')  // Static column
            ) -> where('recipits.state' , 1);

        $catchs = DB::table('catch_recipits')
            ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
            ->where('catch_recipits.client_id', '=', $request->client_id)
            ->select(
                'catch_recipits.id as docId',
                'catch_recipits.bill_number as docNumber',
                'catch_recipits.date as docDate',
                'catch_recipits.amount as amount',
                'clients.name as client',
                DB::raw("CASE
                    WHEN catch_recipits.loan_id = 0 THEN 1
                    ELSE 2
                 END as type")
            )-> where('catch_recipits.state' , 1);


        $loans = DB::table('loans')
            ->join('clients', 'loans.supplier_id', '=', 'clients.id')
            -> where('loans.supplier_id' , '=' , $request -> client_id)
            ->select(
                'loans.id as docId',
                'loans.bill_number as docNumber',
                'loans.date as docDate',
                'loans.amount as amount',
                'clients.name as client',
                DB::raw('3 as type'));



        $dailyMeals = DB::table('daily_milk_meals')
            ->join('clients', 'daily_milk_meals.supplier_id', '=', 'clients.id')
            ->join('weakly_milk_meals', 'daily_milk_meals.weakly_meal_id', '=', 'weakly_milk_meals.id')
            ->where('daily_milk_meals.state', '=', 1)
            ->where('daily_milk_meals.supplier_id', '=', $request->client_id)
            ->select(
                'daily_milk_meals.weakly_meal_id as docId',
                'weakly_milk_meals.code as docNumber',
                'weakly_milk_meals.start_date as docDate',
                DB::raw('SUM(daily_milk_meals.total) as amount'),
                'clients.name as client',
                DB::raw('4 as type')
            )
            ->groupBy(
                'daily_milk_meals.weakly_meal_id',
                'weakly_milk_meals.code',
                'weakly_milk_meals.start_date',
                'clients.name'
            )-> where('daily_milk_meals.state' , 1);

       // return $dailyMeals ;

        $sales = DB::table('sales')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            -> where('sales.client_id' , '=' , $request -> client_id)
            ->select(
                'sales.id as docId',
                'sales.bill_number as docNumber',
                'sales.date as docDate',
                'sales.net as amount',
                'clients.name as client',
                DB::raw('5 as type'))-> where('sales.state' , 1);


        if ($request->has('isDateFrom')) {
            $recipitsBerion = clone $recipits;
            $catchsBeriod = clone $catchs;
            $loansBerion = clone $loans;
            $dailyMealsBerion = clone $dailyMeals;
            $salesBerion = clone $sales;

            $recipitsBerion = $recipitsBerion->whereDate('date', '>=', Carbon::parse($request->from_date))
                ->whereDate('date', '<=', Carbon::parse($request->to_date));



            $catchsBeriod = $catchsBeriod->whereDate('date', '>=', Carbon::parse($request->from_date))
                ->whereDate('date', '<=', Carbon::parse($request->to_date));

            $loansBerion = $loansBerion->whereDate('date', '>=', Carbon::parse($request->from_date))
                ->whereDate('date', '<=', Carbon::parse($request->to_date));

            $dailyMealsBerion = $dailyMealsBerion->whereDate('date', '>=', Carbon::parse($request->from_date))
                ->whereDate('date', '<=', Carbon::parse($request->to_date));

            $salesBerion = $salesBerion->whereDate('date', '>=', Carbon::parse($request->from_date))
                ->whereDate('date', '<=', Carbon::parse($request->to_date));

            $recipitsBefore = $recipits->whereDate('date', '<', Carbon::parse($request->from_date));

            $catchsBefore = $catchs->whereDate('date', '<', Carbon::parse($request->from_date));

            $loansBefore = $loans->whereDate('date', '<', Carbon::parse($request->from_date));

            $dailyMealsBefore = $dailyMeals->whereDate('date', '<', Carbon::parse($request->from_date));

            $salesBefore = $sales->whereDate('date', '<', Carbon::parse($request->from_date));


            $dataBefore =$recipitsBefore -> union($catchsBefore) -> union($loansBefore) -> union($dailyMealsBefore) -> union($salesBefore);

            $debit = 0 ;
            $credit = 0 ;
            foreach($dataBefore -> get() as $item){
                if($item -> type == 2 ||  $item -> type == 4 || $item -> type == 12 ){
                    $debit += $item -> amount ;
                } else {
                    $credit += $item -> amount ;
                }

            }
            $before = [
                'client_name' => $client -> name,
                'debit' => $debit ,
                'credit' => $credit
            ];
            array_push($brfors , $before);


            $data = $recipitsBerion -> union($catchsBeriod) -> union($loansBerion) -> union($dailyMealsBerion) -> union($salesBerion);

        } else {
            $before = [
                'client_name' => $client->name,
                'debit' => 0,
                'credit' => 0
            ];
            array_push($brfors, $before);

            $data = $recipits->union($catchs)->union($loans)->union($dailyMeals)->union($sales);

        }
            $type = $request -> report_type ;
            if($request -> report_type == 1 ){
                $data = $data->orderBy('docDate', 'ASC')->get();

               // return $data ;
                return view('admin.Reports.clientAccountReport', compact('data', 'brfors' , 'type' , 'from_date' , 'to_date' ,'account'));
            }

            else if ($request -> report_type == 0) {

                $result = [];
                $data  = $data->orderBy('docDate', 'ASC')->get();
                foreach ($data as $row) {

                    $amount = (float)$row->amount;
                    $client = $row->client;
                    $type =(int)$row->type;

                    // Initialize if safe not set
                    if (!isset($result[$client])) {
                        $result[$client] = [
                            'client' => $client,
                            'debit' => 0,
                            'credit' => 0
                        ];
                    }

                    // Determine category
                    if (in_array($type, [0, 3, 5])) {
                        $result[$client]['debit'] += $amount;
                    } else {
                        $result[$client]['credit'] += $amount;
                    }
                }

                $totals = array_values($result);

                return view('admin.Reports.clientAccountReportTotal', compact('totals' , 'type' , 'from_date' , 'to_date' , 'account' ));


            }


    }

    public function clientAccountReportPrint(Request $request)
    {
        $client = Client::findOrFail($request->client_id);

        /* ================= Account ================= */

        $account = DB::table('client_accounts')
            ->join('clients', 'client_accounts.client_id', '=', 'clients.id')
            ->where('client_id', $request->client_id)
            ->select('client_accounts.*', 'clients.name as client')
            ->first();

        /* ================= Base Queries ================= */

        $recipits = DB::table('recipits')
            ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
            ->where('recipits.supplier_id', $request->client_id)
            ->where('recipits.state', 1)
            ->select(
                'recipits.id as docId',
                'recipits.bill_number as docNumber',
                'recipits.date as docDate',
                'recipits.amount as amount',
                'clients.name as client',
                DB::raw('0 as type')
            );

        $catchs = DB::table('catch_recipits')
            ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
            ->where('catch_recipits.client_id', $request->client_id)
            ->where('catch_recipits.state', 1)
            ->select(
                'catch_recipits.id as docId',
                'catch_recipits.bill_number as docNumber',
                'catch_recipits.date as docDate',
                'catch_recipits.amount as amount',
                'clients.name as client',
                DB::raw("CASE WHEN catch_recipits.loan_id = 0 THEN 1 ELSE 2 END as type")
            );

        $loans = DB::table('loans')
            ->join('clients', 'loans.supplier_id', '=', 'clients.id')
            ->where('loans.supplier_id', $request->client_id)
            ->select(
                'loans.id as docId',
                'loans.bill_number as docNumber',
                'loans.date as docDate',
                'loans.amount as amount',
                'clients.name as client',
                DB::raw('3 as type')
            );

        $dailyMeals = DB::table('daily_milk_meals')
            ->join('clients', 'daily_milk_meals.supplier_id', '=', 'clients.id')
            ->join('weakly_milk_meals', 'daily_milk_meals.weakly_meal_id', '=', 'weakly_milk_meals.id')
            ->where('daily_milk_meals.state', 1)
            ->where('daily_milk_meals.supplier_id', $request->client_id)
            ->select(
                'daily_milk_meals.weakly_meal_id as docId',
                'weakly_milk_meals.code as docNumber',
                'weakly_milk_meals.start_date as docDate',
                DB::raw('SUM(daily_milk_meals.total) as amount'),
                'clients.name as client',
                DB::raw('4 as type')
            )
            ->groupBy(
                'daily_milk_meals.weakly_meal_id',
                'weakly_milk_meals.code',
                'weakly_milk_meals.start_date',
                'clients.name'
            );

        $sales = DB::table('sales')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->where('sales.client_id', $request->client_id)
            ->where('sales.state', 1)
            ->select(
                'sales.id as docId',
                'sales.bill_number as docNumber',
                'sales.date as docDate',
                'sales.net as amount',
                'clients.name as client',
                DB::raw('5 as type')
            );

        /* ================= brfors (Before Period Balance) ================= */

        $brfors = [];
        $before = [
            'client_name' => $client->name,
            'debit' => 0,
            'credit' => 0
        ];

        if ($request->filled('isFromDate') && $request->filled('fromDate')) {

            $from = Carbon::parse($request->fromDate);

            $recipitsBefore   = clone $recipits;
            $catchsBefore     = clone $catchs;
            $loansBefore      = clone $loans;
            $dailyMealsBefore = clone $dailyMeals;
            $salesBefore      = clone $sales;

            $recipitsBefore->whereDate('recipits.date', '<', $from);
            $catchsBefore->whereDate('catch_recipits.date', '<', $from);
            $loansBefore->whereDate('loans.date', '<', $from);
            $dailyMealsBefore->whereDate('weakly_milk_meals.start_date', '<', $from);
            $salesBefore->whereDate('sales.date', '<', $from);

            $beforeData = $recipitsBefore
                ->unionAll($catchsBefore)
                ->unionAll($loansBefore)
                ->unionAll($dailyMealsBefore)
                ->unionAll($salesBefore)
                ->get();

            foreach ($beforeData as $row) {
                if (in_array((int)$row->type, [2, 4])) {
                    $before['debit'] += $row->amount;
                } else {
                    $before['credit'] += $row->amount;
                }
            }
        }

        $brfors[] = $before;

        /* ================= Date Filters ================= */

        if ($request->filled('isFromDate') && $request->filled('fromDate')) {
            $from = Carbon::parse($request->fromDate);

            $recipits->whereDate('recipits.date', '>=', $from);
            $catchs->whereDate('catch_recipits.date', '>=', $from);
            $loans->whereDate('loans.date', '>=', $from);
            $dailyMeals->whereDate('weakly_milk_meals.start_date', '>=', $from);
            $sales->whereDate('sales.date', '>=', $from);
        }

        if ($request->filled('isToDate') && $request->filled('toDate')) {
            $to = Carbon::parse($request->toDate);

            $recipits->whereDate('recipits.date', '<=', $to);
            $catchs->whereDate('catch_recipits.date', '<=', $to);
            $loans->whereDate('loans.date', '<=', $to);
            $dailyMeals->whereDate('weakly_milk_meals.start_date', '<=', $to);
            $sales->whereDate('sales.date', '<=', $to);
        }

        /* ================= Union ================= */

        $data = $recipits
            ->unionAll($catchs)
            ->unionAll($loans)
            ->unionAll($dailyMeals)
            ->unionAll($sales)
            ->orderBy('docDate', 'ASC')
            ->get();

        /* ================= Print Types ================= */

        // 🔹 Detailed Print
        if ($request->report_type == 1) {
            return view(
                'admin.Reports.clientAccountReportPrint',
                compact('data', 'client', 'account', 'brfors')
            );
        }

        // 🔹 Total Print
        $result = [];

        foreach ($data as $row) {
            if (!isset($result[$row->client])) {
                $result[$row->client] = [
                    'client' => $row->client,
                    'debit' => 0,
                    'credit' => 0
                ];
            }

            if (in_array((int)$row->type, [0, 3, 5])) {
                $result[$row->client]['debit'] += $row->amount;
            } else {
                $result[$row->client]['credit'] += $row->amount;
            }
        }

        $totals = array_values($result);

        return view(
            'admin.Reports.clientAccountReportTotalPrint',
            compact('totals', 'client', 'account', 'brfors')
        );
    }



    public function stockMovementSearch()
    {

        if (!Gate::allows('page-access', [19, 'view'])) {
            abort(403);
        }


        $items = Items::all();
        $stores = Store::all();
        return view('admin.Reports.stockMovementSearch', compact('items' , 'stores'));
    }

    public function stockMovementReport(Request $request){
        $stockTransactionOut = DB::table('stock_transactions')
            ->join('stores', 'stock_transactions.from_store', '=', 'stores.id')
            -> join('stock_transaction_details', 'stock_transactions.id', '=', 'stock_transaction_details.transaction_id')
            -> join('items', 'stock_transaction_details.item_id', '=', 'items.id')
            -> select(
                'stores.name as store',
                'items.name as item',
                DB::raw('2 as type'),
                DB::raw('0 as quantity_in'),
                DB::raw('0 as weight_in'),
                'stock_transaction_details.quantity as quantity_out',
                'stock_transaction_details.weight as weight_out',
                'stock_transactions.date'

            )  ->where(function ($query) use ($request) {
                // Apply condition for item_id based on request
                if ($request->item_id != "") {
                    $query->where('stock_transaction_details.item_id', '=', $request->item_id);
                }
            })
            ->where(function ($query) use ($request) {
                // Apply condition for store_id based on request
                if ($request->store_id != "") {
                    $query->where('stock_transactions.from_store', '=', $request->store_id);
                }
            })
            -> where('stock_transactions.state' , '=' , 1);

        $type =  $request -> report_type  ;
        if($request -> report_type == 0) {
            $stock = DB::table('store_quantities')
                ->join('items', 'store_quantities.item_id', '=', 'items.id')
                ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
                ->select(
                    'store_quantities.item_id',
                    'store_quantities.store_id',
                    DB::raw('SUM(store_quantities.opening_quantity ) as opening_quantity '),
                    DB::raw('SUM(store_quantities.quantity_in) as quantity_in'),
                    DB::raw('SUM(store_quantities.quantity_out) as quantity_out'),
                    DB::raw('SUM(store_quantities.balance) as balance'),
                    'items.name as itemName',
                    'stores.name as storeName'
                )
                ->when($request->filled('item_id'), function ($q) use ($request) {
                    $q->where('store_quantities.item_id', $request->item_id);
                })
                ->when($request->filled('store_id'), function ($q) use ($request) {
                    $q->where('store_quantities.store_id', $request->store_id);
                })
                ->groupBy(
                    'store_quantities.item_id',
                    'store_quantities.store_id',
                    'items.name',
                    'stores.name'
                )
                ->get();

            //return $stock;



            return view('admin.Reports.stockMovementReportTotal', compact('stock' , 'type'));
        } else {

            $stockIn = DB::table('stock_transaction_ins')
                ->join('stores', 'stock_transaction_ins.store_id', '=', 'stores.id')
                -> join('stock_transaction_in_details', 'stock_transaction_ins.id', '=', 'stock_transaction_in_details.transaction_id')
                -> join('items', 'stock_transaction_in_details.item_id', '=', 'items.id')
                -> select(
                    'stores.name as store',
                    'items.name as item',
                    DB::raw('0 as type'),
                    'stock_transaction_in_details.quantity as quantity_in',
                    'stock_transaction_in_details.weight as weight_in',
                    DB::raw('0 as quantity_out'),
                    DB::raw('0 as weight_out'),
                    'stock_transaction_ins.date'

                )   ->where(function ($query) use ($request) {
                    // Apply condition for item_id based on request
                    if ($request->item_id != "") {
                        $query->where('stock_transaction_in_details.item_id', '=', $request->item_id);
                    }
                })
                ->where(function ($query) use ($request) {
                    // Apply condition for store_id based on request
                    if ($request->store_id != "") {
                        $query->where('stock_transaction_ins.store_id', '=', $request->store_id);
                    }
                });

            $stockTransactionIn = DB::table('stock_transactions')
                ->join('stores', 'stock_transactions.to_store', '=', 'stores.id')
                -> join('stock_transaction_details', 'stock_transactions.id', '=', 'stock_transaction_details.transaction_id')
                -> join('items', 'stock_transaction_details.item_id', '=', 'items.id')
                -> select(
                    'stores.name as store',
                    'items.name as item',
                    DB::raw('1 as type'),
                    'stock_transaction_details.quantity as quantity_in',
                    'stock_transaction_details.weight as weight_in',
                    DB::raw('0 as quantity_out'),
                    DB::raw('0 as weight_out'),
                    'stock_transactions.date'

                )
                ->where(function ($query) use ($request) {
                    // Apply condition for item_id based on request
                    if ($request->item_id != "") {
                        $query->where('stock_transaction_details.item_id', '=', $request->item_id);
                    }
                })
                ->where(function ($query) use ($request) {
                    // Apply condition for store_id based on request
                    if ($request->store_id != "") {
                        $query->where('stock_transactions.to_store', '=', $request->store_id);
                    }
                })
                -> where('stock_transactions.state' , '=' , 1);




            $salesBill = DB::table('sales')
                -> join('stores', 'sales.store_id', '=', 'stores.id')
                -> join('sales_details', 'sales.id', '=', 'sales_details.bill_id')
                -> join('items', 'sales_details.item_id', '=', 'items.id')
               -> select(
                    'stores.name as store',
                        'items.name as item',
                    DB::raw('3 as type'),
                    DB::raw('0 as quantity_in'),
                    DB::raw('0 as weight_in'),
                   'sales_details.quantity as quantity_out',
                   'sales_details.weight as weight_out',
                    'sales.date'

                )
                ->where(function ($query) use ($request) {
                    // Apply condition for item_id based on request
                    if ($request->item_id != "") {
                        $query->where('sales_details.item_id', '=', $request->item_id);
                    }
                })
                ->where(function ($query) use ($request) {
                    // Apply condition for store_id based on request
                    if ($request->store_id != "") {
                        $query->where('sales.store_id', '=', $request->store_id);
                    }
                })
                -> where('sales.state' , '=' , 1);


              //  return $salesBill -> get();




            $itemTransformIn = DB::table('item_transform_docs')
                ->join('stores', 'item_transform_docs.to_store', '=', 'stores.id')
                -> join('item_transform_doc_details', 'item_transform_docs.id', '=', 'item_transform_doc_details.doc_id')
                -> join('items', 'item_transform_doc_details.to_item_id', '=', 'items.id')
                -> select(
                    'stores.name as store',
                    'items.name as item',
                    DB::raw('4 as type'),
                    'item_transform_doc_details.quantity as quantity_in',
                    'item_transform_doc_details.weight as weight_in',
                    DB::raw('0 as quantity_out'),
                    DB::raw('0 as weight_out'),
                    'item_transform_docs.date'

                )
                ->where(function ($query) use ($request) {
                    // Apply condition for item_id based on request
                    if ($request->item_id != "") {
                        $query->where('item_transform_doc_details.to_item_id', '=', $request->item_id);
                    }
                })
                ->where(function ($query) use ($request) {
                    // Apply condition for store_id based on request
                    if ($request->store_id != "") {
                        $query->where('item_transform_docs.to_store', '=', $request->store_id);
                    }
                })
                -> where('item_transform_docs.state' , '=' , 1);


            $itemTransformOut = DB::table('item_transform_docs')
                ->join('stores', 'item_transform_docs.from_store', '=', 'stores.id')
                -> join('item_transform_doc_details', 'item_transform_docs.id', '=', 'item_transform_doc_details.doc_id')
                -> join('items', 'item_transform_doc_details.from_item_id', '=', 'items.id')
                -> select(
                    'stores.name as store',
                    'items.name as item',
                    DB::raw('5 as type'),
                    DB::raw('0 as quantity_in'),
                    DB::raw('0 as weight_in'),
                    'item_transform_doc_details.quantity as quantity_out',
                    'item_transform_doc_details.weight as weight_out',
                    'item_transform_docs.date'

                )
                ->where(function ($query) use ($request) {
                    // Apply condition for item_id based on request
                    if ($request->item_id != "") {
                        $query->where('item_transform_doc_details.from_item_id', '=', $request->item_id);
                    }
                })
                ->where(function ($query) use ($request) {
                    // Apply condition for store_id based on request
                    if ($request->store_id != "") {
                        $query->where('item_transform_docs.from_store', '=', $request->store_id);
                    }
                })
                -> where('item_transform_docs.state' , '=' , 1);



            $data = $stockIn -> unionAll($stockTransactionIn) -> unionAll($stockTransactionOut) -> unionAll($salesBill)
                -> unionAll($itemTransformIn) -> unionAll($itemTransformOut);
            $data = $data -> get();






            return view('admin.Reports.stockMovementReport', compact('data' , 'type'));



        }



    }

    public function printStockMealMovementReport(Request $request)
    {

        $type = $request->reportType;

       // return  $type ;
        // ================== TOTAL REPORT ==================
        if ($request->reportType == 0) {

            $stock = DB::table('store_quantities')
                ->join('items', 'store_quantities.item_id', '=', 'items.id')
                ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
                ->select(
                    'stores.name as store',
                    'items.name as item',
                    'store_quantities.*'
                )
                ->when($request->item_id != "", function ($q) use ($request) {
                    $q->where('store_quantities.item_id', $request->item_id);
                })
                ->when($request->store_id != "", function ($q) use ($request) {
                    $q->where('store_quantities.store_id', $request->store_id);
                })
                ->get();

            return view('admin.Reports.stockMovementReportPrintTotal', compact('stock', 'type'));
        }

        // ================== DETAILED REPORT ==================

        $stockIn = DB::table('stock_transaction_ins')
            ->join('stores', 'stock_transaction_ins.store_id', '=', 'stores.id')
            ->join('stock_transaction_in_details', 'stock_transaction_ins.id', '=', 'stock_transaction_in_details.transaction_id')
            ->join('items', 'stock_transaction_in_details.item_id', '=', 'items.id')
            ->select(
                'stores.name as store',
                'items.name as item',
                DB::raw('0 as type'),
                'stock_transaction_in_details.quantity as quantity_in',
                'stock_transaction_in_details.weight as weight_in',
                DB::raw('0 as quantity_out'),
                DB::raw('0 as weight_out'),
                'stock_transaction_ins.date'
            )
            ->when($request->item_id != "", function ($q) use ($request) {
                $q->where('stock_transaction_in_details.item_id', $request->item_id);
            })
            ->when($request->store_id != "", function ($q) use ($request) {
                $q->where('stock_transaction_ins.store_id', $request->store_id);
            });

        $stockTransactionOut = DB::table('stock_transactions')
            ->join('stores', 'stock_transactions.from_store', '=', 'stores.id')
            ->join('stock_transaction_details', 'stock_transactions.id', '=', 'stock_transaction_details.transaction_id')
            ->join('items', 'stock_transaction_details.item_id', '=', 'items.id')
            ->select(
                'stores.name as store',
                'items.name as item',
                DB::raw('2 as type'),
                DB::raw('0 as quantity_in'),
                DB::raw('0 as weight_in'),
                'stock_transaction_details.quantity as quantity_out',
                'stock_transaction_details.weight as weight_out',
                'stock_transactions.date'
            )
            ->where('stock_transactions.state', 1)
            ->when($request->item_id != "", function ($q) use ($request) {
                $q->where('stock_transaction_details.item_id', $request->item_id);
            })
            ->when($request->store_id != "", function ($q) use ($request) {
                $q->where('stock_transactions.from_store', $request->store_id);
            });

        $salesBill = DB::table('sales')
            ->join('stores', 'sales.store_id', '=', 'stores.id')
            ->join('sales_details', 'sales.id', '=', 'sales_details.bill_id')
            ->join('items', 'sales_details.item_id', '=', 'items.id')
            ->select(
                'stores.name as store',
                'items.name as item',
                DB::raw('3 as type'),
                DB::raw('0 as quantity_in'),
                DB::raw('0 as weight_in'),
                'sales_details.quantity as quantity_out',
                'sales_details.weight as weight_out',
                'sales.date'
            )
            ->where('sales.state', 1)
            ->when($request->item_id != "", function ($q) use ($request) {
                $q->where('sales_details.item_id', $request->item_id);
            })
            ->when($request->store_id != "", function ($q) use ($request) {
                $q->where('sales.store_id', $request->store_id);
            });

        $data = $stockIn
            ->unionAll($stockTransactionOut)
            ->unionAll($salesBill)
            ->get();

        return view('admin.Reports.stockMovementReportPrintDetailed', compact('data', 'type'));
    }

    public function stockMealMovementSearch()
    {
        $items = Items::all();
        $stores = Store::all();
        $meals = CheeseMeal::all();
        return view('admin.Reports.stockMealMovementSearch' , compact('items', 'stores', 'meals'));
    }

    public function stockMealMovementReport(Request $request)
    {
        //cheese_meal_id
        $data = DB::table('store_quantities')
            ->join('items', 'store_quantities.item_id', '=', 'items.id')
            ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
            ->join('cheese_meals', 'store_quantities.cheese_meal_id', '=', 'cheese_meals.id')
            ->select(
                'store_quantities.*',
                'items.name as item',
                'stores.name as store',
                'cheese_meals.code as cheese_meal',
                'cheese_meals.symbol'
            )
            ->when($request->filled('meal_id'), function ($q) use ($request) {
                $q->where('store_quantities.cheese_meal_id', $request->meal_id);
            })
            ->when($request->filled('store_id'), function ($q) use ($request) {
                $q->where('store_quantities.store_id', $request->store_id);
            })
            ->when($request->filled('item_id'), function ($q) use ($request) {
                $q->where('store_quantities.item_id', $request->item_id);
            })
            ->get();

        return view('admin.Reports.stockMealMovementReport', compact('data'));


    }

    public function printStockMovementReport(Request $request)
    {
        //cheese_meal_id
        $data = DB::table('store_quantities')
            ->join('items', 'store_quantities.item_id', '=', 'items.id')
            ->join('stores', 'store_quantities.store_id', '=', 'stores.id')
            ->join('cheese_meals', 'store_quantities.cheese_meal_id', '=', 'cheese_meals.id')
            ->select(
                'store_quantities.*',
                'items.name as item',
                'stores.name as store',
                'cheese_meals.code as cheese_meal',
                'cheese_meals.symbol'
            )
            ->when($request->filled('meal_id'), function ($q) use ($request) {
                $q->where('store_quantities.cheese_meal_id', $request->meal_id);
            })
            ->when($request->filled('store_id'), function ($q) use ($request) {
                $q->where('store_quantities.store_id', $request->store_id);
            })
            ->when($request->filled('item_id'), function ($q) use ($request) {
                $q->where('store_quantities.item_id', $request->item_id);
            })
            ->get();

        return view('admin.Reports.stockMealMovementPrint', compact('data'));


    }

    public function safeMovementSearch()
    {

        if (!Gate::allows('page-access', [19, 'view'])) {
            abort(403);
        }


        $safes = Safe::all();
        return view('admin.Reports.safeMovementSearch', compact('safes' ));

    }

    public function safeMovementReport(Request $request)
    {
        $recipits = DB::table('recipits')
            ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
            ->join('safes', 'recipits.safe_id', '=', 'safes.id')
            ->select(
                'recipits.id as docId',
                'recipits.bill_number as docNumber',
                'recipits.date as docDate',
                'recipits.amount as amount',
                'clients.name as client',
                'safes.name as safe',
                DB::raw('0 as type')  // Static column
            ) -> where('recipits.state' , 1);

        $boxes = DB::table('box_recipits')
            ->join('recipit_types', 'box_recipits.recipit_type', '=', 'recipit_types.id')
            ->join('safes', 'box_recipits.safe_id', '=', 'safes.id')
            ->select(
                'box_recipits.id as docId',
                'box_recipits.bill_number as docNumber',
                'box_recipits.date as docDate',
                'box_recipits.amount as amount',
                'recipit_types.name as client',
                'safes.name as safe',
                DB::raw('4 as type')  // Static column
            )-> where('box_recipits.state' , 1);

        $catchs = DB::table('catch_recipits')
            ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
            ->join('safes', 'catch_recipits.safe_id', '=', 'safes.id')
            ->select(
                'catch_recipits.id as docId',
                'catch_recipits.bill_number as docNumber',
                'catch_recipits.date as docDate',
                'catch_recipits.amount as amount',
                'clients.name as client',
                'safes.name as safe',
                DB::raw("CASE
                    WHEN catch_recipits.loan_id = 0 THEN 1
                    ELSE 2
                 END as type")
            ) -> where('catch_recipits.state' , 1);

        $exchangeOut = DB::table('safe_balance_exchanges')
            ->join('safes', 'safe_balance_exchanges.from_safe_id','=', 'safes.id')
            ->select(
                'safe_balance_exchanges.id as docId',
                'safe_balance_exchanges.bill_number as docNumber',
                'safe_balance_exchanges.date as docDate',
                'safe_balance_exchanges.balance as amount',
                DB::raw("'empty' as client"),
                'safes.name as safe',
                DB::raw('7 as type')
            );


        $exchangeIn = DB::table('safe_balance_exchanges')
            ->join('safes', 'safe_balance_exchanges.to_safe_id','=', 'safes.id')
            ->select(
                'safe_balance_exchanges.id as docId',
                'safe_balance_exchanges.bill_number as docNumber',
                'safe_balance_exchanges.date as docDate',
                'safe_balance_exchanges.balance as amount',
                DB::raw("'empty' as client"),
                'safes.name as safe',
                DB::raw('8 as type')
            );


        $salaries = DB::table('salaries')
            -> join('safes', 'salaries.safe_id', '=', 'safes.id')
            -> join('employees', 'salaries.employee_id', '=', 'employees.id')
            -> select('salaries.id as docId',
                DB::raw("'empty' as docNumber"),
                'salaries.created_at as docDate',
                'salaries.total_amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('9 as type')
            );

        $advances = DB::table('advances')
            -> join('safes', 'advances.safe_id', '=', 'safes.id')
            -> join('employees', 'advances.employee_id', '=', 'employees.id')
            -> select('advances.id as docId',
                DB::raw("'empty' as docNumber"),
                'advances.date as docDate',
                'advances.amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('10 as type')
            );

        $paidAdvances = DB::table('advances')
            ->join('employees', 'advances.employee_id', '=', 'employees.id')
            ->join('safes', 'advances.safe_id', '=', 'safes.id')
            ->where('advances.paid_back', 1)
            ->select(
                'advances.id as docId',
                DB::raw("'empty' as docNumber"),
                'advances.date as docDate',
                'advances.amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('11 as type')
            );

        $safeBalance = DB::table('safe_balances') ->
        join('safes', 'safe_balances.safe_id', '=', 'safes.id')
            ->select('safe_balances.*' , 'safes.name as safe')
            -> where('safe_balances.safe_id' , $request -> safe_id) -> first();




        $isSearchByDate = 0 ;
        if($request -> safe_id != ""){
            $recipits = $recipits  -> where('recipits.safe_id' , '=' , $request -> safe_id);
            $boxes = $boxes -> where('box_recipits.safe_id' , '=' , $request -> safe_id);
            $catchs = $catchs -> where('catch_recipits.safe_id' , '=' , $request -> safe_id);
            $exchangeOut = $exchangeOut -> where('safe_balance_exchanges.from_safe_id' , '=' , $request -> safe_id);
            $exchangeIn = $exchangeIn -> where('safe_balance_exchanges.to_safe_id' , '=' , $request -> safe_id);
            $salaries = $salaries -> where('salaries.safe_id' , '=' , $request -> safe_id);
            $advances = $advances -> where('advances.safe_id' , '=' , $request -> safe_id);
            $paidAdvances = $paidAdvances -> where('advances.safe_id' , '=' , $request -> safe_id);

        }
        if ($request->has('isFromDate') && $request->filled('fromDate')) {
            $isSearchByDate = 1 ;
            $recipits = $recipits->whereDate('recipits.date', '>=', Carbon::parse($request->fromDate) );
            $boxes = $boxes->whereDate('box_recipits.date', '>=', Carbon::parse($request->fromDate) );
            $catchs = $catchs->whereDate('catch_recipits.date', '>=', Carbon::parse($request->fromDate) );
            $exchangeOut = $exchangeOut-> whereDate('safe_balance_exchanges.date', '>=', Carbon::parse($request->fromDate) );
            $exchangeIn = $exchangeIn-> whereDate('safe_balance_exchanges.date', '>=', Carbon::parse($request->fromDate) );

            $salaries = $salaries-> whereDate('salaries.created_at', '>=', Carbon::parse($request->fromDate) );
            $advances = $advances-> whereDate('advances.date', '>=', Carbon::parse($request->fromDate) );
            $paidAdvances = $paidAdvances-> whereDate('advances.date', '>=', Carbon::parse($request->fromDate) );

        }

        if ($request->has('isToDate') && $request->filled('toDate')) {
            $isSearchByDate = 1 ;
            $recipits = $recipits->whereDate('recipits.date', '<=', Carbon::parse($request->toDate) );
            $boxes = $boxes->whereDate('box_recipits.date', '<=', Carbon::parse($request->toDate) );
            $catchs = $catchs->whereDate('catch_recipits.date', '<=', Carbon::parse($request->toDate) );
            $exchangeOut = $exchangeOut-> whereDate('safe_balance_exchanges.date', '<=', Carbon::parse($request->toDate) );
            $exchangeIn = $exchangeIn-> whereDate('safe_balance_exchanges.date', '<=', Carbon::parse($request->toDate) );

            $salaries = $salaries-> whereDate('salaries.created_at', '<=', Carbon::parse($request->toDate) );
            $advances = $advances-> whereDate('advances.date', '<=', Carbon::parse($request->toDate) );

            $paidAdvances = $paidAdvances-> whereDate('advances.date', '<=', Carbon::parse($request->toDate) );
        }
        $data = $recipits -> unionAll($boxes) -> unionAll( $catchs) -> unionAll($exchangeOut) -> unionAll($exchangeIn) -> unionAll($advances) -> unionAll($salaries) -> unionAll($paidAdvances) ;
        $data = $data->orderBy('docDate', 'ASC')->get();

        $fromDate = $request -> fromDate ;
        $toDate = $request -> toDate ;
        $safe = Safe::find($request -> safe_id);
        $oBalance = SafeBalance::where('safe_id' , '=' , $request -> safe_id) -> first();



        if($request -> reportType == 1){
            return view('admin.Reports.safeMovementReport', compact('data' ,
                'isSearchByDate' , 'fromDate' , 'toDate' , 'safe' , 'safeBalance'));

        } else {
            $result = [];

            foreach ($data as $row) {
                $amount = (float)$row->amount;
                $safe = $row->safe;
                $type = (int)$row->type;

                // Initialize if safe not set
                if (!isset($result[$safe])) {
                    $result[$safe] = [
                        'safe' => $safe,
                        'outMoney' => 0,
                        'inMoney' => 0,
                        'opening_balance' => 0
                    ];
                }

                // Determine category
                if (in_array($type, [0, 3, 4, 7, 9, 10])) {
                    $result[$safe]['outMoney'] += $amount;
                } elseif (in_array($type, [1, 2, 8, 11])) {
                    $result[$safe]['inMoney'] += $amount;
                }
                if (!empty($oBalance) && isset($oBalance->opening_balance)) {


                    $result[$safe]['opening_balance'] = (float) $oBalance->opening_balance;
                } else {
                    $result[$safe]['opening_balance'] =  0 ;
                }
            }

            $totals = array_values($result);

            //  return $totals ;




            return view('admin.Reports.safeMovementReportTotal', compact('safe',
                'totals',
                'isSearchByDate' , 'fromDate' , 'toDate' , 'safeBalance'));
        }




    }

    public function safeMovementReportPrint(Request $request)
    {
        /* ================== Base Queries ================== */

        $recipits = DB::table('recipits')
            ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
            ->join('safes', 'recipits.safe_id', '=', 'safes.id')
            ->select(
                'recipits.id as docId',
                'recipits.bill_number as docNumber',
                'recipits.date as docDate',
                'recipits.amount as amount',
                'clients.name as client',
                'safes.name as safe',
                DB::raw('0 as type')
            )->where('recipits.state', 1);

        $boxes = DB::table('box_recipits')
            ->join('recipit_types', 'box_recipits.recipit_type', '=', 'recipit_types.id')
            ->join('safes', 'box_recipits.safe_id', '=', 'safes.id')
            ->select(
                'box_recipits.id as docId',
                'box_recipits.bill_number as docNumber',
                'box_recipits.date as docDate',
                'box_recipits.amount as amount',
                'recipit_types.name as client',
                'safes.name as safe',
                DB::raw('4 as type')
            )->where('box_recipits.state', 1);

        $catchs = DB::table('catch_recipits')
            ->join('clients', 'catch_recipits.client_id', '=', 'clients.id')
            ->join('safes', 'catch_recipits.safe_id', '=', 'safes.id')
            ->select(
                'catch_recipits.id as docId',
                'catch_recipits.bill_number as docNumber',
                'catch_recipits.date as docDate',
                'catch_recipits.amount as amount',
                'clients.name as client',
                'safes.name as safe',
                DB::raw("CASE WHEN catch_recipits.loan_id = 0 THEN 1 ELSE 2 END as type")
            )->where('catch_recipits.state', 1);

        $exchangeOut = DB::table('safe_balance_exchanges')
            ->join('safes', 'safe_balance_exchanges.from_safe_id','=', 'safes.id')
            ->select(
                'safe_balance_exchanges.id as docId',
                'safe_balance_exchanges.bill_number as docNumber',
                'safe_balance_exchanges.date as docDate',
                'safe_balance_exchanges.balance as amount',
                DB::raw("'empty' as client"),
                'safes.name as safe',
                DB::raw('7 as type')
            );

        $exchangeIn = DB::table('safe_balance_exchanges')
            ->join('safes', 'safe_balance_exchanges.to_safe_id','=', 'safes.id')
            ->select(
                'safe_balance_exchanges.id as docId',
                'safe_balance_exchanges.bill_number as docNumber',
                'safe_balance_exchanges.date as docDate',
                'safe_balance_exchanges.balance as amount',
                DB::raw("'empty' as client"),
                'safes.name as safe',
                DB::raw('8 as type')
            );

        $salaries = DB::table('salaries')
            ->join('safes', 'salaries.safe_id', '=', 'safes.id')
            ->join('employees', 'salaries.employee_id', '=', 'employees.id')
            ->select(
                'salaries.id as docId',
                DB::raw("'empty' as docNumber"),
                'salaries.created_at as docDate',
                'salaries.total_amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('9 as type')
            );

        $advances = DB::table('advances')
            ->join('safes', 'advances.safe_id', '=', 'safes.id')
            ->join('employees', 'advances.employee_id', '=', 'employees.id')
            ->select(
                'advances.id as docId',
                DB::raw("'empty' as docNumber"),
                'advances.date as docDate',
                'advances.amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('10 as type')
            );

        $paidAdvances = DB::table('advances')
            ->join('employees', 'advances.employee_id', '=', 'employees.id')
            ->join('safes', 'advances.safe_id', '=', 'safes.id')
            ->where('advances.paid_back', 1)
            ->select(
                'advances.id as docId',
                DB::raw("'empty' as docNumber"),
                'advances.date as docDate',
                'advances.amount as amount',
                'employees.name as client',
                'safes.name as safe',
                DB::raw('11 as type')
            );

        /* ================== Filters ================== */

        if ($request->filled('safe_id')) {
            $recipits->where('recipits.safe_id', $request->safe_id);
            $boxes->where('box_recipits.safe_id', $request->safe_id);
            $catchs->where('catch_recipits.safe_id', $request->safe_id);
            $exchangeOut->where('safe_balance_exchanges.from_safe_id', $request->safe_id);
            $exchangeIn->where('safe_balance_exchanges.to_safe_id', $request->safe_id);
            $salaries->where('salaries.safe_id', $request->safe_id);
            $advances->where('advances.safe_id', $request->safe_id);
            $paidAdvances->where('advances.safe_id', $request->safe_id);
        }

        if ($request->filled('isFromDate') && $request->filled('fromDate')) {
            $from = Carbon::parse($request->fromDate);

            $recipits->whereDate('recipits.date', '>=', $from);
            $boxes->whereDate('box_recipits.date', '>=', $from);
            $catchs->whereDate('catch_recipits.date', '>=', $from);
            $exchangeOut->whereDate('safe_balance_exchanges.date', '>=', $from);
            $exchangeIn->whereDate('safe_balance_exchanges.date', '>=', $from);
            $salaries->whereDate('salaries.created_at', '>=', $from);
            $advances->whereDate('advances.date', '>=', $from);
            $paidAdvances->whereDate('advances.date', '>=', $from);
        }

        if ($request->filled('isToDate') && $request->filled('toDate')) {
            $to = Carbon::parse($request->toDate);

            $recipits->whereDate('recipits.date', '<=', $to);
            $boxes->whereDate('box_recipits.date', '<=', $to);
            $catchs->whereDate('catch_recipits.date', '<=', $to);
            $exchangeOut->whereDate('safe_balance_exchanges.date', '<=', $to);
            $exchangeIn->whereDate('safe_balance_exchanges.date', '<=', $to);
            $salaries->whereDate('salaries.created_at', '<=', $to);
            $advances->whereDate('advances.date', '<=', $to);
            $paidAdvances->whereDate('advances.date', '<=', $to);
        }

        /* ================== Collect Data ================== */

        $data = $recipits
            ->unionAll($boxes)
            ->unionAll($catchs)
            ->unionAll($exchangeOut)
            ->unionAll($exchangeIn)
            ->unionAll($advances)
            ->unionAll($salaries)
            ->unionAll($paidAdvances)
            ->orderBy('docDate', 'ASC')
            ->get();

        $safe     = Safe::find($request->safe_id);
        $fromDate = $request->fromDate;
        $toDate   = $request->toDate;
        $oBalance = SafeBalance::where('safe_id', $request->safe_id)->first();

        /* ================== TOTAL REPORT ================== */

        if ($request->reportType == 0) {

            $result = [];

            foreach ($data as $row) {

                $safeName = $row->safe;
                $amount   = (float)$row->amount;
                $type     = (int)$row->type;

                if (!isset($result[$safeName])) {
                    $result[$safeName] = [
                        'safe' => $safeName,
                        'outMoney' => 0,
                        'inMoney' => 0,
                        'opening_balance' => (float)($oBalance->opening_balance ?? 0)
                    ];
                }

                if (in_array($type, [0,3,4,7,9,10])) {
                    $result[$safeName]['outMoney'] += $amount;
                } elseif (in_array($type, [1,2,8,11])) {
                    $result[$safeName]['inMoney'] += $amount;
                }
            }

            $totals = array_values($result);

            return view('admin.Reports.safeMovementReportTotalPrint',
                compact('totals','safe','fromDate','toDate')
            );
        }

        /* ================== DETAILED REPORT ================== */

        return view('admin.Reports.safeMovementReportPrint',
            compact('data','safe','fromDate','toDate','oBalance')
        );
    }


    public function supplierAccountSettlementSearch()
    {
        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];

        $suppliers = Client::where('type' , '<>' , 0 ) -> get();
        $weaklyMeals = DB::table('weakly_milk_meals')
            -> select(
                'weakly_milk_meals.id',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            ) -> where('weakly_milk_meals.state' , '=' , 1) -> get();

        foreach ($weaklyMeals as $meal) {
            $meal->from_day_name_ar = $dayTranslations[$meal->from_day_name_en] ?? $meal->from_day_name_en;
            $meal->to_day_name_ar = $dayTranslations[$meal->to_day_name_en] ?? $meal->to_day_name_en;
        }

        return view('admin.Reports.supplierSettlementSearch', compact('suppliers'  , 'weaklyMeals'));

    }

    public function supplierAccountSettlementReport(Request $request)
    {

        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];
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
                DB::raw('MAX(CASE WHEN daily_milk_meals.type = 1 THEN daily_milk_meals.code END) as evening_meal'),

                DB::raw("DAYNAME(MIN(daily_milk_meals.date)) as day_name"),

            )
            ->where('daily_milk_meals.weakly_meal_id', '=', $request -> meal_id)
            ->where('daily_milk_meals.supplier_id', '=', $request -> supplier_id)
            ->groupBy(
                'clients.name',
                'weakly_milk_meals.code',
                'weakly_milk_meals.start_date',
                'weakly_milk_meals.end_date'
            )
            ->orderBy('meal_id', 'asc')
            ->get();

        $wmeal = DB::table('weakly_milk_meals')
            -> select(
                'weakly_milk_meals.id',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            ) -> where('weakly_milk_meals.id' , '=' , $request -> meal_id)

            -> get() -> first();

        if($wmeal){
            $wmeal->from_day_name_ar = $dayTranslations[$wmeal->from_day_name_en] ?? $wmeal->from_day_name_en;
            $wmeal->to_day_name_ar = $dayTranslations[$wmeal->to_day_name_en] ?? $wmeal->to_day_name_en;
        }

        $supplier_id = $request -> supplier_id ;

        $totalMorning = $meals->sum('morning_total');
        $totalEvening = $meals->sum('evening_total');

        $amount = $totalMorning + $totalEvening;

        $suppliers = Client::where('type' , '<>' , 0 ) -> get();
        $safes = Safe::all();
        return view('admin.Reports.supplierSettlementReport', compact('meals' , 'wmeal' , 'supplier_id' , 'amount' , 'suppliers' , 'safes'));

    }

    public function dailyMealsSearch()
    {
        $dayTranslations = [
            'Sunday'    => 'الأحد',
            'Monday'    => 'الإثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];
        //dailyMealsSearch
        $suppliers = Client::where('type' , '<>' , 0 ) -> get();
        $meals = DB::table('weakly_milk_meals')
            -> select(
                'weakly_milk_meals.id',
                DB::raw('DAYNAME(weakly_milk_meals.start_date) as from_day_name_en'),
                DB::raw('DAYNAME(weakly_milk_meals.end_date) as to_day_name_en'),
                DB::raw('DATE(weakly_milk_meals.start_date) as start_date'),
                DB::raw('DATE(weakly_milk_meals.end_date) as end_date'),

            )

            -> get() ;



            foreach ($meals as $meal) {
                $meal->from_day_name_ar = $dayTranslations[$meal->from_day_name_en] ?? $meal->from_day_name_en;
                $meal->to_day_name_ar = $dayTranslations[$meal->to_day_name_en] ?? $meal->to_day_name_en;
            }



        return view('admin.Reports.dailyMealsSearch', compact('suppliers'  , 'meals'));

    }
}
