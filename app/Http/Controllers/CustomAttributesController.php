<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userAgent;
use App\Models\OrganizationServices;

class CustomAttributesController extends Controller
{
    
        // public function index(){
        //     return view('customattributes.index');
        // }

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

        public function customattributes(Request $request,$service, $organization_service_id)
        {
            $custom_attributes = [];
            $agent_custom_attribute = "";
            $profile_custom_attributes = "";
            $queues_custom_attributes = "";
            $tfn_custom_attributess ="";

            $agents_attributes = $this->get_custom_attributes_Agents($organization_service_id);

       
            if ($agents_attributes['result'] == "success") {
                $agent_custom_attribute = $agents_attributes['data'];
            }



            $profile_attributes = $this->get_custom_attributes_profiles($organization_service_id);
            if ($profile_attributes['result'] == 'success') {
                $profile_custom_attributes = $profile_attributes['data'];
            }


            $queues_attributes = $this->get_custom_attributes_queues($organization_service_id);
            if($queues_attributes['result'] == "success")
            {
                $queues_custom_attributes = $queues_attributes['data'];
            }

            $tfn_custom_attributes = $this->get_custom_attributes_tfn($organization_service_id);
            if($tfn_custom_attributes['result'] == "success")
            {
                $tfn_custom_attributess = $tfn_custom_attributes['data'];
            }

            $custom_attributes['agent'] = $agent_custom_attribute;
            $custom_attributes['profile'] = $profile_custom_attributes;
            $custom_attributes['queues'] = $queues_custom_attributes;
            $custom_attributes['tfn'] = $tfn_custom_attributess;
            
           

            return view('customattributes.index', ['custom_attributes' => $custom_attributes]);
        }






        public function store(Request $request)
        {
            $OrganizationServices = OrganizationServices::find($request->organization_services_id);

            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';


             // POST data
             $postData = [
                'Action' => 'AddCustomAttribute',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' => auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'Agents',
                'api_name' => $request->api,
                'display_name' =>$request->name ,
            ];

            


            // dd($postData );

   
            $ch = curl_init($apiEndpoint);
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
            // Execute cURL session and get the response
            $response = curl_exec($ch);
            // Close cURL session
            curl_close($ch);
            $responce =  json_decode($response, true);

            // dd($responce);

           if($responce['result'] == 'success'){
               return redirect()->back()->with('success', 'attribute added successfully');
           }else{
               return redirect()->back()->with('error', 'Some thing Went Wrong ');
           }


        }

        public function store_custom_attribute_in_outbound(Request $request)
        {

            $OrganizationServices = OrganizationServices::find($request->organization_services_id);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';


            // dd($apiEndpoint);

             // POST data
             $postData = [
                'Action' => 'AddCustomAttribute',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' => auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'Profiles',
                'api_name' => $request->api,
                'display_name' =>$request->name ,
            ];


            

   
            $ch = curl_init($apiEndpoint);
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
            // Execute cURL session and get the response
            $response = curl_exec($ch);
            // Close cURL session
            curl_close($ch);
            $responce =  json_decode($response, true);

            // dd($responce);

           if($responce['result'] == 'success'){
               return redirect()->back()->with('success', 'attribute added successfully');
           }else{
               return redirect()->back()->with('error', 'Some thing Went Wrong ');
           }


        }


        public function get_custom_attributes_Agents( $organization_servicesID)
        {

     

            $OrganizationServices = OrganizationServices::find($organization_servicesID);

            // dd($OrganizationServices);

           
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';
            // POST data
            $postData = [
                'Action' => 'GetCustomAttributes',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' =>  auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'Agents'
            ];


            // dd($postData);
            $ch = curl_init($apiEndpoint);
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            // Execute cURL session and get the response
            $response = curl_exec($ch);
        
            // Close cURL session
            curl_close($ch);
            
            $api_response = json_decode($response, true);
       
            return $api_response;

        }

        public function get_custom_attributes_profiles($organization_service_id)
        {

            $OrganizationServices = OrganizationServices::find($organization_service_id);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';
            // POST data
            $postData = [
                'Action' => 'GetCustomAttributes',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' =>  auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'Profiles'
            ];
            $ch = curl_init($apiEndpoint);
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            // Execute cURL session and get the response
            $response = curl_exec($ch);
        
            // Close cURL session
            curl_close($ch);
            
            return json_decode($response, true);

        }

