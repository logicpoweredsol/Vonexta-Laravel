<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\userAgent;
use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    //


    // Start Randing the Data Function
    public function index($service ,$organization_servicesID){

        $user = Auth::user();
        $organization = $user->organizations->first();
        $user_agent_detail = [];
        $service_Users =  $this->get_user($organization_servicesID);

        $users = $organization->users;

        $userAgent_inDB = userAgent::count();

        if($userAgent_inDB !=  count($service_Users['user_id'])){
            foreach($service_Users['user_id'] as $i=>$api_agent){
                $user = $service_Users['user'][$i];
                $user_id = $service_Users['user_id'][$i];
                $organization_servicesID = $organization_servicesID;
                $orgID = $organization->id;
                $orgUserID = $users[0]->id;
                $this->store_agent_into_db($user ,$user_id,$organization_servicesID, $orgID, $orgUserID);
            }
        }
        if($userAgent_inDB !=  count($service_Users['user_id'])){
           
        }

        $userAgent = userAgent::with('user_detail')->get();

        return view('dialer.Agent.index',compact('service','users','organization_servicesID' ,'userAgent'));
    }

    function get_user($organization_service_id) {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllUsers',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  $phpArray['api_user'],
            'responsetype' => 'json',
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

    function store_agent_into_db($user ,$user_id,$organization_servicesID, $orgID, $orgUserID){
        $add_userAgent = userAgent::where('api_user',$user)->first();

        if($add_userAgent == '' || $add_userAgent == null){
            $add_userAgent = new userAgent();
        }

        $add_userAgent->orgid = $orgID;
        $add_userAgent->org_user_id = $orgUserID;
        $add_userAgent->services_id = $organization_servicesID;
        $add_userAgent->api_user_id =$user_id;
        $add_userAgent->api_user = $user;
        $add_userAgent->password = base64_encode($this->generateRandomString());
        $add_userAgent->save();
    }

    function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
     // End Randing the Data Function

     // Start Edit User
     public function edit($service , $organization_services_id ,$AgentID){

        $dailer_agent_user = '';
        $dailer_agent_user_response =   $this->get_agent_detail($organization_services_id ,$AgentID);
        if($dailer_agent_user_response['result'] == 'success'){
            $dailer_agent_user = $dailer_agent_user_response['data'];
        }


        return view('dialer.Agent.edit' ,compact('dailer_agent_user'));
    }

    public function get_agent_detail($organization_services_id ,$AgentID) {
        $OrganizationServices = OrganizationServices::find($organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            'Action' => 'GetUserInfo',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  $phpArray['api_user'],
            'responsetype' => 'json',
            'user'=>$AgentID
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
     // End  Edit User
    

    //   Update Function Deaatil Tabk of Agent 
    public function update_agent_in_db_detail(Request $request){

        // dd($request->all());

        $userAgent = userAgent::where('api_user',$request->User)->first();

         $OrganizationServices = OrganizationServices::find($userAgent->services_id);
         $phpArray = json_decode($OrganizationServices->connection_parameters, true);
 
        $Agent_detail= $this->get_agent_detail($userAgent->services_id,$request->User);

        $options_value = $Agent_detail['data'];
        
         $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
         // POST data
         $postData = [
             
             'Action' => 'EditUser',
             'apiUser' =>  $phpArray['api_user'],
             'apiPass' =>  $phpArray['api_pass'],
             'session_user' =>  $phpArray['api_user'],
             'responsetype' => 'json',
             'user' => $request->User,
 
             'full_name'=> $request->name,
             'user_group'=>  $request->group,
             'active' => $request->active,
             'voicemail_id'=> $request->Voice_Mail,
             'email'=> $request->email,
 
             'mobile_number' =>  $options_value['mobile_number'],
             'agent_choose_ingroups'=> $options_value['agent_choose_ingroups'],
             'agent_choose_blended'=> $options_value['agent_choose_blended'],
             'closer_default_blended'=> $options_value['closer_default_blended'],
             'scheduled_callbacks'=> $options_value['scheduled_callbacks'],
             'agentonly_callbacks'=> $options_value['agentonly_callbacks'],
             'agentcall_manual'=> $options_value['agentcall_manual'],
             'agent_call_log_view_override'=> $options_value['agent_call_log_view_override'],
             'max_inbound_calls'=> $options_value['max_inbound_calls'],
 
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

        if($responce['result'] == 'success'){
            return redirect()->back()->with('success', 'Agent Updated successfully');
        }else{
            return redirect()->back()->with('error', 'Some thing Went Wrong ');
        }
    }

     //   Update Function Option Tabk of Agent 
    function update_agent_in_db_options(Request $request) {

        // dd($request->all());
        $agent_choose_ingroups = isset($request->agent_choose_ingroups) ? '1' : '0';
        $agent_choose_blended = isset($request->agent_choose_blended) ? '1' : '0';
        $closer_default_blended = isset($request->closer_default_blended) ? '1' : '0';
        $scheduled_callbacks = isset($request->scheduled_callbacks) ? '1' : '0';
        $agentonly_callbacks = isset($request->agentonly_callbacks) ? '1' : '0';
        $agentcall_manual = isset($request->agentcall_manual) ? '1' : '0';
        $agent_call_log_view_override = isset($request->agent_call_log_view_override) ? '1' : '0';



        $userAgent = userAgent::where('api_user',$request->User)->first();
        $OrganizationServices = OrganizationServices::find($userAgent->services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $Agent_detail= $this->get_agent_detail($userAgent->services_id,$request->User);
        $options_value = $Agent_detail['data'];
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            
            'Action' => 'EditUser',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  $phpArray['api_user'],
            'responsetype' => 'json',
            'user' => $request->User,

            'full_name'=> $options_value['full_name'],
            'user_group'=> $options_value['user_group'],
            'active' =>$options_value['active'],
            'voicemail_id'=>  $options_value['voicemail_id'],
            'email'=> $options_value['email'],

            'mobile_number' => $request->Sms_number,
            'agent_choose_ingroups'=> $agent_choose_ingroups,
            'agent_choose_blended'=> $agent_choose_blended,
            'closer_default_blended'=> $closer_default_blended,
            'scheduled_callbacks'=> $scheduled_callbacks,
            'agentonly_callbacks'=> $agentonly_callbacks,
            'agentcall_manual'=> $agentcall_manual,
            'agent_call_log_view_override'=> $agent_call_log_view_override,
            'max_inbound_calls'=> $request->max_inbound_calls,
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
        
        $api_responce =  json_decode($response, true);

        if($api_responce['result'] == 'success'){
            return redirect()->back()->with('success', 'Agent Updated successfully');
           }else{
            return redirect()->back()->with('error', 'Some thing Went Wrong ');
           }



    }



    function check_extension(Request $request){
        $reponse = $this->get_agent_detail($request->services_id,$request->extension);
        $data = [];
        if($reponse['result'] == 'success'){
            $userAgent = userAgent::where('api_user',$request->extension)->first();
            if($userAgent){
                $data['status'] = 'failed';// already in use
                $data['message'] = 'Extension is already in use.';// already in use
            } else{
                // extension is free
                $data['status'] = 'success';// already in use
                $data['message'] = 'Extension is free to use.';
            }
        } else { // extension is invalid
            $data['status'] = 'failed';// already in use
            $data['message'] = "Invalid value in extension. Doesn't exist";
        }

        return $data;
    }


    function add_agents(Request $request){
        dd($request);
    }

}
