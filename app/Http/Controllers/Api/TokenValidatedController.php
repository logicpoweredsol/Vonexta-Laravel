<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\accessKey;


class TokenValidatedController extends Controller
{

    public function GetAgentDetails(Request $request){
        if(isset($request->access_key)){
            $accessKey = accessKey::with('agent_password','serviceDetail')->where('access_key', $request->access_key)->first();

            // dd($accessKey);
    
            if($accessKey != null){
                $currentTime = now();
                $createdAt = $accessKey->created_at;
    
                $timeDifference = $currentTime->diffInMinutes($createdAt);
    
                if ($timeDifference < 10){

                    $service_url_ison = $accessKey->serviceDetail->connection_parameters;
                    $data = json_decode($service_url_ison, true);
                    $serverUrl = $data['server_url'];


                    if(isset($request->destroy_token) && $request->destroy_token == 1){
                        $accessKey->access_key = "";
                        $accessKey->save();
                        $response = [
                            "success" => true,
                            "message" => "Access Key validated and destroyed successfully.",
                            "data" => [
                                "service_url" => $serverUrl,
                                "extension" => $accessKey->extension,
                                "access_string" => $accessKey->agent_password->password,
                                "access_key_destroyed" => true
                            ]
                        ];
                    } else {
                        $response = [
                            "success" => true,
                            "message" => "Access Key validated successfully.",
                            "data" => [
                                "service_url" => $serverUrl,
                                "extension" => $accessKey->extension,
                                "access_string" => $accessKey->agent_password->password
                            ]
                        ];
                    }
                } else {
                    $response = [
                        "success" => false,
                        "message" => "Access Key is older than 10 minutes.",
                        "data" => []
                    ];
                }
            } else {
                $response = [
                    "success" => false,
                    "message" => "Access Key is not existing.",
                    "data" => []
                ];
            }
    
        } else {
            $response = [
                "success" => false,
                "message" => "Access Key is required.",
                "data" => []
            ];
        } 
    
        return response()->json($response);
    }

    

    
}
