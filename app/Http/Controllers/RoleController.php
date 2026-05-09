<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
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
    public function index()
    {

        if (!Gate::allows('page-access', [21, 'view'])) {
            abort(403);
        }

        $roles = Role::all();
        return view('admin.Roles.index', compact('roles'));
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
            Role::create([
                'name' => $request -> name,
                'user_ins' => Auth::user()->id,
            ]);
            return redirect()->route('roles')->with('success', __('main.saved'));
        } else {
            return $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        echo json_encode($role);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $role = Role::find($request -> id);
        if($role){
            $role -> update([
                'name' => $request -> name,
                'user_upd' => Auth::user()->id,
            ]);
            return redirect()->route('roles')->with('success', __('main.updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role){
            $role -> delete();
            return redirect()->route('roles')->with('success', __('main.deleted'));
        }
    }
}
