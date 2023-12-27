<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index(){

        // if(auth()->user()->hasRole('superadmin')){
        //     return view('dashboard.superadmin');
        // }
        
        return view('dashboard.index');
    }
}
