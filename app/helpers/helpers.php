<?php
use App\Models\UserOrganization;
use App\Models\Organization;
use App\Models\UserHaveService;
use App\Models\Service;
use App\Models\OrganizationServices;
use App\Models\Automation;
use App\Models\User;
use App\Models\userAgent;



function get_user_nav($user_id) {
    $userServices = Service::with(['user_have_service' => function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        }])
        ->orderBy('id', 'ASC')
        ->get();
    return $userServices;
}



function get_serive_type_id($org_ser_id) {
    $Organization = OrganizationServices::where('id',$org_ser_id)->first();
    return  $Organization->service_id;
}



function ceck_service_detail($organization_service_id) {
    // API endpoint

    $status = false;
    $OrganizationServices = OrganizationServices::find($organization_service_id);

    if( $OrganizationServices  != null &&  $OrganizationServices  !=""){
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

        // Decode the JSON response
        $responseData = json_decode($response, true);

        if( $responseData && isset($responseData['result']) && $responseData['result'] === 'success'){
            $status = true;
        }

    }



    return $status;
}


function get_group_user($organization_service_id) {
    // API endpoint

    $status = false;
    $OrganizationServices = OrganizationServices::find($organization_service_id);

    if( $OrganizationServices  != null &&  $OrganizationServices  !=""){
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/UserGroups/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllUserGroups',
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

        // Decode the JSON response
        $responseData = json_decode($response, true);

        if( $responseData && isset($responseData['result']) && $responseData['result'] === 'success'){
            $status = $responseData['user_group'];
        }

    }
    return $status;
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
    
    $responcc = json_decode($response, true);

    return $responcc;
   
   
}



function get_email($agent) {
    $userAgent = userAgent::with('user_detail')->where('api_user',$agent)->first();
    return  $userAgent->user_detail->email;
}


