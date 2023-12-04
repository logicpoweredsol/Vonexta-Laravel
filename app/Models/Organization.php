<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','phone','address','city','state','zip','active'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'organization_id', 'user_id')
            ->withPivot('is_organization_admin')
            ->withTimestamps();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'organization_services')
            ->withPivot(['id', 'connection_parameters', 'service_name'])
            ->withTimestamps();
    }

    public function automations()
    {
        return $this->belongsToMany(Automation::class, 'organization_automation')->withTimestamps();
    }

    public function attachService($service){
        $this->services()->attach($service);
    }

}
