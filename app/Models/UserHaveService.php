<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHaveService extends Model
{
    use HasFactory;

    function user_have_service(){
        return $this->hasOne('App\Models\OrganizationServices' , 'id' , 'organization_services_id');
    }
    
}
