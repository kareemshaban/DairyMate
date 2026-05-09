<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\DailyMilkMeal;
use App\Models\Recipit;
use App\Models\Safe;
use App\Models\SafeBalance;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\CarDailyMeal;


class RecipitController extends Controller
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
        if (!Gate::allows('page-access', [15, 'view'])) {
            abort(403);
        }
        if($isAll == 1 ) {
            $docs = DB::table('recipits')
                ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
                ->join('safes', 'recipits.safe_id', '=', 'safes.id')
                ->select('recipits.*', 'clients.name', 'safes.name as safe')
                ->get();
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
            $docs = DB::table('recipits')
                ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
                ->join('safes', 'recipits.safe_id', '=', 'safes.id')
                ->select('recipits.*', 'clients.name', 'safes.name as safe')
                ->whereDate('recipits.date', '>=', $startOfWeek->toDateString())
                ->whereDate('recipits.date', '<=', $endOfWeek->toDateString())
                ->get();
        }
        $suppliers = Client::where('type' ,'<>' , 0) -> get();
        $safes = Safe::all();
        return view('admin.Recipits.index', compact('docs' , 'suppliers' , 'safes' , 'is_all'));
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
                    'bill_number' => 'required|unique:recipits,bill_number',
                    'supplier_id' => 'required',
                    'amount' => 'required',
                    'safe_id' => 'required',
                    'payment_method' => 'required',
                ]
                , [
                    'bill_number.required' => __('main.bill_number_required'),
                    'supplier_id.required' => __('main.supplier_required'),
                    'bill_number.unique'   => __('main.bill_number_unique'),
                    'amount.required' => __('main.amount_required'),
                    'safe_id.required' => __('main.safe_id_required'),
                    'payment_method.required' => __('main.payment_method_required'),
                ]
            );
           $id = Recipit::create([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'supplier_id' => $request -> supplier_id,
                'amount' => $request -> amount,
                'payment_method' => $request -> payment_method,
                'safe_id' => $request -> safe_id,
                'notes' => $request -> notes ?? "",
                'isPayment' => 0 ,
                'state' => 0 ,
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]) -> id;

         //   $this -> updateClientAccount($request -> amount , 0 , $request -> supplier_id );
          //  $this -> updateSafeBalance(0 , $request -> amount , $request -> safe_id);


            if ($request->has('isPost')) {
                return $this -> post($id);


            } else {
            return redirect() -> route('recipits') -> with('success', __('main.saved'));

            }
        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipit  $recipit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = Recipit::find($id);
       echo json_encode($doc);
        exit();
    }

    public function print($id)
    {
        $doc = DB::table('recipits')
            ->join('clients', 'recipits.supplier_id', '=', 'clients.id')
            ->join('safes', 'recipits.safe_id', '=', 'safes.id')
            ->select('recipits.*', 'clients.name', 'safes.name as safe')
            -> where('recipits.id', $id)
            ->first();

        return view('admin.Recipits.print' , compact('doc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recipit  $recipit
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipit $recipit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipit  $recipit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = Recipit::find($request -> id);
        if($doc != null){
            $oldAmount = $doc -> amount;
           // $oldSupplierId = $doc -> supplier_id;
          //  $oldSafeId = $doc -> safe_id;
            $doc -> update([
                'date' => Carbon::parse($request -> date),
                'bill_number' => $request -> bill_number,
                'supplier_id' => $request -> supplier_id,
                'amount' => $request -> amount,
                'payment_method' => $request -> payment_method,
                'safe_id' => $request -> safe_id,
                'notes' => $request -> notes ?? "",
                'isPayment' => 0 ,
                'state' => 0 ,
                'user_upd' => Auth::user() -> id
            ]);
             $netAmount = $request -> amount - $oldAmount;

          //  $this -> updateClientAccount($netAmount , 0 , $request -> supplier_id );
           // $this -> updateSafeBalance(0 , $netAmount , $request -> safe_id);
        }
        return redirect()->route('recipits') -> with('success', __('main.updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipit  $recipit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = Recipit::find($id);
        if($doc != null){
            $oldAmount = $doc -> amount;

            $netAmount = 0 - $oldAmount;
          //  $this -> updateClientAccount($netAmount , 0 , $doc -> supplier_id );
         //   $this -> updateSafeBalance(0 , $netAmount , $doc->safe_id );
            $doc -> delete();
            return redirect()->route('recipits') -> with('success', __('main.deleted'));

        }
    }


    public function getCode(){
        $docs = Recipit::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'RB' . $padded;
        $defaultSafe = Safe::where('isDefault' , '=' , 1) -> first();

        return response()->json([
            'code' => $code,
            'safe' => $defaultSafe
        ]);
    }


    public function updateClientAccount($debit , $credit , $client_id)
    {
        $account = ClientAccount::where('client_id' , $client_id)->first();
        if($account){
            $account->update([
                'client_id' => $client_id,
                'debit' => $account -> debit  +  $debit,
                'credit' => $account -> credit  + $credit,
                'balance' => $account -> balance + $credit - $debit,
                'user_ins' => Auth::user() -> id,
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
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
        }
    }

    public function updateSafeBalance($income , $outcome , $safe_id)
    {
        $balance = SafeBalance::where('safe_id' , $safe_id)->first();
        if($balance){
            $balance->update([
                'income' => $balance -> income + $income ,
                'outcome' => $balance -> outcome + $outcome,
                'balance' => $balance -> balance + $income - $outcome,
            ]);
        }
    }


    public function makePayment(Request $request)
    {

        try {

            $docs = Recipit::all();
            $dId = 0;
            if (count($docs) > 0) {
                $dId = count($docs) + 1;
            } else {
                $dId = 1;
            }

            $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
            $code = 'RB' . $padded;
         //////////////////////////////create Recipit Doc /////////////// pass
         Recipit::create([
                'date' => Carbon::parse($request->date),
                'bill_number' => $code,
                'supplier_id' => $request->supplier_id,
                'amount' => $request->amount,
                'payment_method' => 0,
                'safe_id' => $request->safe_id,
                'notes' => $request->notes ?? "",
                'isPayment' => 1 ,
                'state' => 1 ,
                'user_ins' => Auth::user()->id,
                'user_upd' => 0,
            ]);

            //////////////////////////////update client account /////////////// pass
            $this->updateClientAccount($request->amount, 0, $request->supplier_id);


            //////////////////////////////update Safe account /////////////// pass
            $this->updateSafeBalance(0, $request->amount, $request->safe_id);


            //then i need to handle the dailymeals to pay

           if($request -> car == 0){
            $meals = DailyMilkMeal::where('weakly_meal_id', '=', $request->wid)
                ->where('supplier_id', '=', $request->supplier_id)
                ->where('isPaid', '=', 0)
                ->get();    
           } else {
               $meals = CarDailyMeal::where('weakly_meal_id', '=', $request->wid)
                ->where('member_id', '=', $request->supplier_id)
                ->where('isPaid', '=', 0)
                ->get();
           }
           

            $amount = $request->amount;

            foreach ($meals as $meal) {
                if ($amount <= 0) {
                    break;
                }

                $remaining = $meal->total - $meal->paid;

                if ($amount >= $remaining) {
                    $meal->update([
                        'paid' => $meal->total,
                        'isPaid' => 1
                    ]);
                    $amount -= $remaining; // ✅ only subtract the remaining unpaid part
                } else {
                    $meal->update([
                        'paid' => $meal->paid + $amount,
                        'isPaid' => 0
                    ]);
                    $amount = 0;
                }


            }
            return response()->json([
                'status' => 'success',
                'message' => 'تم التسديد بنجاح'
            ]);

        } catch (QueryException $err){
            return response()->json([
                'status' => 'error',
                'message' => 'حدثت مشكلة اثناء اتمام العملية',
                'debug' => $err->getMessage()
            ]);
        }
    }

    public function post($id)
    {
        $doc = Recipit::find($id);
        if($doc){
            $doc->update([
                'state' => 1
            ]);

            $this -> updateClientAccount($doc -> amount , 0 , $doc -> supplier_id );
            $this -> updateSafeBalance(0 , $doc -> amount , $doc -> safe_id);

            return redirect()->route('recipits') -> with('success', __('main.posted'));
        }
    }
}
