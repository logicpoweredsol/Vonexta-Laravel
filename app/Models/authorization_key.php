<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class authorization_key extends Model
{
    use HasFactory;


    function associatedPath(){
        return $this->hasMany('App\Models\authorization_key_rule' , 'authorization_key_id' , 'id');
    }

}
