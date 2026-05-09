<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

        if (!Gate::allows('page-access', [23, 'view'])) {
            abort(403);
        }

        $settings = Settings::all();
        if(count($settings )){
            $setting = $settings[0];
        } else {
            $setting = null ;
        }
        return view('admin.Settings.index' , compact('setting'));
    }

    public function store(Request $request){
        if($request -> id == 0){
            Settings::create([
                'buffalo_milk_price'=> $request -> buffalo_milk_price ?? 0,
                'bovine_milk_price' => $request -> bovine_milk_price ?? 0,
                'morning_bonus_time' => Carbon::parse($request -> morning_bonus_time)  ,
                'evening_bonus_time' => Carbon::parse($request -> evening_bonus_time ) ,
                'bonus_value' => $request -> bonus_value ?? 0,
                'protein_price' => $request -> protein_price ?? 0 ,
                'cream_price' => $request -> cream_price ?? 0 ,
                'cheese_price' => $request -> cheese_price ?? 0 ,
                'white_cheese_price' => $request -> white_cheese_price ?? 0 ,
                'user_ins' => Auth::user()-> id ,
                'user_upd'=> 0
            ]);
            return redirect()->route('settings')->with('success' ,  __('main.saved'));


        } else {
            $setting = Settings::find($request -> id);
            if($setting){
                $setting -> update([
                    'buffalo_milk_price'=> $request -> buffalo_milk_price ?? 0,
                    'bovine_milk_price' => $request -> bovine_milk_price ?? 0,
                    'morning_bonus_time' => Carbon::parse($request -> morning_bonus_time)  ,
                    'evening_bonus_time' => Carbon::parse($request -> evening_bonus_time ) ,
                    'bonus_value' => $request -> bonus_value ?? 0,
                    'protein_price' => $request -> protein_price  ,
                    'cream_price' => $request -> cream_price  ?? 0,
                    'cheese_price' => $request -> cheese_price ?? 0 ,
                    'white_cheese_price' => $request -> white_cheese_price ?? 0 ,
                    'user_upd'=> Auth::user()-> id
                ]);
            }
            return redirect()->route('settings')->with('success' ,  __('main.updated'));


        }
    }

    public function show(){
        $settings = Settings::all() -> first();
        echo  json_encode($settings);
        exit();
    }
}
