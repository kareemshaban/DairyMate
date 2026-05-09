<?php

namespace App\Http\Controllers;

use App\Models\CatchRecipit;
use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\Loan;
use App\Models\Safe;
use App\Models\SafeBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
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
        $docs = DB::table('loans')
            ->join('clients', 'loans.supplier_id', '=', 'clients.id')
            ->join('safes', 'loans.safe_id', '=', 'safes.id')
            -> select('loans.*', 'clients.name' , 'safes.name as safe')
            ->get();

        $suppliers = Client::where('type' ,'<>' , 0) -> get();

        $safes = Safe::all();
        return view('admin.Loans.index', compact('docs' , 'suppliers' , 'safes'));
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
                    'installment_amount' => 'required',
                    'installment_count' => 'required',

                ]
                , [
                    'bill_number.required' => __('main.bill_number_required'),
                    'supplier_id.required' => __('main.supplier_required'),
                    'bill_number.unique'   => __('main.bill_number_unique'),
                    'amount.required' => __('main.amount_required'),
                    'safe_id.required' => __('main.safe_id_required'),
                    'installment_amount.required' => __('main.installment_amount_required'),
                    'installment_count.required' => __('main.installment_count_required'),
                ]
            );
            Loan::create([
                'date' => Carbon::parse( $request -> date),
                'bill_number' => $request -> bill_number,
                'supplier_id' => $request -> supplier_id,
                'safe_id' => $request -> safe_id,
                'amount' => $request -> amount,
                'installment_amount' => $request -> installment_amount,
                'installment_count' => $request -> installment_count,
                'start_date' => Carbon::parse($request -> start_date),
                'remaining_installments' => $request -> installment_count ,
                'paid_installments' => 0,
                'notes' => $request -> notes ?? "",
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0,
            ]);

            $this -> updateClientAccount($request -> amount,  0 , $request -> supplier_id );
            $this -> updateSafeBalance(0 , $request -> amount , $request -> safe_id );

            return redirect()->route('loans') -> with('success', __('main.saved'));
        } else{
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = Loan::find($id);
        echo json_encode($doc);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $doc = Loan::find($request -> id);
        if($doc != null){

            if($doc -> paid_installments == 0 ){
                $oldAmount = $doc -> amount;
                $doc -> update([
                    'date' => Carbon::parse( $request -> date),
                    'bill_number' => $request -> bill_number,
                    'safe_id' => $request -> safe_id,
                    'amount' => $request -> amount,
                    'installment_amount' => $request -> installment_amount,
                    'installment_count' => $request -> installment_count,
                    'start_date' => Carbon::parse($request -> start_date),
                    'notes' => $request -> notes ?? "",
                    'user_ins' => Auth::user() -> id,
                    'user_upd' => 0,
                ]);
                $netAmount = $request -> amount - $oldAmount;

                $this -> updateClientAccount($netAmount , 0  , $doc -> supplier_id );
                $this -> updateSafeBalance(0 , $netAmount , $doc -> safe_id );
                return redirect()->route('loans') -> with('success', __('main.updated'));
            } else {
                return redirect()->route('loans') -> with('warning', __('main.can_not_edit_paid_loans'));
            }


        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $doc = Loan::find($id);
        if($doc != null){
            $oldAmount = $doc -> amount;

            $doc -> delete();
            $netAmount = 0 - $oldAmount;

            $this -> updateClientAccount($netAmount ,  0, $doc -> client_id );
            $this -> updateSafeBalance(0 , $netAmount , $doc -> safe_id );

            return redirect()->route('loans') -> with('success', __('main.deleted'));

        }
    }

    public function getCode(){
        $docs = Loan::all();
        $dId = 0;
        if (count($docs) > 0) {
            $dId = count($docs)  + 1;
        } else {
            $dId = 1;
        }

        $padded = str_pad($dId, 4, '0', STR_PAD_LEFT); // Result: "0001"    }
        $code =  'SL' . $padded;
        echo json_encode($code);
        exit();
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

    public function pay(Request $request)
    {
        $loan = Loan::find($request -> loan_id);

       $id = CatchRecipit::create([
            'date' => Carbon::parse($request -> date),
            'bill_number' => $request -> bill_number,
            'client_id' => $loan -> supplier_id,
            'amount' => $request -> amount,
            'payment_method' => $request -> payment_method,
            'safe_id' => $request -> safe_id,
            'notes' => $request -> notes ?? "",
            'loan_id' => $request -> loan_id ,
            'user_ins' => Auth::user() -> id,
            'user_upd' => 0,
        ]) -> id;

       if($id > 0){

           $loan->update([
               'remaining_installments' => $loan->remaining_installments - 1,
               'paid_installments' => $loan->paid_installments + 1,
           ]);
           $this -> updateClientAccount(0, $request -> amount  , $loan -> supplier_id );
           $this -> updateSafeBalance($request -> amount , 0, $loan -> safe_id );

       }
        return redirect()->route('loans') -> with('success', __('main.saved'));


    }
}
