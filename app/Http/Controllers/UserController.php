<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    //

    public function index($service ,$serviceID){
        $service_Users =  $this->get_user($serviceID);
        //get users of logged in user's organization...
        $user = Auth::user();

        // dd($user);
        $organization = $user->organizations->first();
        if(!$user->hasRole('admin')){
            abort(403);
        }
        $users = $organization->users;
        // dd($users);
        return view('dialer.Agent.index',compact('users','service','service_Users' ,'serviceID'));
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
}
