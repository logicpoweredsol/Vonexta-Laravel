<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAgent extends Model
{
    use HasFactory;


    function user_detail(){
        return $this->hasOne('App\Models\User' , 'id' , 'org_user_id');
    }

    function agent_organization(){
        return $this->hasOne('App\Models\OrganizationServices' , 'id' , 'services_id');
    }


}
