<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accessKey extends Model
{

    protected $table = 'access_keys';

    // function service_detail(){
    //     return $this->hasOne('App\Models\UserHaveService' , 'service_type' , 'extension');
    // }

    function agent_password(){
        return $this->hasOne('App\Models\userAgent' , 'api_user' , 'extension');
    }

    function serviceDetail(){
        return $this->hasOne('App\Models\OrganizationServices' , 'id' , 'service_id');
    }



}
