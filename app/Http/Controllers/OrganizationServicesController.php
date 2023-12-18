<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class OrganizationServicesController extends Controller
{
    public function add(Request  $request){    
        $tab = 'Organization-service';
        $request->session()->put('tab', $tab);

        $formData = $request->input('formData');
        $decodedFormData = urldecode($formData);
        parse_str($decodedFormData, $parsedData);


        $connection_params= [];
        foreach($parsedData as $index => $param_key){
            $connection_params[$index] = $param_key;
        }

        $connection_parameters = json_encode($connection_params['param_keys']);

        $add_OrganizationServices = new OrganizationServices();
        $add_OrganizationServices->organization_id = $request->org_id;
        $add_OrganizationServices->service_id  =  $request->add_service_type;
        $add_OrganizationServices->connection_parameters = $connection_parameters;
        $add_OrganizationServices->service_nick_name = $request->serviceNiceName;
        $add_OrganizationServices->service_name = $this->generateSlug($request->serive_name);
        $add_OrganizationServices->save();
        
        $os = $request->serive_name;
        return response()->json([
            "status" => true,
            "msg" => "Service updated",
            "data" => $os
        ],200);


        
    }
    
    public function update(Request  $request){   

        $tab = 'Organization-service';
        $request->session()->put('tab', $tab);

        $formData = $request->input('formData');

        $decodedFormData = urldecode($formData);
        parse_str($decodedFormData, $parsedData);
        $connection_params= [];
        foreach($parsedData as $index => $param_key){
            $connection_params[$index] = $param_key;
        }
        $connection_parameters = json_encode($connection_params['param_keys']);
        $update_OrganizationServices =  OrganizationServices::where('id',$request->id)->first();
        $update_OrganizationServices->connection_parameters = $connection_parameters;
        $update_OrganizationServices->service_name = $this->generateSlug($request->serive_name);
        $update_OrganizationServices->service_nick_name = $request->serviceNiceName;
        $update_OrganizationServices->save();
        
        $os = $request->serive_name;
        return response()->json([
            "status" => true,
            "msg" => "Service updated",
            "data" => $os
        ],200);


        
    }

    
    public function edit($ogService, Request $request){
        $ogService = OrganizationServices::find($ogService);
        if(!$ogService){
            return  response()->json([
                "status" => false,
            ],404);
        }
        $connection_parameters = $ogService->service->connection_parameters;
        return response()->json([
            "ogService" => $ogService,
            "connection_parameters" => $connection_parameters
        ]);
    }

    public function delete($osId){


        $tab = 'Organization-service';
        session()->put('tab', $tab);

        $os = OrganizationServices::find($osId);
        
        if($os){
            $os->delete();
            return response()->json([
                "status" => true,
                "msg" => "Service deleted from organization",
            ],200);
        }
        return response()->json([
            "status" => false,
            "msg" => "No service found to delete.",
        ],404);
    }

    function get_service_type(Request $request) {
        $serviceId = $request->services_type;
        $service = Service::find($serviceId);
       return $service;
    }

    public function generateSlug($name) {
        $slug = strtolower(str_replace(' ', '-', $name));
        return $slug;
    }




    public function ceck_service_detail(Request $request) {
        // API endpoint

        $status = false;
        $apiEndpoint = 'https://' . $request->serverUrl . '/APIv2/Users/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllUsers',
            'apiUser' => $request->apiUser,
            'apiPass' => $request->apiPass,
            'session_user' => $request->apiUser,
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






    // public function ceck_service_detail(Request $request){


    //     $status = false;

    //     $response = Http::withoutVerifying()->post('https://vn21.vonexta.com/APIv2/Users/API.php', [
    //         'apiUser' => $request->apiUser,
    //         'apiPass' => $request->apiPass,
    //         'Action' => $request->Action,
    //         'session_user' => $request->session_user,
    //         'responsetype' => $request->responsetype,
    //         '_token' => $request->_token
    //     ]);
    
    //     // dd($response); // Uncomment this line for debugging purposes
    
    //      $responseData = $response->json();

        
    //      dd( $responseData);
    //     // Get the result status directly from the JSON-decoded response
    //     $resultStatus = $response['result'] ?? null;
    


    //     dd( $resultStatus);

    //     // Check if the result is success
    //     if ($resultStatus === 'success') {
    //         $status = true;
    //     }
    
    //     return $status;

    //     // $status = false;       
    //     // $response = Http::withoutVerifying()->post('https://vn21.vonexta.com/APIv2/Users/API.php', [
    //     //     'apiUser'=>$request->apiUser,
    //     //     'apiPass'=>$request->apiPass,
    //     //     'Action'=>$request->Action,
    //     //     'session_user'=>$request->session_user,
    //     //     'responsetype'=>$request->responsetype,
    //     //     '_token' => $request->_token
    //     // ]);
        
    //     // // Parse the response as JSON
    //     // $responseData = $response->json();
        

    //     // dd( $responseData);
    //     // // Get the result status
    //     // $resultStatus = $responseData['result'];
        
    //     // // Check if the result is success
    //     // if ($resultStatus == 'success') {
    //     //     $status = true;
    //     // }

    //     // return $status;

    // }


    


   




    // public function updateConnectionParameters(Request $request){

    //     $os = OrganizationServices::find($id);
    //     if (!$os) {
    //         return response()->json(['error' => 'Organization service not found.'], 404);
    //     }
    //     $connection_params= [];
    //     foreach($request->param_keys as $index => $param_key){
    //         $connection_params[$index] = $param_key;
    //     }
    //     if(null!==$request->service_name){
    //         $os->service_name = $request->service_name;
    //     }
    //     $os->connection_parameters = json_encode($connection_params);
    //     $os->save();
    //     return response()->json([
    //         "status" => true,
    //         "msg" => "Service updated",
    //         "data" => $os
    //     ],200);
    // }

    



    

}
