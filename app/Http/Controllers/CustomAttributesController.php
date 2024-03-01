<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userAgent;

class CustomAttributesController extends Controller
{
    //
    public function index(){
        return view('customattributes.index');
    }

        public function add(Request $request){

            // dd($request->all());
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
            $user->phone =$request->Phone;
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

        public function customattributes(Request $request){
            return view('customattributes.index');
        }

}
