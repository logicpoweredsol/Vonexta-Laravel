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
        //get users of logged in user's organization...
        $user = Auth::user();
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin') || !$user->hasPermissionTo('view campaigns')){
            abort(403);
        }
       $get_compaignSkills =$this->get_compaigns($organization_servicesID);

    //    dd($get_compaignSkills);
        return view('dialer.campaigns.index',compact('service' ,'organization_servicesID','get_compaignSkills'));
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
        $edit_compaigns  =  $this->fetch_compaigns_detail($organization_service_id,$CampaignID);


        // dd( $edit_compaigns);



        if( $edit_compaigns['data']['dial_method']  == 'RATIO'){
            $velocity = $edit_compaigns['data']['auto_dial_level'];

        }elseif($edit_compaigns['data']['dial_method']  == 'ADAPT_TAPERED'){
            $velocity = $edit_compaigns['data']['adaptive_maximum_level'];

        }elseif($edit_compaigns['data']['dial_method']  == 'INBOUND_MAN'){
            if($edit_compaigns['data']['adaptive_maximum_level'] == "0" || $edit_compaigns['data']['adaptive_maximum_level'] == 0){
                $velocity = $edit_compaigns['data']['adaptive_maximum_level'];
            }else{
                $velocity = $edit_compaigns['data']['manual_auto_next'];
            }
        }

        // dd($velocity);


  

        $get_Scripts_responce = $this->get_Scripts($organization_service_id);
        $lists = $this->get_contact($organization_service_id ,$CampaignID);

        if($lists['result'] == 'success')
        {
            $get_lists = $lists;
        }
        
       if($get_Scripts_responce['result'] == 'success'){
        $get_Scripts = $get_Scripts_responce;
       }


        return view('dialer.campaigns.edit' ,compact('edit_compaigns','get_Scripts','get_lists' ,'organization_service_id' ,'velocity'));
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
        $request->validate([
            'status' => 'required|in:Y,N', // Ensure the status is either Y or N
        ]);

        

        $organization_service_id = $request->organization_service_id;
        $profile_id = $request->profile_id;

        $profile_name = $request->profile_name;
        $type = $request->type;
        $sequence = $request->sequence;

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
        ];





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
                $postData["manual_auto_next"] = 0;
                $postData["auto_dial_level"] = 1;
                $postData["adaptive_maximum_level"] = 1;
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

        // dd( $responseArray);

        // Check if the response indicates success
        if(isset($responseArray['result']) && $responseArray['result'] == 'success'){
            return redirect()->back()->with('success', 'Updated successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
         }



    }

}
