<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAutomations extends Model
{
    use HasFactory;
    protected $table = 'organization_automation';

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }
}
