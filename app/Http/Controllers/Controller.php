<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Service;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $services;

    protected function getServices(){
        return Service::all();
    }
}
