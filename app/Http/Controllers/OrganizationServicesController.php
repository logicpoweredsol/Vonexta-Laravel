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

    public  function add(Request  $request){
        $serviceId = $request->input('service_id');
        $organizationId = $request->input('organization_id');

        $service = Service::find($serviceId);
        $organization = Organization::find($organizationId);

//        $organization->services()->attach($service, [
//            'connection_parameters' => json_encode(json_decode($service->connection_parameters)),
//            'service_name' => $request->input('service_name'),
//        ]);
        $organizationServiceId = OrganizationServices::insertGetId([
            'organization_id' => $organization->id,
            'service_id' => $service->id,
            'connection_parameters' => json_encode(json_decode($service->connection_parameters)),
            'service_name' => $request->input('service_name'),
        ]);
//        $request->session()->flash('msg', 'Service added successfully');
        return response()->json([
            "status" => true,
            "msg" => "Service added to organization",
            "data" => OrganizationServices::find($organizationServiceId),
        ], 200);
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

    public function updateConnectionParameters($id, Request $request){
        $os = OrganizationServices::find($id);
        if (!$os) {
            return response()->json(['error' => 'Organization service not found.'], 404);
        }
        $connection_params= [];
        foreach($request->param_keys as $index => $param_key){
            $connection_params[$index] = $param_key;
        }
        if(null!==$request->service_name){
            $os->service_name = $request->service_name;
        }
        $os->connection_parameters = json_encode($connection_params);
        $os->save();
        return response()->json([
            "status" => true,
            "msg" => "Service updated",
            "data" => $os
        ],200);
    }

    public function delete($osId){
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
}