        public function get_custom_attributes_queues($organization_service_id)
        {

            $OrganizationServices = OrganizationServices::find($organization_service_id);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';
            // POST data
            $postData = [
                'Action' => 'GetCustomAttributes',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' =>  auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'Queues'
            ];
            $ch = curl_init($apiEndpoint);
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            // Execute cURL session and get the response
            $response = curl_exec($ch);
        
            // Close cURL session
            curl_close($ch);
            
            return json_decode($response, true);

        }

        public function get_custom_attributes_tfn($organization_service_id)
        {

            $OrganizationServices = OrganizationServices::find($organization_service_id);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';
            // POST data
            $postData = [
                'Action' => 'GetCustomAttributes',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' =>  auth()->user()->email,
                'responsetype' => 'json',
                'module' => 'TFN'
            ];
            $ch = curl_init($apiEndpoint);
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            // Execute cURL session and get the response
            $response = curl_exec($ch);
        
            // Close cURL session
            curl_close($ch);
            
            return json_decode($response, true);

        }




        public function get_agent_detail($organization_services_id, $AgentID) {
            $OrganizationServices = OrganizationServices::find($organization_services_id);
            if (!$OrganizationServices) {
                return ['status' => 'error', 'message' => 'Organization services not found'];
            }
        
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);
            if (!$phpArray || !isset($phpArray['server_url'], $phpArray['api_user'], $phpArray['api_pass'])) {
                return ['status' => 'error', 'message' => 'Invalid connection parameters'];
            }
        
            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        
            $postData = [
                'Action' => 'GetUserInfo',
                'apiUser' => $phpArray['api_user'],
                'apiPass' => $phpArray['api_pass'],
                'session_user' => auth()->user()->email,
                'responsetype' => 'json',
                'user' => $AgentID
            ];
        
            $ch = curl_init($apiEndpoint);
            if (!$ch) {
                return ['status' => 'error', 'message' => 'Failed to initialize cURL'];
            }
        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
            $response = curl_exec($ch);
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return ['status' => 'error', 'message' => 'cURL error: ' . $error];
            }
        
            curl_close($ch);
            
            return json_decode($response, true);
        }
        
        public function check_previous_api_name(Request $request) {
            
            $api_names = [];
            
            $api_name_exists = null;
            
            // Check if the API name is already in use
            if ($request->api_name) {
                $response = $this->get_custom_attributes_Agents($request->organization_servicesID);
            
                // Check if the response is successful and contains data
                if ($response['result'] == 'success' && $response['data']) {
                    // Assign API names from the response data
                    $api_names = $response['data'];
            
                    $api_name_exists = $this->searchByApiName($api_names, $request->api_name);
                }
            }
            
            // Check if the API name already exists
            if ($api_name_exists !== null) {
                $response_data['status'] = 'error';
                $response_data['message'] = 'API name is already in use.';
                return response()->json($response_data, 409); 
            } else {
                $response_data['status'] = 'success';
                $response_data['message'] = "API name is available";
                return response()->json($response_data, 200); 
            }
        }
        
        


        public function previous_api_name_outbound(Request $request)
        {
            $all_api_name_outbound = [];
            $result = "";
            $response = $this->get_custom_attributes_profiles($request->organization_servicesID);
            if ($response['result'] == 'success' && $response['data']) {
                $all_api_name_outbound = $response['data'];
                $result = $this->searchByApiName($all_api_name_outbound, $request->api_name);
            } else {
                $result = null;
            }

            if ($result !== null) {
                $data['status'] = 'error';
                $data['message'] = 'API name is already in use.';
                return response()->json($data, 400); // 400 Bad Request: Name already in use
            } else {
                // Do not post to the API
                $data['status'] = 'success';
                $data['message'] = "API name is available";
                return response()->json($data, 200); // 200 OK: Name available
            }
        }




        function searchByApiName($array, $apiName) {
            foreach ($array as $item) {
                if ($item['api_name'] === $apiName) {
                    return $item;
                }
            }
            return null; // Return null if not found
        }


      



        
           
       


}
