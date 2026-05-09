<?php

namespace App\Http\Controllers;

use App\Models\BoxRecipit;
use App\Models\RecipitType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecipitTypeController extends Controller
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

        if (!Gate::allows('page-access', [3, 'view'])) {
            abort(403);
        }

        $types = RecipitType::all();
        return view('admin.ExpensesType.index', compact('types'));
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
                    'code' => 'required|unique:recipit_types,code',
                    'name' => 'required|unique:recipit_types,name',
                ]
                , [
                    'code.required' => __('main.code_required'),
                    'code.unique'   => __('main.code_unique'),
                    'name.required' => __('main.name_required'),
                    'name.unique'   => __('main.name_unique'),
                ]
            );
            RecipitType::create([
                'name' => $request -> name,
                'code' => $request -> code,
                'description' => $request -> description ?? "",
                'user_ins' => Auth::user() -> id,
                'user_upd' => 0
            ]);
            return  redirect() -> route('expenses_types') -> with('success', __('main.saved'));

        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RecipitType  $recipitType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = RecipitType::find($id);
        echo json_encode($type);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RecipitType  $recipitType
     * @return \Illuminate\Http\Response
     */
    public function edit(RecipitType $recipitType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RecipitType  $recipitType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('recipit_types', 'code')->ignore($request -> id),
            ],
            'name' => [
                'required',
                Rule::unique('recipit_types', 'name')->ignore($request -> id),
            ],
        ], [
            'code.required' => __('main.code_required'),
            'code.unique'   => __('main.code_unique'),
            'name.required' => __('main.name_required'),
            'name.unique'   => __('main.name_unique'),
        ]);
        $type = RecipitType::find($request -> id);
        if($type){
            $type -> update([
                'name' => $request -> name,
                'code' => $request -> code,
                'description' => $request -> description ?? "",
                'user_upd' => Auth::user() -> id,
            ]);
            return  redirect() -> route('expenses_types') -> with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RecipitType  $recipitType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = RecipitType::find($id);
        if($type){
            $boxs = BoxRecipit::where('recipit_type' , $id) -> get();
            if(count($boxs) > 0){
                return  redirect() -> route('expenses_types') -> with('warning', __('main.can_not_delete'));

            } else {
                $type -> delete();
                return  redirect() -> route('expenses_types') -> with('success', __('main.deleted'));
            }

        }
    }
}
