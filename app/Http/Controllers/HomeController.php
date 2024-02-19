<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userAgent;
class HomeController extends Controller
{
    //

    
    function index(){
        if(auth()->user()->hasRole('superadmin')){
            return redirect('/dashboard');
        }else{

            if(auth()->user()->hasRole('admin')){
                $userAgents = userAgent::with('user_detail')->get();
            }else{
                $userAgents = userAgent::where('org_user_id',auth()->user()->id)->with('user_detail')->get();
            }

           
        }

        // dd( $userAgents);
        return view('home' ,compact('userAgents'));
        
    }
}
