<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use \App\Models\Service;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    //     $commonData = [
    //         'services' => Service::all(),
    //         'main_menu' => request()->segment(1),
    //         'sub_menu' => request()->segment(2),
    //         'sub_menu_main' => null!==request()->segment(3) && !is_numeric(request()->segment(3)) ? request()->segment(3) : '',
    //         'sub_menu_sub' => null!==request()->segment(4) && !is_numeric(request()->segment(4)) ? request()->segment(4) : '',
    //     ];
    //     View::share($commonData);
    //     Schema::defaultStringLength(125);
    // }
}
