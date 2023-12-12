<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    //

    public function index()  {
        $user = Auth::user();
        if(!$user->hasRole('superadmin')){
            abort(403);
        }

        $superadminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'superadmin');
        })->get();
        return view('superadmin.index',compact('superadminUsers'));
    }


    public function add()  {
        return view('superadmin.add');
    }


    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:50",
                "email" => "required|email|unique:users,email",
                "password" => "required",
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
    
            $role = Role::findByName('superadmin');
            $user->assignRole($role);
    
            if ($request->has('permissions')) {
                $allPermissions = Permission::pluck('name')->toArray();
                $user->givePermissionTo($allPermissions);
            }
    
            return redirect('accounts')->with('success', 'Successfully account has been created');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit($userID)  {
        $User = User::find($userID);
        return view('superadmin.edit',compact('User'));
    }



    public function update(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:50",
                "email" => [
                    "required",
                    "email",
                    Rule::unique('users')->ignore($request->id),
                ],

            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $update_user = User::where('id',$request->id)->first();
            $update_user->name = $request->name;
            $update_user->email  = $request->email;
            $update_user->active  = $request->active;

            if($request->password != "" && $request->password !=null){
                $update_user->password = Hash::make($request->password);
            }
            $update_user->save();
    
            return redirect('accounts')->with('success', 'Successfully account has been Updated');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }








  


}
