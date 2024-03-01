<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userAgent;

class DashboardController extends Controller
{
    //
    public function index(){
        if(auth()->user()->hasRole('superadmin')){
            $userAgents = '';
            // return view('dashboard.superadmin');
        }else{
            $userAgents = userAgent::with('user_detail')->get();
        }
        return view('dashboard.index' ,compact('userAgents'));
    }

    public function customattributes(Request $request){
        return view('customattributes.index');
    }

}
