<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Auth;

class OrganizationServicesController extends Controller
{
    //

//     public  function add(Request  $request){
//         $serviceId = $request->input('service_id');
//         $organizationId = $request->input('organization_id');

//         $service = Service::find($serviceId);
//         $organization = Organization::find($organizationId);

// //        $organization->services()->attach($service, [
// //            'connection_parameters' => json_encode(json_decode($service->connection_parameters)),
// //            'service_name' => $request->input('service_name'),
// //        ]);
//         $organizationServiceId = OrganizationServices::insertGetId([
//             'organization_id' => $organization->id,
//             'service_id' => $service->id,
//             'connection_parameters' => json_encode(json_decode($service->connection_parameters)),
//             'service_name' => $request->input('service_name'),
//         ]);
// //        $request->session()->flash('msg', 'Service added successfully');
//         return response()->json([
//             "status" => true,
//             "msg" => "Service added to organization",
//             "data" => OrganizationServices::find($organizationServiceId),
//         ], 200);
//     }


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
        $add_OrganizationServices->service_id  =  $request->org_serice_id;
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
        $update_OrganizationServices->service_name = $this->generateSlug($request->srviceName);
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
        $serviceId = $request->service_id;
        $service = Service::find($serviceId);
       return $service;
    }

    public function generateSlug($name) {
        $slug = strtolower(str_replace(' ', '-', $name));
        return $slug;
    }


    


   




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
