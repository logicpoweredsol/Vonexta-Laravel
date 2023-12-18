<?php
use App\Models\UserOrganization;
use App\Models\Organization;
use App\Models\UserHaveService;
use App\Models\Service;
use App\Models\OrganizationServices;
use App\Models\Automation;
use App\Models\User;



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
    $OrganizationServices = OrganizationServices::find($organization_service_id);
    $phpArray = json_decode($OrganizationServices->connection_parameters, true);
    $status = false;
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

    return $status;
}


