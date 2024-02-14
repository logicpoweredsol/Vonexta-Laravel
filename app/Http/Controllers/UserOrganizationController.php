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
use App\Models\UserHaveService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendImpersonation;
use Mockery\Generator\StringManipulation\Pass\Pass;

class UserOrganizationController extends Controller
{
    //
    public function store(Request $request){
        // dd($request->all());
        $tab = 'Organization-user'; 
        $request->session()->put('tab', $tab);
        $request->session()->put('tab-action', 'org-user');
      

        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",
            "email" => "required|email|unique:App\Models\User,email",
            "password" => "required|string",
            // "role" => "required",
            // "Services" => "required|array"
        ]);

        if($validator->fails()){
            $request->session()->put('tab-action', 'add-org-user');
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->Phone = $request->Phone;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->created_by = Auth()->user()->id;
        if($request->role == 'admin'){
            $user->is_owner = $this->is_first_user($request->org_id);
        }
       
        
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
                $UserHaveService->organization_id = $request->org_id;
                $UserHaveService->service_type =  get_serive_type_id($request->Services[$i]);
                $UserHaveService->save();

             }
         }
        

        //  //add this user into the same organization as of the logged in user...
         $organization = Organization::where('id',$request->org_id)->first();

         $user->organizations()->attach($organization,["is_organization_admin" => $user->hasRole("admin") ? 1 : 0]);

         return redirect()->back()->with('success', 'User added successfully');


        
    }

    public function edit($user_id ,$org_id){

        
        $tab = 'Organization-user'; 
        session()->put('tab', $tab);
        session()->put('tab-action', 'edit-org-user');

        $user = User::find($user_id);
        $organization = Organization::find($org_id);
        $organizationServices = $organization->services()->get();
        $user_have_service = UserHaveService::where('organization_id' ,$org_id)->where('user_id' ,$user_id)->pluck("organization_services_id")->toArray();
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $userRoles = $user->getRoleNames();
        $Data = [
            "user"=> $user ,
            "organization"=> $organization,
            "organizationServices"=>  $organizationServices,
            "user_have_service"=> $user_have_service,
            "userRoles" => $userRoles,
            'roles'=>$roles
        ];


    

        session()->put('org-user-edit', $Data);
        return redirect()->back();
    }

    public function update(Request $request){


        $tab = 'Organization-user'; 
        $request->session()->put('tab', $tab);
        $request->session()->put('tab-action', 'org-user');


        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:50",

            "email" => [
                "required",
                "email",
                Rule::unique('users')->ignore($request->user_id),
            ],
            "role" => "required",
            // "Services" => "required|array"
        ]);


        if($validator->fails()){
            $request->session()->put('tab-action', 'edit-org-user');
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $previous_user_have_service = UserHaveService::where('organization_id' ,$request->org_id)->where('user_id' ,$request->user_id)->pluck("organization_services_id")->toArray();

        $close_service = array_filter($previous_user_have_service, function($x) use ($request) {
            return !in_array($x, $request->Services);
        });


        if(!empty($close_service)){
            foreach($close_service as $closeService){
                $user_have_service = UserHaveService::where('organization_id' ,$request->org_id)->where('user_id' ,$request->user_id)->where('organization_services_id',$closeService)->first();
                $user_have_service->delete();
            }
        }

    

        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->Phone=$request->Phone;
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
                $UserHaveService = UserHaveService::where('organization_id' ,$request->org_id)->where('user_id' ,$request->user_id)->where('organization_services_id',$request->Services[$i])->first();
                
                if( $UserHaveService == '' && $UserHaveService == null ){
                    $UserHaveService = new UserHaveService();
                }
                $UserHaveService->organization_services_id =  $request->Services[$i];
                $UserHaveService->user_id = $request->user_id;
                $UserHaveService->organization_id = $request->org_id;
                $UserHaveService->service_type =  get_serive_type_id($request->Services[$i]);
                $UserHaveService->save();
            }
        }

        $user->save();
        return redirect()->back()->with('success', 'User Update successfully');
       
    }



    public function delete(Request $request){

        $tab = 'Organization-user'; 
        $request->session()->put('tab', $tab);
        $request->session()->put('tab-action', 'org-user');

        $user = User::find($request->user_id);
        $user->organizations()->detach();
        if($user->getAllPermissions()){
            $user->revokePermissionTo($user->getAllPermissions());
        }


        $user_have_service = UserHaveService::where('organization_id' ,$request->org_id)->where('user_id' ,$request->user_id)->get();
        foreach($user_have_service as $user_have){
            $user_have->delete();
        }

        $user->delete();    
        return response()->json(["status" => true, "message" => "User deleted successfully."], 200);
    }


    
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



    function is_first_user($OrgID) {

        $status = false;
        $UserOrganization = UserOrganization::where('organization_id',$OrgID)->first();

        if($UserOrganization == '' || $UserOrganization == null){
            $status = true;
        }
        return $status;
        
    }


    public function send_impersonation_email(Request $request)
    {
        $user = User::find($request->user);
        $code = $this->generateRandomCode();

        if($request->type == 'email'){
            $sender_email = $user->email;
            Mail::to($sender_email)->send(new SendImpersonation($code));
        }
        // elseif($request->type == 'text'){
        
        // }
        // Generate random code
       
        $user->code = $code;
        $user->save();
        // Send impersonation email
        
        return true;

    }


    public function generateRandomCode()
    {
        // Generate a 6-digit random code
        $code = rand(100000, 999999);
        
        return $code;
    }



    public function verified(Request $request){
        $user = User::with('organizations')->find($request->user_idd);
        $code = $request->code_1 .
                $request->code_2 .
                $request->code_3 .
                $request->code_4 .
                $request->code_5 .
                $request->code_6;

        if($user->code == $code){
            Session::put('original_user_id', Auth::id());
            Session::put('impersonated_user_id', $user);
            Auth::user()->impersonate($user);
            return redirect('/dashboard');
        }else{
            return redirect()->back()->with('error', 'Incorrect code. Please resend the code.');
        }

        
    }


    public function leaveImpersonation(Request $request) {
        Session::forget('original_user_id');
        Session::forget('impersonated_user_id');
        Auth::user()->leaveImpersonation();
        return redirect('/organizations');
    }

    
}
