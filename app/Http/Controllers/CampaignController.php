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


    // public function all_compaigns($service, $organization_servicesID)
    // {
    //     $compaigns_result = $this->get_compaigns($organization_servicesID);

    //     dd($compaigns_result);
    //     $compaigns = "";

    //     if ($compaigns['result'] == 'success') {
    //         $compaigns =  $compaigns_result;
    //     }

    // }


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
}
