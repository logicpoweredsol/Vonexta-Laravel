<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    use HasFactory;

    protected $table='automations';

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_automation')
//            ->withPivot('connection_parameters')
            ->withTimestamps();
    }
}
