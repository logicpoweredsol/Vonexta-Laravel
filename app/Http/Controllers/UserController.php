<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\userAgent;
use App\Models\UserHaveService;
use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use ReturnTypeWillChange;
use Spatie\Permission\Models\Permission;
use App\Models\accessKey;

class UserController extends Controller
{
    //


    // Start Randing the Data Function
    public function index($service ,$organization_servicesID){
        session()->forget('add-user-by-agent');
        session()->forget('tab');
        $user = Auth::user();
        $organization = $user->organizations->first();
        $users = $organization->users;
        $serviceUsers = $this->get_user($organization_servicesID);
        // $access_key=$this->generateAccessKey($organization_servicesID,$AgentID);
        $userAgents = [];

      
        
        if ($serviceUsers['result'] == 'success') {
            foreach ($serviceUsers['user_id'] as $i => $serviceUser) {
                $user_record = userAgent::with('user_detail')->where('api_user', $serviceUsers['user'][$i])->first();

           

                if( $user_record != null && $user_record != '' ){
                    $userAgents[] = (object)[
                        'id'=>          $user_record->id,
                        'email'      => $user_record->user_detail->email,
                        'user'       => $serviceUsers['user'][$i],
                        'full_name'  => $serviceUsers['full_name'][$i],
                        'User_Group' => $serviceUsers['user_group'][$i],
                        'active'     => $serviceUsers['active'][$i],
                    ];
                }
               
            }
        }





        $user_have_service = [];

        $organization = $user->organizations->first();
        $get_all_user_have_service = UserHaveService::with('user_have_service')->where('organization_id' ,$organization->id)->where('user_id' ,auth()->user()->id)->get();

        //ceck te validation
        foreach($get_all_user_have_service as $ceck_service){
            $responce = ceck_service_detail($ceck_service->organization_services_id);
            if($responce){
                array_push( $user_have_service , $ceck_service);
            }
        }
        // $service_Users =  $this->get_user($organization_servicesID);
        
        // // dd( $service_Users);
        // $userAgent_inDB = userAgent::count();
        // if($userAgent_inDB !=  count($service_Users['user_id'])){
        //     foreach($service_Users['user_id'] as $i=>$api_agent){
        //         $user = $service_Users['user'][$i];
        //         $user_id = $service_Users['user_id'][$i];
        //         $organization_servicesID = $organization_servicesID;
        //         $orgID = $organization->id;
        //         $orgUserID = $users[0]->id;
        //         $this->store_agent_into_db($user ,$user_id,$organization_servicesID, $orgID, $orgUserID);
        //     }
        // }

        // $userAgent = userAgent::where('orgid',$organization->id)->with('user_detail')->get();


        $get_Inbound_skill_resppnce = $this->get_Inbound_skill($organization_servicesID);
        $get_Campaigns_skill_resppnce = $this->get_Campaigns_skill($organization_servicesID);
        $skills = [
            'Inbound'=> $get_Inbound_skill_resppnce,
            'Campaigns'=>$get_Campaigns_skill_resppnce
        ];



        $last_extension = "";
        $loop = true;
        $all_extension = userAgent::all()->pluck('api_user')->toArray();

        if(!empty( $all_extension)){
            $minimumValue = min($all_extension);
            $next_extension = $minimumValue + 1;
            while ($loop) {
                if (in_array($next_extension, $all_extension)) {
                    $next_extension++; // Increment the value correctly
                } else {
                    $last_extension = $next_extension;
                    $loop = false;
                }
            }
        }else{
            $last_extension = '1001';
        }
       



        return view('dialer.Agent.index',compact('service','users','organization_servicesID' ,'userAgents' ,'organization' ,'user_have_service' ,'skills' ,'last_extension'));
    }

  




       // inbound skills on add agent
       function get_Inbound_skill($organization_servicesID){
        $OrganizationServices = OrganizationServices::find($organization_servicesID);
            if ($OrganizationServices) {
                $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        
                $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Inbound/API.php';
        
                $postData = [
                    'Action' => 'GetAllIngroup',
                    'apiUser' =>  $phpArray['api_user'],
                    'apiPass' =>  $phpArray['api_pass'],
                    'session_user' =>auth()->user()->email,
                ];
                $ch = curl_init($apiEndpoint);
        
                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($ch);
                curl_close($ch);
        
                $api_response = json_decode($response, true);
       
                return $api_response;
            } else {
                // Handle the case when $OrganizationServices is null
                return response()->json(['error' => 'Organization Services not found'], 404);
            }
    }


