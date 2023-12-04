<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CampaignController extends Controller
{
    public function index($service){
        //get users of logged in user's organization...
        $user = Auth::user();
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin') || !$user->hasPermissionTo('view campaigns')){
            abort(403);
        }
        return view('dialer.campaigns.index',compact('service'));
    }

    public function add(){

    }
}
