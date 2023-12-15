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
use App\Models\Organization;
use App\Models\Service;
use App\Models\UserHaveService;

class SystemUserController extends Controller
{
    //
    public function index(){
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
        if(!$user->hasRole('admin')){
            abort(403);
        }
        $organization = $user->organizations->first();
        $user_have_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->get();

        return view("administration.users.add", compact("organization",'user_have_service'));
    }



    public function store(Request $request){
        $current_user = Auth::user();
        $organization = $current_user->organizations->first();

        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",
            "email" => "required|email|unique:App\Models\User,email",
            "password" => "required|string|min:8",
            "role" => "required",
            // "Services" => "required|array"
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->created_by = Auth()->user()->id;
        $user->save();

        $user = User::orderBy('id','desc')->first();


         // dd($request->role);
         $role = Role::findByName($request->role);
         $user->assignRole($role);


         if($role->name == 'admin'){
            $all_Permission =  Permission::pluck("name")->toArray();
            $user->givePermissionTo($all_Permission);
         }

        
         if(isset($request->Services)){
            foreach($request->Services as  $i=>$ser){
                $UserHaveService = new UserHaveService();
                $UserHaveService->organization_services_id =  $request->Services[$i];
                $UserHaveService->user_id = $user->id;
                $UserHaveService->organization_id = $organization->id;
                $UserHaveService->service_type =  get_serive_type_id($request->Services[$i]);
                $UserHaveService->save();
             }
         }
        
        //  //add this user into the same organization as of the logged in user...
         $organization = Organization::where('id',$organization->id)->first();

         $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);

         return redirect()->route("administration.users")->with('success', 'User added successfully');
        //  return redirect()->back()
    }


    

    

    public function view(User $user){
        $current_user = Auth::user();
        $organization = $current_user->organizations->first();
        $user_have_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$current_user->id)->get();

        $edit_user_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->pluck("organization_services_id")->toArray();

        $roles = Role::where('name', '!=', 'superadmin')->get();
        $edit_userRoles = $user->getRoleNames();

        // // $permissions = Permission::all();
        // $userPermissions = $user->getAllPermissions()->pluck("name")->toArray();
        return view('administration.users.edit', compact("user", "user_have_service", "edit_user_service" ,'roles' ,'edit_userRoles'));
    }



    public function edit(User $user, Request $request){


        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",
            "email" => [
                "required",
                "email",
                Rule::unique('users')->ignore($user->id),
            ],
            "role" => "required",
            "Services" => "required|array"
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $current_user = Auth::user();
        $organization = $current_user->organizations->first();

        $previous_user_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->pluck("organization_services_id")->toArray();

        $close_service = array_filter($previous_user_service, function($x) use ($request) {
            return !in_array($x, $request->Services);
        });


        if(!empty($close_service)){
            foreach($close_service as $closeService){
                $user_have_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->where('organization_services_id',$closeService)->first();
                $user_have_service->delete();
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != null &&  $request->password != ''){
            $user->password = Hash::make($request->password);
        }

        $user->active = $request->active;
        $user->syncRoles([$request->role]);

        if($request->role == 'user'){
            $user->revokePermissionTo($user->getAllPermissions());
         }else{
            $user->revokePermissionTo($user->getAllPermissions());
            $all_Permission =  Permission::pluck("name")->toArray();
            $user->givePermissionTo($all_Permission);
         }
         

        if(isset($request->Services)){
            foreach($request->Services as  $i=>$ser){
                $UserHaveService = new UserHaveService();
                $UserHaveService->organization_services_id =  $request->Services[$i];
                $UserHaveService->user_id = $user->id;
                $UserHaveService->organization_id = $organization->id;
                $UserHaveService->service_type =  get_serive_type_id($request->Services[$i]);
                $UserHaveService->save();
            }
        }

        $user->save();
        return redirect()->route("administration.users")->with('success', 'User Updated successfully');
    }

    public function delete(User $user){

        $current_user = Auth::user();
        $organization = $current_user->organizations->first();
        $previous_user_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->get();
        if(!empty($previous_user_service)){
            foreach($previous_user_service as $user_service){
                $user_service->delete();
            }
        }
        if($user->getAllPermissions()){
            $user->revokePermissionTo($user->getAllPermissions());
        }
        
        $user->organizations()->detach();
        $user->delete();
        return response()->json(["status" => true, "message" => "User deleted successfully."], 200);
    }
}
