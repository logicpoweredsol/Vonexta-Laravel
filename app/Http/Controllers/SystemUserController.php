<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\userAgent;
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
use Exception;
use Illuminate\Validation\ValidationException;



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

        $user_have_service = [];

        $organization = $user->organizations->first();
        $get_all_user_have_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->get();


        //ceck te validation

        foreach($get_all_user_have_service as $ceck_service){

  
            $responce = ceck_service_detail($ceck_service->organization_services_id);

            if($responce){
                array_push( $user_have_service , $ceck_service);
            }
        }
        return view("administration.users.index", compact("organization","user_have_service"));
    }



    public function store(Request $request){

        dd($request->all());
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


        //  dd($request->role);
         $role = Role::findByName($request->role);
         $user->assignRole($role);


         if($role->name == 'admin'){
            $all_Permission =  Permission::pluck("name")->toArray();
            $user->givePermissionTo($all_Permission);
         }

        

         if($request->role == 'admin'){
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

         }
      
        
        //  //add this user into the same organization as of the logged in user...
         $organization = Organization::where('id',$organization->id)->first();

         $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);
         return redirect()->route('administration.users')->with('success', 'User added successfully');

        
         
    }


    

    

    public function view(User $user){


       

        $user_have_service = [];
        $current_user = Auth::user();
        $organization = $current_user->organizations->first();
        $get_all_user_have_service = [];
        $edit_user_service = [];

        $get_all_user_have_service_witout_filter = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$current_user->id)->get();


        foreach($get_all_user_have_service_witout_filter as $ceck_service){
  
            $responce = ceck_service_detail($ceck_service->organization_services_id);


            if($responce){
                array_push( $user_have_service , $ceck_service);
            }
        }

       

       


        $edit_user_service_witout_filter = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->pluck("organization_services_id")->toArray();

        foreach ($edit_user_service_witout_filter as $ceck_service) {
            // Check if $ceck_service is an object
            if (is_object($ceck_service)) {
                $responce = ceck_service_detail($ceck_service->organization_services_id);
        
                if ($responce) {
                    array_push($edit_user_service, $ceck_service);
                }
            }
        }
        


        // dd($edit_user_service );

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
            // "Services" => "required|array"
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $current_user = Auth::user();
        $organization = $current_user->organizations->first();

        $previous_user_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,$user->id)->pluck("organization_services_id")->toArray();

        $close_service = array_filter($previous_user_service, function ($x) use ($request) {
            return is_array($request->Services) && !in_array($x, $request->Services);
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
         



         if($request->role == 'admin'){
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
        }

        $user->save();
        return redirect()->route("administration.users")->with('success', 'User Updated successfully');
    }

    // public function delete(Request $request){
    //     $user = User::find($request->user);
    //     $current_user = Auth::user();
    //     $organization = $current_user->organizations->first();
    
    //     $previous_user_service = UserHaveService::with('user_have_service')
    //         ->where('organization_id', $organization->id)
    //         ->where('user_id', $user->id)
    //         ->get();
    
    //     if (!empty($previous_user_service)) {
    //         foreach ($previous_user_service as $user_service) {
    //             $user_service->delete();
    //         }
    //     }
        
    //     if ($user->getAllPermissions()) {
    //         $user->revokePermissionTo($user->getAllPermissions());
    //     }
    
    //     $user->organizations()->detach();
    

    //     $user->delete();
    
    //     $userAgents = userAgent::where('org_user_id', $request->user)->get();
    //     if (!empty($userAgents)) {
    //         foreach ($userAgents as $userAgent) {
    //             $userAgent->delete();
    //         }
    //     }
    
    //     return response()->json(["status" => true, "message" => "User disabled successfully."], 200);
    // }


    public function delete(Request $request){
        $user = User::find($request->user);
        $user->active= 0;
        $user->save();
       
        return response()->json(["status" => true, "message" => "User disabled successfully."], 200);
    }





    public function store_user_by_agent_side(Request $request){
        
        try{
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
                $data = [
                    'status' => 'validator-fail',
                    'errors' => $validator->errors(),
                ];
                // return redirect()->back()->withInput()->withErrors($validator);
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
    
            
    
             if($request->role == 'admin'){
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
    
             }
          
            
            //  //add this user into the same organization as of the logged in user...
             $organization = Organization::where('id',$organization->id)->first();
    
             $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);


             $data = [
                'status' => 'success',
                'data' => $user,
            ];
            } catch (ValidationException $e) {
                // Handle validation errors
                $data = [
                    'status' => 'fail',
                    'errors' => $e->errors(),
                ];
            } catch (Exception $e) {
                // Handle other exceptions
                $data = [
                    'status' => 'validator-fail',
                    'errors' => $validator->errors(),
                ];
            }

             // Handle other exceptions
             return $data;
     

    }


    
}
