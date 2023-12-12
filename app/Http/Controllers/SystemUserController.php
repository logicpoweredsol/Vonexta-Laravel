<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Models\UserOrganization;

class SystemUserController extends Controller
{
    //
    public function index(){
        //get users of logged in user's organization...
        $user = Auth::user();
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin')){
            abort(403);
        }
        $users = $organization->users->except($user->id);
        return view('administration.users.index',compact('users'));
    }

    public function add(){
        $user = Auth::user();
        //|| $user->hasPermissionTo('add administration users')
        if(!$user->hasRole('admin')){
            abort(403);
        }
        $permissions = Permission::all();
        return view("administration.users.add", compact("permissions"));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",
            "email" => "required|email|unique:App\Models\User,email",
            "password" => "required",
            "role" => "required",
            "permissions" => "required|array"
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);
        $role = Role::findByName($request->role);
        $user->assignRole($role);
        if(count($request->permissions)){
            //assign all the selected permissions to the user...
            $user->givePermissionTo($request->permissions);
        }
        //add this user into the same organization as of the logged in user...
        $organization = Auth::user()->organizations->first();
        $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);
        $request->session()->flash('msg', 'User added successfully');
        return redirect()->route("administration.users");
    }

    public function view(User $user){
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $userRoles = $user->getRoleNames();
        $permissions = Permission::all();
        $userPermissions = $user->getAllPermissions()->pluck("name")->toArray();
        return view('administration.users.edit', compact("roles", "userRoles", "permissions", "userPermissions", "user"));
    }

    public function edit(User $user, Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",
            "email" => [
                "required",
                "email",
//                "unique:App\Models\User,email",
                Rule::unique('users')->ignore($user->id),
            ],
//            "password" => "sometimes|required|between:8,32",
            "role" => "required",
//            "permissions" => "required|array"
        ]);

        if($validator->fails()){
//            dd($validator->errors());
            return redirect()->back()->withInput()->withErrors($validator);
        }
//        $user = User::findOrFail($user->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->has('password') && null!== $request->password){
            if(!strlen($request->password)>=8 && !strlen($request->password)<=32){
                return redirect()->back()->withInput()->withErrors(['password' => "Password must be between 8 and 32 characters"]);
            }
            $user->password = Hash::make($request->password);
        }
        $user->active = $request->active;
        $user->syncRoles([$request->role]);
        //revoke all permissions by default... if assigned any permissions they will be set below...
        $user->revokePermissionTo($user->getAllPermissions());
        if($request->has('permissions') && null!==$request->permissions){
            if(!is_array($request->permissions)){
                return redirect()->back()->withInput()->withErrors(['permissions' => "Error in permission assignment"]);
            }
            $user->revokePermissionTo($user->getAllPermissions());
            if(count($request->permissions)){
                //assign all the selected permissions to the user...
                $user->givePermissionTo($request->permissions);
            }
        }
        $user->save();
        $request->session()->flash('msg', 'User updated successfully');
        return redirect()->route("administration.users.view", $user->id);
    }

    public function delete(User $user){
        $organization = $user->organizations->first();
        $adminUsers = $organization->users->filter(function ($user){
            return $user->hasRole('admin');
        })->count();
        if($adminUsers<=1){
            return response()->json(["status" => false,'error' => "Cannot delete only admin of the organization."],403);
        }
        $user->organizations()->detach();
        $user->delete();
        return response()->json(["status" => true, "message" => "User deleted successfully."], 200);
    }
}
