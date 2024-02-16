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
       
        return view('dialer.campaigns.index',compact('service' ,'organization_servicesID','get_compaignSkills'));
    }

    public function add(){

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

        $get_Scripts = "";
        $get_lists = "";
        $edit_compaigns  =  $this->fetch_compaigns_detail($organization_service_id,$CampaignID);
        $get_Scripts_responce = $this->get_Scripts($organization_service_id);
        $lists = $this->get_contact($organization_service_id ,$CampaignID);

        if($lists['result'] == 'success')
        {
            $get_lists = $lists;
        }
        
       if($get_Scripts_responce['result'] == 'success'){
        $get_Scripts = $get_Scripts_responce;
       }




        return view('dialer.campaigns.edit' ,compact('edit_compaigns','get_Scripts','get_lists'));
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
            'session_user' =>  auth()->user()->email,
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
    
        // Close cURL session
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
        dd($request->all());
    }





}