        // compaign skills on agent
        function get_Campaigns_skill($organization_servicesID)
        {
        
        
            $OrganizationServices = OrganizationServices::find($organization_servicesID);
        
            if ($OrganizationServices) {
                $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        
                $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        
                $postData = [
                    'Action' => 'GetAllCampaigns',
                    'apiUser' =>  $phpArray['api_user'],
                    'apiPass' =>  $phpArray['api_pass'],
                    'session_user' =>auth()->user()->email,
                ];
                $ch = curl_init($apiEndpoint);
        
                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($ch);
                curl_close($ch);
        
                $api_response = json_decode($response, true);
                return $api_response;
            } else {
                // Handle the case when $OrganizationServices is null
                return response()->json(['error' => 'Organization Services not found'], 404);
            }
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
            'session_user' =>  auth()->user()->email,
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




    function model_update_skills(Request $request) {
        
        $max_inbound_calls = '';
        $call_log_inbounds = '';
        $call_log_outbounds = '';

        $dailer_agent_user_response =   $this->get_agent_detail($request->organization_servicesID ,$request->extension);

        
        if($dailer_agent_user_response['result'] == 'success'){
            $max_inbound_calls = $dailer_agent_user_response['data']['max_inbound_calls'];

        }

     



        $get_call_log_response_inbound_dume =   $this->get_call_inbound_log($request->organization_servicesID ,$request->extension);
        // dd($get_call_log_response_inbound_dume);
        // $other_inbound_call  = $get_call_log_response_inbound_dume['data'];
        if($get_call_log_response_inbound_dume['result'] == 'success'){
            $call_log_inbound_all_agent = $get_call_log_response_inbound_dume['data'];
            $call_log_inbounds  = $this->findArrayByKey($call_log_inbound_all_agent ,$request->extension);

            // dd($call_log_inbounds);
        }

        $get_call_log_response_outbound_dume =   $this->get_call_outbound_log($request->organization_servicesID ,$request->extension);
        if($get_call_log_response_outbound_dume['result'] == 'success'){
            $call_log_outbound_all_agent = $get_call_log_response_outbound_dume['data'];
            $call_log_outbounds  = $this->findArrayByKey($call_log_outbound_all_agent ,$request->extension);
        }


        $data = ['max_inbound_calls'=>$max_inbound_calls, 'call_log_inbounds'=>$call_log_inbounds , 'call_log_outbounds'=>$call_log_outbounds ];


        return  $data;
    }

    // function store_agent_into_db($user ,$user_id,$organization_servicesID, $orgID, $orgUserID){
    //     $add_userAgent = userAgent::where('api_user',$user)->first();

    //     if($add_userAgent == '' || $add_userAgent == null){
    //         $add_userAgent = new userAgent();
    //         $add_userAgent->orgid = $orgID;
    //         $add_userAgent->org_user_id = $orgUserID;
    //         $add_userAgent->services_id = $organization_servicesID;
    //         // $add_userAgent->api_user_id =$user_id;
    //         $add_userAgent->api_user = $user;
    //         $add_userAgent->password = base64_encode($this->generateRandomString());
    //         $add_userAgent->save();
    //     }

       
    // }

    // function generateRandomString() {
    //     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     $randomString = '';
    //     for ($i = 0; $i < 10; $i++) {
    //         $randomString .= $characters[rand(0, strlen($characters) - 1)];
    //     }
    //     return $randomString;
    // }
     // End Randing the Data Function


     // Start Edit User
     public function edit($service , $organization_services_id ,$AgentID){
        $dailer_agent_user = '';
        $call_log_inbounds = '';
        $call_log_outbounds = '';

        $dailer_agent_user_response =   $this->get_agent_detail($organization_services_id ,$AgentID);
        if($dailer_agent_user_response['result'] == 'success'){
            $dailer_agent_user = $dailer_agent_user_response['data'];
        }

        $get_call_log_response_inbound_dume =   $this->get_call_inbound_log($organization_services_id ,$AgentID);
        // dd($get_call_log_response_inbound_dume);
        // $other_inbound_call  = $get_call_log_response_inbound_dume['data'];
        if($get_call_log_response_inbound_dume['result'] == 'success'){
            $call_log_inbound_all_agent = $get_call_log_response_inbound_dume['data'];
            $call_log_inbounds  = $this->findArrayByKey($call_log_inbound_all_agent ,$AgentID);

            // dd($call_log_inbounds);
        }

        $get_call_log_response_outbound_dume =   $this->get_call_outbound_log($organization_services_id ,$AgentID);
        if($get_call_log_response_outbound_dume['result'] == 'success'){
            $call_log_outbound_all_agent = $get_call_log_response_outbound_dume['data'];
            $call_log_outbounds  = $this->findArrayByKey($call_log_outbound_all_agent ,$AgentID);
        }

            return view('dialer.Agent.edit' ,compact('service','dailer_agent_user','organization_services_id','call_log_inbounds' ,'call_log_outbounds'));
    }


    public function detail($service , $organization_services_id ,$AgentID){
        $accessKey =  $this->generateAndRedirect($AgentID,$organization_services_id);
        return redirect("https://agent.vonexta.com:8181/agent/index.php?Token=$accessKey");
    }

    public function generateAndRedirect($AgentID,$organization_service_id)
    {
        // Generate the access key
        $accessKey = $this->generateAccessKey();


        $this->saveAccessKey($AgentID,$organization_service_id,$accessKey);    
        return $accessKey;
    }

    private function generateAccessKey()
    {
       
        return bin2hex(random_bytes(16));
    }


    private function saveAccessKey($AgentID,$organization_service_id,$accessKey)
    {

        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $access_keys = new accessKey();
        $access_keys->extension = $AgentID;
        $access_keys->organization_id =$OrganizationServices->organization_id ;
        $access_keys->service_id = $organization_service_id;
        $access_keys->access_key =$accessKey;
        $access_keys->save();
        return true;

    }





    //Get detail Model in edit Agent 
    public function get_skill_inbound_level(Request $request){


        // dd($request->all());
        $other_inbound_call = [];
        $skill_level_array = [];

        $email_array = [];
        $extension_array = [];
        $level_array = [];
        $invited_array = [];
  
        $get_call_log_response_inbound_dume =   $this->get_call_inbound_log($request->organization_services_id ,$request->extension);

        $other_inbound_call  = $get_call_log_response_inbound_dume['data'];
        if($get_call_log_response_inbound_dume['result'] == 'success'){
            if (array_key_exists($request->extension, $other_inbound_call)) {
                unset($other_inbound_call[$request->extension]);
            }

        }

        
        foreach ($other_inbound_call as $extension => $other_inbound) {
            foreach ($other_inbound as $other) {
                if ($other['group_id'] == $request->group_id) {
                    $userAgent = userAgent::with('user_detail')->where('api_user', $extension)->first();
    
                    // Check if $userAgent is not null before accessing its properties
                    if ($userAgent) {
                        array_push($email_array, $userAgent->user_detail->email);
                        array_push($extension_array, $extension);
                        array_push($level_array, $other['group_grade']);
                        array_push($invited_array, $other['selected']);
                    }
                }
            }
        }

        array_push($skill_level_array,$email_array);
        array_push($skill_level_array,$extension_array);
        array_push($skill_level_array,$level_array);
        array_push($skill_level_array,$invited_array);
        


        return  $skill_level_array;

    }

    public function get_skill_outbound_level(Request $request){


        // dd($request->all());
        $other_outbound_call = [];
        $skill_level_array = [];

        $email_array = [];
        $extension_array = [];
        $level_array = [];
  
        $get_call_log_response_inbound_dume =   $this->get_call_outbound_log($request->organization_services_id ,$request->extension);

        $other_outbound_call  = $get_call_log_response_inbound_dume['data'];
        if($get_call_log_response_inbound_dume['result'] == 'success'){
            if (array_key_exists($request->extension, $other_outbound_call)) {
                unset($other_outbound_call[$request->extension]);
            }

        }

        
        foreach ($other_outbound_call as $extension => $other_outbound) {
            foreach ($other_outbound as $other) {
                if ($other['campaign_id'] == $request->campaign_id) {
                    $userAgent = userAgent::with('user_detail')->where('api_user', $extension)->first();
        
                    // Check if $userAgent is not null before accessing its properties
                    if ($userAgent) {
                        array_push($email_array, $userAgent->user_detail->email);
                        array_push($extension_array, $extension);
                        array_push($level_array, $other['campaign_grade']);
                    }
                }
            }
        }

        array_push($skill_level_array,$email_array);
        array_push($skill_level_array,$extension_array);
        array_push($skill_level_array,$level_array);
        
        return  $skill_level_array;

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
            'session_user' => auth()->user()->email,
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

    public function get_call_inbound_log($organization_services_id ,$AgentID) {
        $OrganizationServices = OrganizationServices::find($organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Inbound/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllAgentRank',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
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

    public function get_call_outbound_log($organization_services_id ,$AgentID) {
        $OrganizationServices = OrganizationServices::find($organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'GetCampaignRanks',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
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


    function findArrayByKey($array, $key) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return null; // or handle the case where the key is not found
        }
    }



    
     // End  Edit User
    

    //   Update Function Deaatil Tabk of Agent 
    public function update_agent_in_db_detail(Request $request){

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
             'session_user' => auth()->user()->email,
             'responsetype' => 'json',
             'user' => $request->User,
 
             'full_name'=> $request->name,
             'user_group'=>  $request->role,
             'active' => $request->status,
             'voicemail_id'=> $request->voice_mail,
             'email'=> $request->email,
             'mobile_number' =>  $options_value['mobile_number'],
             'custom_attribute' =>$request->custom_attribute,
            //  'agent_choose_ingroups'=> $options_value['agent_choose_ingroups'],
            //  'agent_choose_blended'=> $options_value['agent_choose_blended'],
            //  'closer_default_blended'=> $options_value['closer_default_blended'],
            //  'scheduled_callbacks'=> $options_value['scheduled_callbacks'],
            //  'agentonly_callbacks'=> $options_value['agentonly_callbacks'],
            //  'agentcall_manual'=> $options_value['agentcall_manual'],
            //  'agent_call_log_view_override'=> $options_value['agent_call_log_view_override'],
            //  'max_inbound_calls'=> $options_value['max_inbound_calls'],


             'hotkeys_active' => 0,
             'user_level' => 1,
             'vicidial_recording_override' => "DISABLED",
             'agent_lead_search_override' => "DISABLED",
             'vdc_agent_api_access' =>1,
             'modify_same_user_level' =>'',


 
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

        //  dd($responce);

        if($responce['result'] == 'success'){
            return redirect()->back()->with('success', 'Agent Updated successfully');
        }else{

            return redirect()->back()->with('error', 'Some thing Went Wrong ');
        }
    }

     //   Update Function Option Tabk of Agent 
    // function update_agent_in_db_options(Request $request) {

    //     // dd($request->all());
    //     $agent_choose_ingroups = isset($request->Inbound_Upon_Login) ? '1' : '0';
    //     $agent_choose_blended = isset($request->Auto_Outbound_Upon_Login) ? '1' : '0';
    //     $closer_default_blended = isset($request->Allow_Outbound) ? '1' : '0';
    //     $scheduled_callbacks = isset($request->scheduled_callbacks) ? '1' : '0';
    //     $agentonly_callbacks = isset($request->Personal_Callbacks) ? '1' : '0';
    //     $agentcall_manual = isset($request->allow_manual_calls) ? '1' : '0';
    //     $agent_call_log_view_override = isset($request->Call_Log_View) ? 'Y' : 'N';



    //     $userAgent = userAgent::where('api_user',$request->User)->first();

       
    //     $OrganizationServices = OrganizationServices::find($userAgent->services_id);

    //     $phpArray = json_decode($OrganizationServices->connection_parameters, true);

    //     $Agent_detail= $this->get_agent_detail($userAgent->services_id,$request->User);
    //     $options_value = $Agent_detail['data'];
    //     $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
    //     // POST data
    //     $postData = [
            
    //         'Action' => 'EditUser',
    //         'apiUser' =>  $phpArray['api_user'],
    //         'apiPass' =>  $phpArray['api_pass'],
    //         'session_user' =>  auth()->user()->email,
    //         'responsetype' => 'json',
    //         'user' => $request->User,

    //         'full_name'=> $options_value['full_name'],
    //         'user_group'=> $options_value['user_group'],
    //         'active' =>$options_value['active'],
    //         'voicemail_id'=>  $options_value['voicemail_id'],
    //         'email'=> $options_value['email'],

    //         'mobile_number' => $request->Sms_number,
    //         'agent_choose_ingroups'=> $agent_choose_ingroups,
    //         'agent_choose_blended'=> $agent_choose_blended,
    //         'closer_default_blended'=> $closer_default_blended,
    //         'scheduled_callbacks'=> $scheduled_callbacks,
    //         'agentonly_callbacks'=> $agentonly_callbacks,
    //         'agentcall_manual'=> $agentcall_manual,
    //         'agent_call_log_view_override'=> $agent_call_log_view_override,
    //         'max_inbound_calls'=> $request->max_inbound_calls,
    //     ];
    //     $ch = curl_init($apiEndpoint);
    
    //     // Set cURL options
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
    //     // Execute cURL session and get the response
    //     $response = curl_exec($ch);
    
    //     // Close cURL session
    //     curl_close($ch);
        
    //     $api_responce =  json_decode($response, true);

    //     if($api_responce['result'] == 'success'){
    //         return redirect()->back()->with('success', 'Agent Updated successfully');
    //        }else{
    //         return redirect()->back()->with('error', 'Some thing Went Wrong ');
    //        }



    // }



    function check_extension(Request $request){


        $reponse = $this->get_agent_detail($request->organization_servicesID,$request->extension);
        $data = [];


        if($reponse['result'] == 'success'){

            $data['status'] = 'failed';// already in use
            $data['message'] = 'Extension is already in use.';// already in use
            // $userAgent = userAgent::where('api_user',$request->extension)->first();
            // if($userAgent){
            //     $data['status'] = 'failed';// already in use
            //     $data['message'] = 'Extension is already in use.';// already in use
            // } else{
            //     // extension is free
            //     $data['status'] = 'success';// already in use
            //     $data['message'] = 'Extension is free to use.';
            // }
        } else { // extension is invalid
            $data['status'] = 'success';// already in use
            $data['message'] = "Extension is available";
        }

        return $data;
    }


    function extension_skill(Request $request) {

        $max_inbound_calls = '';
        $call_log_inbounds = '';
        $call_log_outbounds = '';

        $dailer_agent_user_response =   $this->get_agent_detail($request->organization_servicesID ,$request->extension);

        
        if($dailer_agent_user_response['result'] == 'success'){
            $max_inbound_calls = $dailer_agent_user_response['data']['max_inbound_calls'];

        }

     



        $get_call_log_response_inbound_dume =   $this->get_call_inbound_log($request->organization_servicesID ,$request->extension);
        // dd($get_call_log_response_inbound_dume);
        // $other_inbound_call  = $get_call_log_response_inbound_dume['data'];
        if($get_call_log_response_inbound_dume['result'] == 'success'){
            $call_log_inbound_all_agent = $get_call_log_response_inbound_dume['data'];
            $call_log_inbounds  = $this->findArrayByKey($call_log_inbound_all_agent ,$request->extension);

            // dd($call_log_inbounds);
        }

        $get_call_log_response_outbound_dume =   $this->get_call_outbound_log($request->organization_servicesID ,$request->extension);
        if($get_call_log_response_outbound_dume['result'] == 'success'){
            $call_log_outbound_all_agent = $get_call_log_response_outbound_dume['data'];
            $call_log_outbounds  = $this->findArrayByKey($call_log_outbound_all_agent ,$request->extension);
        }


        $data = ['max_inbound_calls'=>$max_inbound_calls, 'call_log_inbounds'=>$call_log_inbounds , 'call_log_outbounds'=>$call_log_outbounds ];


        return  $data;
        

    }


    function save_agents(Request $request)
    {
       
        try {
            $org_user = User::where('id', $request->organization_user)->first();
            // $agent_choose_ingroups = isset($request->Inbound_Upon_Login) ? '1' : '0';
            // $agent_choose_blended = isset($request->Auto_Outbound_Upon_Login) ? '1' : '0';
            // $closer_default_blended = isset($request->Allow_Outbound) ? '1' : '0';
            // $scheduled_callbacks = isset($request->scheduled_callbacks) ? '1' : '0';
            // $agentonly_callbacks = isset($request->Personal_Callbacks) ? '1' : '0';
            // $agentcall_manual = isset($request->Allow_Manual_Calls) ? '1' : '0';
            // $agent_call_log_view_override = isset($request->Call_Log_View) ? 'Y' : 'N';
            $active = isset($request->status) && $request->status == 1 ? 'Y' : 'N';

            $OrganizationServices = OrganizationServices::find($request->organization_servicesID);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);

            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
            // POST data
            $postData = [
                'Action' => 'AddUser',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' => $phpArray['api_user'],
                'responsetype' => 'json',
                'user' => $request->user,
                'full_name' => $request->agent_name,
                'user_group' =>  $request->agent_role,
                'active' => $active,
                'email' => $org_user->email,
                'mobile_number' => $request->Sms_number,
                'inbound_calls_limit' => $request->inbound_calls_limit,
            ];

            // dd($postData);


            // dd($postData);
            $ch = curl_init($apiEndpoint);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);
            $api_response = json_decode($response, true);

        
            if ($api_response['result'] == 'success') {
                $add_userAgent = new userAgent();
                $add_userAgent->orgid = $request->orgID;
                $add_userAgent->org_user_id = $org_user->id;
                $add_userAgent->services_id = $request->organization_servicesID;
                $add_userAgent->api_user = $request->user;
                $add_userAgent->password = $api_response['pass_hash_encrypted'];
                $add_userAgent->name =$request->agent_name;
                $add_userAgent->status =$request->status;
                $add_userAgent->save();

                foreach($request->inbound_id as $i=>$data ){
                    $organization_services_id =  $request->organization_servicesID;

                    $inbound_id = $request->inbound_id[$i];
                    $level  = $request->level[$i];   
                    $invited = isset($request->{'row_' . $request->inbound_id[$i]}) ? 'YES' : 'NO';

                    $extension =  $request->user;
                   $return  = $this->skill_inbound_update($organization_services_id,$inbound_id ,$level ,$invited , $extension);
                }


                foreach($request->campaign_id as $i=>$data2 ){
                    $organization_services_id =  $request->organization_servicesID;

                    $campaign_id = $request->campaign_id[$i];
                    $level  = $request->profile_id_level[$i];             
                    $extension =  $request->user;
                   $return  = $this->skill_campaigns_update($organization_services_id,$campaign_id ,$level, $extension);
                }

            } else {
                return redirect()->back()->with('error', 'Something Went Wrong');
            }

        return redirect()->back()->with('add_more_agent', 'New agent added successfully.');
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    function skill_inbound_update($organization_services_id,$group_id ,$level ,$invited , $extension){

        $itemrank = "agent_rank_table_length=10&CHECK_{$extension}={$invited}&RANK_{$extension}={$level}&GRADE_{$extension}={$level}";
        $OrganizationServices = OrganizationServices::find($organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Inbound/API.php';
        // POST data
        $postData = [
            'Action' => 'EditAgentRank',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>auth()->user()->email,
            'group_id'=> $group_id,
            'itemrank'=>$itemrank
        ];

        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);


        return  $api_response;


        // $itemrank = "agent_rank_table_length=10&CHECK_{$extension}={$invited}&RANK_{$extension}={$level}&GRADE_{$extension}={$level}";
        // $OrganizationServices = OrganizationServices::find($organization_services_id);
        // $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        // $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Inbound/API.php';
        // // POST data
        // $postData = [
        //     'Action' => 'EditAgentRank',
        //     'apiUser' =>  $phpArray['api_user'],
        //     'apiPass' =>  $phpArray['api_pass'],
        //     'session_user' =>auth()->user()->email,
        //     'group_id'=> $group_id,
        //     'itemrank'=>$itemrank
        // ];

        // // dd($postData);

        // $ch = curl_init($apiEndpoint);

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // $response = curl_exec($ch);
        // curl_close($ch);

        // $api_response = json_decode($response, true);
        // return  $api_response;

    }


    function skill_campaigns_update($organization_services_id,$campaign_id ,$level, $extension){
        $itemrank = "RANK_{$extension}={$level}&GRADE_{$extension}={$level}";
        $OrganizationServices = OrganizationServices::find($organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'EditCampaignRanks',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>auth()->user()->email,
            'campaign_id'=> $campaign_id,
            'itemrank'=>$itemrank
        ];

        // dd($postData);

        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);
        return  $api_response;

    }





    function save_bulk_agents(Request $request){
        foreach($request->email as  $i=>$new_user){

            $org_user = user::where('id',$request->email[$i])->first();


    

            $active = isset($request->status) && $request->status == 1 ? 'Y' : 'N';


            
            $OrganizationServices = OrganizationServices::find($request->organization_servicesID);
            $phpArray = json_decode($OrganizationServices->connection_parameters, true);

            
            $Agent_detail= $this->get_agent_detail($request->organization_servicesID ,$request->other_user);

            $options_value = $Agent_detail['data'];
            

            $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
            // POST data
            $postData = [
                'Action' => 'AddUser',
                'apiUser' =>  $phpArray['api_user'],
                'apiPass' =>  $phpArray['api_pass'],
                'session_user' => auth()->user()->email,
                'responsetype' => 'json',

                'user' => $request->extension[$i],

                // 'pass' => $api_password,
                'full_name' => $request->full_name[$i],
                'user_group' =>$options_value['user_group'],
                'active' => $active,
                'email' => $org_user->email,

                'mobile_number' =>  $options_value['mobile_number'],
                'agent_choose_ingroups'=> $options_value['agent_choose_ingroups'],
                'agent_choose_blended'=> $options_value['agent_choose_blended'],
                'closer_default_blended'=> $options_value['closer_default_blended'],
                'scheduled_callbacks'=> $options_value['scheduled_callbacks'],
                'agentonly_callbacks'=> $options_value['agentonly_callbacks'],
                'agentcall_manual'=> $options_value['agentcall_manual'],
                'agent_call_log_view_override'=> $options_value['agent_call_log_view_override'],
                'inbound_calls_limit'=> $options_value['inbound_calls_limit'],

            ];
            $ch = curl_init($apiEndpoint);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);

            $api_response = json_decode($response, true);

            // dd($api_response);

            if ($api_response['result'] == 'success') {
                $add_userAgent = new userAgent();
                $add_userAgent->orgid = $request->orgID;
                $add_userAgent->org_user_id = $org_user->id;
                $add_userAgent->services_id = $request->organization_servicesID;
                $add_userAgent->api_user = $request->extension[$i];
                $add_userAgent->password = $api_response['pass_hash_encrypted'];
                $add_userAgent->name =$request->agent_name;
                $add_userAgent->status =$request->status;
                $add_userAgent->save();
            } else {
                return redirect()->back()->with('error', 'Something Went Wrong');
            }

        }
    
        return redirect()->back()->with('success', 'New agent added successfully.');
    }



    function emergency_logout(Request $request)  {

        $OrganizationServices = OrganizationServices::find($request->organization_servicesID);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Dashboard/API.php';
        // POST data
        $postData = [
            'Action' => 'EmergencyLogout',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' => auth()->user()->email,
            'responsetype' => 'json',

            'apiUserAgent' => $request->extension,

        ];
        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);

        return  $api_response;


    }

    function bulk_action(Request $request)  {


        $return_response = [];
        array_push($return_response, ['key' => $request->actionType]);

        
        $OrganizationServices = OrganizationServices::find($request->organization_servicesID);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);


        foreach($request->extension as $extension){
            if($request->actionType == 'emergency'){
                $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Dashboard/API.php';
                  // POST data
                $postData = [
                    'Action' => 'EmergencyLogout',
                    'apiUser' =>  $phpArray['api_user'],
                    'apiPass' =>  $phpArray['api_pass'],
                    'session_user' => auth()->user()->email,
                    'responsetype' => 'json',
    
                    'apiUserAgent' => $extension,
    
                ];
    
            }elseif($request->actionType == 'disable'){
    
            //     $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/disable/API.php';
            //     // POST data
            //   $postData = [
            //       'Action' => 'disable',
            //       'apiUser' =>  $phpArray['api_user'],
            //       'apiPass' =>  $phpArray['api_pass'],
            //       'session_user' =>  $phpArray['api_user'],
            //       'responsetype' => 'json',
    
            //       'apiUserAgent' => $request->extension,
    
            //   ];
    
    
            }elseif($request->actionType == 'delete'){
                // $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/delete/API.php';
                // // POST data
                // $postData = [
                //     'Action' => 'delete',
                //     'apiUser' =>  $phpArray['api_user'],
                //     'apiPass' =>  $phpArray['api_pass'],
                //     'session_user' =>  $phpArray['api_user'],
                //     'responsetype' => 'json',
    
                //     'apiUserAgent' => $request->extension,
    
                // ];
            }

            $ch = curl_init($apiEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);
            $api_response = json_decode($response, true);

            // Convert $api_response to a string if it's an array
                if (is_array($api_response)) {
                    $api_response = json_encode($api_response); // Convert array to JSON string
                }

                if (strpos($api_response, 'Error:') === false) {
                    array_push($return_response, ['value' => $extension]);
                }            
        }

        return $return_response;

    }

