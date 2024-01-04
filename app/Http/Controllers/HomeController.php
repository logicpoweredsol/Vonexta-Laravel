<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userAgent;
class HomeController extends Controller
{
    //
    function index(){
        if(auth()->user()->hasRole('superadmin')){
            $userAgents = '';
            // return view('dashboard.superadmin');
        }else{
            $userAgents = userAgent::with('user_detail')->get();
        }
        return view('home' ,compact('userAgents'));
        
    }
}
