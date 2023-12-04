<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_services')
            ->withPivot(['id', 'connection_parameters', 'service_name'])
            ->withTimestamps();
    }
}
