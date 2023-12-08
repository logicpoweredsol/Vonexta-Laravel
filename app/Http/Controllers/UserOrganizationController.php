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
use App\Models\UserOrganization;
use App\Models\Organization;



class UserOrganizationController extends Controller
{
    //

    public function get_org_user(Request $request){

       
        $UserOrganization = UserOrganization::with('organization_user')->where('organization_id',$request->organization_id)->get();
        return $UserOrganization;
    }


    public function store(Request $request){
        $tab = 'Organization-user';    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);

         // dd($request->role);
         $role = Role::findByName($request->role);

         $user->assignRole($role);
         if(count($request->permissions)){
             //assign all the selected permissions to the user...
             $user->givePermissionTo($request->permissions);
         }
 
         //add this user into the same organization as of the logged in user...
         $organization = Organization::where('id',$request->organizations_id)->first();
 
         $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);
         return redirect()->back()->with('success', 'User added successfully')->with('tab', $tab);


        
    }

    public function edit(Request $request){
        $user = User::find($request->user_id);
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $userRoles = $user->getRoleNames();
        $permissions = Permission::all();
        $userPermissions = $user->getAllPermissions()->pluck("name")->toArray();
        $Data = [
                "roles"=> $roles ,
                "userRoles"=> $userRoles,
                "permissions"=>  $permissions,
                "userPermissions"=> $userPermissions,
                "user" => $user
            ];
        return $Data;
    }

    public function update(Request $request){

        $tab = 'Organization-user'; 

        $user = User::find($request->edit_org_user_id);
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password != null &&  $request->password != ''){
            $user->password = Hash::make($request->password);
        }

        $user->active = $request->active;
        $user->syncRoles([$request->role]);
        //revoke all permissions by default... if assigned any permissions they will be set below...
        $user->revokePermissionTo($user->getAllPermissions());
        if($request->has('permissions') && null!==$request->permissions){
            // if(!is_array($request->permissions)){
            //     return redirect()->back()->withInput()->withErrors(['permissions' => "Error in permission assignment"]);
            // }
            $user->revokePermissionTo($user->getAllPermissions());
            if(count($request->permissions)){
                //assign all the selected permissions to the user...
                $user->givePermissionTo($request->permissions);
            }
        }
        $user->save();
        return redirect()->back()->with('success', 'User Update successfully')->with('tab', $tab);
       
    }



    public function delete(Request $request){
        $tab = 'Organization-user';
        $user = User::find($request->user_id);
        $user->organizations()->detach();
        $user->delete();
    
        // Set session variable
        $request->session()->put('tab', $tab);
    
        return response()->json(["status" => true, "message" => "User deleted successfully."], 200);
    }


    // public function delete(Request $request){
    //     $tab = 'Organization-user';
    //     $user = user::find($request->user_id);

    //     // $organization = $user->organizations->first();
    //     // $adminUsers = $organization->users->filter(function ($user){
    //     //     return $user->hasRole('admin');
    //     // })->count();
    //     // if($adminUsers<=1){
    //     //     return response()->json(["status" => false,'error' => "Cannot delete only admin of the organization."],403);
    //     // }
    //     $user->organizations()->detach();
    //     $user->delete();


        
    //     return response()->json(["status" => true, "message" => "User deleted successfully."], 200);
    // }



    function check_user_email(Request $request) {
        $status = 0;

        if ($request->action == 'add') {
            $check_email = User::where('email', $request->email)->first();
            if ($check_email != "" || $check_email != null) {
                $status = 1;
            }
        } elseif ($request->action == 'edit') {
            // Get the current user's ID
            $currentUserId = $request->user_id;

            // Check for an email that matches the given email and exclude the current user
            $check_email = User::where('email', $request->email)
                ->where('id', '!=', $currentUserId)
                ->first();

            if ($check_email != "" || $check_email != null) {
                $status = 1;
            }
        }

        return $status;
    }

    
}
