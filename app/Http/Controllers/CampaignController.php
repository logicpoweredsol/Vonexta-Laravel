<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\OrganizationServices;

class CampaignController extends Controller
{

    public function index($service ,$organization_servicesID){
        $user = Auth::user();
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin') || !$user->hasPermissionTo('view campaigns')){
            abort(403);
        }
        $get_compaignSkills =$this->get_compaigns($organization_servicesID);

    
        return view('dialer.campaigns.index',compact('service' ,'organization_servicesID','get_compaignSkills'));
    }


    public function GetCustomAttributes($organization_servicesID)
    {
        $OrganizationServices = OrganizationServices::find($organization_servicesID);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/SystemSettings/API.php';
        // POST data
        $postData = [
            'Action' => 'GetCustomAttributes',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'module'=>'Profiles'
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



    public function get_compaigns($organization_service_id)
    {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllCampaigns',
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

    public function get_contact($organization_service_id,$CampaignID)
    {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Lists/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllListsCampaign',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'campaign_id' => $CampaignID

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


    public function edit($service , $organization_service_id , $CampaignID)
    {
        $velocity = "";
        $get_Scripts = "";
        $get_lists = "";
        $edit_compaigns= "";
       $GetCustomAttributes = '';

        $edit_compaign  =  $this->fetch_compaigns_detail($organization_service_id,$CampaignID);
        if($edit_compaign['result'] == "success")
        {
            $edit_compaigns = $edit_compaign;
        }

        if( $edit_compaigns['data']['dial_method']  == 'RATIO'){
            $velocity = $edit_compaigns['data']['auto_dial_level'];

        }elseif($edit_compaigns['data']['dial_method']  == 'ADAPT_TAPERED'){
            $velocity = $edit_compaigns['data']['adaptive_maximum_level'];

        }elseif($edit_compaigns['data']['dial_method']  == 'INBOUND_MAN'){
            $velocity = $edit_compaigns['data']['manual_auto_next'];
        }


        $get_Scripts_responce = $this->get_Scripts($organization_service_id);
        $lists = $this->get_contact($organization_service_id ,$CampaignID);

        if($lists['result'] == 'success')
        {
            $get_lists = $lists;
        }
        

       if($get_Scripts_responce['result'] == 'success'){
        $get_Scripts = $get_Scripts_responce;
       }

       $GetCustomAttributes_responce = $this->GetCustomAttributes($organization_service_id);

       if ($GetCustomAttributes_responce['result'] == 'success') {
            $GetCustomAttributes = json_encode( $GetCustomAttributes_responce['data']);
        }


        return view('dialer.campaigns.edit' ,compact('edit_compaigns','get_Scripts','get_lists' ,'organization_service_id' ,'velocity' , 'GetCustomAttributes'));
    }


    public function fetch_compaigns_detail($organization_service_id,$CampaignID)
    {

        $OrganizationServices = OrganizationServices::find($organization_service_id);

        
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';

        // POST data
        $postData = [
            'Action' => 'GetCampaignInfo',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' => auth()->user()->email,
            'responsetype' => 'json',
            'campaign_id'=> $CampaignID,
        ];

        $ch = curl_init($apiEndpoint);
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);

    }



    function get_Scripts($organization_service_id) {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Scripts/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllScripts',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'Field'=> 'script_name',
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


    public function update(Request $request)
    {

        // dd($request->all());
        $outboundprofile_data  =  $this->fetch_compaigns_detail( $request->organization_service_id, $request->profile_id);

        $request->validate([
            'status' => 'required|in:Y,N',
        ]);
        $organization_service_id = $request->organization_service_id;
        $profile_id = $request->profile_id;
        $profile_name = $request->profile_name;
        $type = $request->type;
        $sequence = $request->sequence;

        // dd($sequence);
        $speed = 0;
        if(isset($request->field_55)){
            $speed =  $request->field_55;
        }

        $profile_script = $request->profile_script;
        $status = $request->status;

        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'EditCampaign',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'campaign_id'=> $profile_id,
            "campaign_name" =>$profile_name,
            "lead_order" =>$sequence,
            "dial_method" =>$type,
            "campaign_script" =>$profile_script,
            "active" => $status,
            'custom_attributes' =>$outboundprofile_data['data']['custom_attributes'],
        ];

        // dd($postData);


        if ($type == 'RATIO') {
            $postData["auto_dial_level"] = $speed;
            $postData["adaptive_maximum_level"] = '1';
            $postData["manual_auto_next"] = "0";
    
        } elseif ($type == 'ADAPT_TAPERED') {
            $postData["auto_dial_level"] = '1';
            $postData["adaptive_maximum_level"] = $speed;
            $postData["manual_auto_next"] = "0";
        
        } elseif ($type == 'INBOUND_MAN') {
            if ($speed == 0) {
                $postData["manual_auto_next"] = '0';
                $postData["auto_dial_level"] = '1';
                $postData["adaptive_maximum_level"] = '1';
            } else {

                $postData["manual_auto_next"] = $speed;
                $postData["auto_dial_level"] = '1';
                $postData["adaptive_maximum_level"] = '1';
            }
        }

        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);
        // Decode the JSON response
        $responseArray = json_decode($response, true);
        // Check if the response indicates success
        if(isset($responseArray['result']) && $responseArray['result'] == 'success'){
            return redirect()->back()->with('success', 'Updated successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
         }



    }



    function Servicescampaigns_customAttributes(Request $request){


        
        $requestData = $request->all();


        // Define keys that you want to exclude
        $excludeKeys = ['_token', 'organization_service_id', 'profile_id'];

        // Filter out the keys you want to exclude
        $filteredData = array_filter($requestData, function($key) use ($excludeKeys) {
            return !in_array($key, $excludeKeys);
        }, ARRAY_FILTER_USE_KEY);


        // Convert the modified data to JSON
        $jsonData = json_encode($filteredData);
        
        $outboundprofile_data  =  $this->fetch_compaigns_detail($request->organization_service_id,$request->profile_id);
        

        $OrganizationServices = OrganizationServices::find($request->organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'EditCampaign',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'campaign_id'=> $request->profile_id,
            "campaign_name" =>$outboundprofile_data['data']['campaign_name'],
            "lead_order" =>$outboundprofile_data['data']['lead_order'],
            "dial_method" =>$outboundprofile_data['data']['dial_method'],
            "campaign_script" =>$outboundprofile_data['data']['campaign_script'],
            "active" => $outboundprofile_data['data']['active'],
            "auto_dial_level" =>$outboundprofile_data['data']['auto_dial_level'],
            "adaptive_maximum_level" =>$outboundprofile_data['data']['adaptive_maximum_level'],
            "manual_auto_next" =>$outboundprofile_data['data']['manual_auto_next'],
            'custom_attributes' =>$jsonData
            
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
        // Decode the JSON response
        $responseArray = json_decode($response, true);
        // Check if the response indicates success
        if(isset($responseArray['result']) && $responseArray['result'] == 'success'){
            return redirect()->back()->with('success', 'Updated successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
         }




        
    }



    public function update_profile_in_db_custom_attribute(Request $request){


        $requestData = $request->all();

        // Define keys that you want to exclude
        $excludeKeys = ['User', 'organization_services_id', '_token'];

        // Filter out the keys you want to exclude
        $filteredData = array_filter($requestData, function($key) use ($excludeKeys) {
            return !in_array($key, $excludeKeys);
        }, ARRAY_FILTER_USE_KEY);


        // Convert the modified data to JSON
        $jsonData = json_encode($filteredData);



        $OrganizationServices = OrganizationServices::find($request->organization_services_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
 
        $Agent_detail= $this->get_agent_detail($request->organization_services_id,$request->User);

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
 
             'full_name'=> $options_value['full_name'],
             'user_group'=>  $options_value['user_group'],
             'active' => $options_value['active'],
             'voicemail_id'=> $options_value['voicemail_id'],
             'email'=>$options_value['email'],
             'mobile_number' =>  $options_value['mobile_number'],
             'hotkeys_active' => 0,
             'user_level' => 1,
             'vicidial_recording_override' => "DISABLED",
             'agent_lead_search_override' => "DISABLED",
             'vdc_agent_api_access' =>1,
             'modify_same_user_level' =>'',
             'custom_attributes' =>$jsonData,

         ];


        //  dd($postData);
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

}
