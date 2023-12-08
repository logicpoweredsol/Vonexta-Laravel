<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    use HasFactory;

    protected $table = 'user_organizations';

    protected  $fillable = [
        'user_id',
        'organization_id',
        "is_organization_admin"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }



    function organization_user(){
        return $this->hasOne('App\Models\User' , 'id' , 'user_id');
        
    }



}
