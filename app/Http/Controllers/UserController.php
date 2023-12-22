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

    public function index($service ,$serviceID){

        $user_agent_detail = [];
        $service_Users =  $this->get_user($serviceID);
        //get users of logged in user's organization...
        $user = Auth::user();

        // dd($service_Users);
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin')){
            abort(403);
        }
        $users = $organization->users;


        $userAgent_inDB = userAgent::count();
        if($userAgent_inDB !=  count($service_Users['user_id'])){
            foreach($service_Users['user_id'] as $i=>$api_agent){
                $user = $service_Users['user'][$i];
                $user_id = $service_Users['user_id'][$i];
                $serviceID =$serviceID;
                $orgID = $organization->id;
                $orgUserID = $users[0]->id;
                $this->store_agent_into_db($user ,$user_id,$serviceID, $orgID, $orgUserID);
            }
        }
       

        $userAgent = userAgent::with('user_detail')->get();

        return view('dialer.Agent.index',compact('users','service','service_Users' ,'serviceID' ,'userAgent'));
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

    function store_agent_into_db($user ,$user_id,$serviceID, $orgID, $orgUserID){
        $add_userAgent = userAgent::where('api_user',$user)->first();

        if($add_userAgent == '' || $add_userAgent == null){
            $add_userAgent = new userAgent();
        }
        $add_userAgent->orgid = $orgID;
        $add_userAgent->org_user_id = $orgUserID;
        $add_userAgent->services_id = $serviceID;
        $add_userAgent->api_user_id =$user_id;
        $add_userAgent->api_user = $user;
        $add_userAgent->save();
    }

    public function edit($service , $serviceID ,$AgentID){
        $dailer_agent_user = '';

        $dailer_agent_user_response =   $this->get_agent_detail($serviceID ,$AgentID);
        if($dailer_agent_user_response['result'] == 'success'){
            $dailer_agent_user = $dailer_agent_user_response['data'];

            // dd($dailer_agent_user);
        }


        return view('dialer.Agent.edit' ,compact('dailer_agent_user'));
    }



    function get_agent_detail($serviceID ,$AgentID) {
        $OrganizationServices = OrganizationServices::find($serviceID);
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
    

    function update_agent_in_db_detail(Request $request){
       $responce =  $this->update_agent_in_api_detail($request->name, $request->email , $request->User ,$request->Voice_Mail , $request->group , $request->active);

       if($responce['result'] == 'success'){
        return redirect()->back()->with('success', 'Agent Updated successfully');
       }else{
        return redirect()->back()->with('error', 'Some thing Went Wrong ');
       }
       
      
    }

    function update_agent_in_api_detail($name, $email , $User ,$Voice_Mail , $group , $active) {
        $userAgent = userAgent::where('api_user',$User)->first();
        $OrganizationServices = OrganizationServices::find($userAgent->services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

       $Agent_detail= $this->get_agent_detail($userAgent->services_id,$User);
       $options_value = $Agent_detail['data'];

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            
            'Action' => 'EditUser',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  $phpArray['api_user'],
            'responsetype' => 'json',
            'user' => $User,

            'full_name'=> $name,
            'user_group'=> $group,
            'active' => $active,
            'voicemail_id'=> $Voice_Mail,
            'email'=> $email,

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
        
        return json_decode($response, true);
    }


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

    function get_extension_details(Request $request) {

        // dd($request->all());
        return $this->get_agent_detail($request->services_id , $request->extension);
    }



   


}