    public function check_activity(Request $request)
    {

  
        $OrganizationServices = OrganizationServices::find($request->organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAgentLog',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'start_date'=>$request->startDate,
            'end_date'=>$request->endDate,
            'agentlog'=>'userlog',
            'user' => $request->extension,

        ];
        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);
        return  $api_response;

    }


    public function check_call_log(Request $request)
    {
        $responce_data = [];
        $data = [];
    
        $OrganizationServices = OrganizationServices::find($request->organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
    
        foreach ($request->selected_table as $filter) {
            $postData = [
                'Action' => 'GetAgentLog',
                'apiUser' => $phpArray['api_user'],
                'apiPass' => $phpArray['api_pass'],
                'session_user' => auth()->user()->email,
                'responsetype' => 'json',
                'start_date' => $request->startDate,
                'end_date' => $request->endDate,
                'agentlog' => strtolower($filter),
                'user' => $request->extension,
            ];
    
            $ch = curl_init($apiEndpoint);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);
    
            $api_response = json_decode($response, true);
    
            if ($api_response['result'] == 'success' && $api_response['data'] != null) {
                // Use filter as key in the response_data array
                $responce_data[$filter] = $api_response['data'];
            }
        }
        return $responce_data;
    }

    


    public function log($service , $organization_services_id ,$AgentID){
        $tab = 'call-Logs';
        session()->put('tab', $tab);
        return redirect('services/dialer/agents/edit/'.$organization_services_id .'/'.$AgentID);
    }

   



    //update the skill inblund  tab record in edit agent 
    function update_skill_inbound(Request $request){

        $itemrank = "agent_rank_table_length=10&CHECK_{$request->extension}={$request->invited}&RANK_{$request->extension}={$request->group_grade}&GRADE_{$request->extension}={$request->group_grade}";
        $OrganizationServices = OrganizationServices::find($request->organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Inbound/API.php';
        // POST data
        $postData = [
            'Action' => 'EditAgentRank',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>auth()->user()->email,
            'inbound_id'=> $request->inbound_id,
            'itemrank'=>$itemrank
        ];



        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);


        return  $api_response;

    }

     //update the skill outbound  tab record in edit agent 
     function update_skill_outbound(Request $request){

        $itemrank = "RANK_{$request->extension}={$request->campaign_grade}&GRADE_{$request->extension}={$request->campaign_grade}";
        $OrganizationServices = OrganizationServices::find($request->organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'EditCampaignRanks',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>auth()->user()->email,
            'campaign_id'=> $request->campaign_id,
            'itemrank'=>$itemrank
        ];

        // dd($postData);

        $ch = curl_init($apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($response, true);
        return  $api_response;


    }


    //update the skill update_inblound_call_limit  tab record in edit agent 
    function update_inblound_call_limit(Request $request) {   

        // dd($request->all());
        
        $OrganizationServices = OrganizationServices::find($request->organization_services_id);

        $phpArray = json_decode($OrganizationServices->connection_parameters, true);

        $Agent_detail= $this->get_agent_detail($request->organization_services_id ,$request->extension);
        $options_value = $Agent_detail['data'];
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            
            'Action' => 'EditUser',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'user' => $request->extension,

            'full_name'=> $options_value['full_name'],
            'user_group'=> $options_value['user_group'],
            'active' =>$options_value['active'],
            'voicemail_id'=>  $options_value['voicemail_id'],
            'email'=> $options_value['email'],
            'mobile_number' => $options_value['full_name'],
            // 'agent_choose_ingroups'=> $options_value['agent_choose_ingroups'],
            // 'agent_choose_blended'=> $options_value['agent_choose_blended'],
            // 'agent_choose_blended'=>$options_value['agent_choose_blended'],
            // 'scheduled_callbacks'=> $options_value['scheduled_callbacks'],
            // 'agentonly_callbacks'=> $options_value['agentonly_callbacks'],
            // 'agentcall_manual'=> $options_value['agentcall_manual'],
            // 'agent_call_log_view_override'=>$options_value['agent_call_log_view_override'],
            'max_inbound_calls1'=> $request->max_inbound_calls1,
            
           
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

            return 'success';
        }else{
            return 'error';
        }
          



    }

   
}
