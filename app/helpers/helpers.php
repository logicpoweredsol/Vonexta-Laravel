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



function get_serive_id($org_ser_id) {
    $Organization = OrganizationServices::where('id',$org_ser_id)->first();
    return  $Organization->service_id;
}

