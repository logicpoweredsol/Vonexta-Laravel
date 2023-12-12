<?php

use App\Models\OrganizationServices;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserOrganizationController;
use App\Http\Controllers\OrganizationServicesController;
use \App\Http\Controllers\ServiceController;
use \App\Http\Controllers\PermissionController;
use \App\Http\Controllers\SuperAdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::get('reset-password', [ProfileController::class, 'reset_password'])->name('reset-password');


    // //Extra Route :
    // Route::post('get-permissions-attribute', [PermissionController::class, 'get_permissions'])->name('permissions');
    

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('services/{service}')->group(function() {
        Route::prefix('users')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('services.users');
            Route::get('/add', [UserController::class, 'add'])->name('services.users.new');
        });
        Route::middleware(['role_or_permission:admin|view campaigns'])->prefix('campaigns')->group(function() {
            Route::get('/', [CampaignController::class, 'index'])->name('services.campaigns');
            Route::get('/add', [CampaignController::class, 'add'])->name('services.campaigns.new');
        });
    });

    Route::prefix('administration')->group(function() {
        Route::middleware(['role_or_permission:admin|view users'])->group(function() {
            Route::prefix('users')->group(function(){
                Route::get('/', [SystemUserController::class, 'index'])->name('administration.users');
                Route::get('/add', [SystemUserController::class, 'add'])->name('administration.users.new');
                Route::post('/add', [SystemUserController::class, 'store'])->name('administration.users.store');
                Route::get('/edit/{user}', [SystemUserController::class, 'view'])->name('administration.users.view');
                Route::put('/edit/{user}',[SystemUserController::class, 'edit'])->name('administration.users.edit');
                Route::delete('/delete/{user}',[SystemUserController::class, 'delete'])->name('administration.users.delete');
            });
        });
    });
});

require __DIR__.'/auth.php';

Route::prefix('organizations')->middleware(['role:superadmin','auth'])->group(function(){
    Route::get('/', [OrganizationController::class, 'index'])->name('organizations');
    Route::get('/add', [OrganizationController::class, 'add'])->name('organizations.new');
    Route::post('/add', [OrganizationController::class, 'save'])->name('organizations.save');
    Route::get('/edit/{organization}', [OrganizationController::class, 'view'])->name('organizations.view');
    Route::put('/edit/{organiztion}',[OrganizationController::class, 'edit'])->name('organizations.edit');
   
    Route::prefix('/services')->group(function(){
        Route::POST('/add-service',[OrganizationServicesController::class, 'add'])->name('organizations.services.add-service');
        Route::post('/edit/{id}',[OrganizationServicesController::class, 'edit'])->name('organization.services.edit');
        Route::POST('/update-service',[OrganizationServicesController::class, 'update'])->name('organizations.services.update-service');
        Route::delete("/delete/{id}",[OrganizationServicesController::class, 'delete'])->name('organizations.services.delete');

        Route::post('/get_service_type',[OrganizationServicesController::class, 'get_service_type'])->name('organizations.services.get_service_type');
        // Route::post('/add',[OrganizationServicesController::class, 'add'])->name('organizations.services.new');
        // Route::post('/add',[OrganizationServicesController::class, 'add'])->name('organizations.services.new');
        // Route::put('/update_connection_parameters/{id}',[OrganizationServicesController::class, 'updateConnectionParameters'])->name('organizations.services.connection_parameters');
    });

    Route::prefix('/user')->group(function(){
        Route::post('/store',[UserOrganizationController::class, 'store'])->name('organizations.user.store');
        Route::get('/edit/{user_id}/{org_id}',[UserOrganizationController::class, 'edit']);
        Route::post('/update',[UserOrganizationController::class, 'update'])->name('organization.user.update');
        Route::post("/delete",[UserOrganizationController::class, 'delete'])->name('organizations.user.delete');

        Route::post('/check-user-email',[UserOrganizationController::class, 'check_user_email'])->name('organizations.user.check-user-email');
    });
});


Route::prefix('accounts')->middleware(['role:superadmin','auth'])->group(function(){
    Route::get('/',[SuperAdminController::class, 'index'])->name('accounts');
    Route::get('/add',[SuperAdminController::class, 'add'])->name('accounts.new');
    Route::post('/store',[SuperAdminController::class, 'store'])->name('accounts.store');
    Route::get('/edit/{user}',[SuperAdminController::class, 'edit'])->name('accounts.edit');
    Route::post('/update',[SuperAdminController::class, 'update'])->name('accounts.update');
    Route::post("/delete",[SuperAdminController::class, 'delete'])->name('accounts.delete');

});


//Route::prefix('services')->middleware(['auth','role:superadmin'])->group(function(){
//    Route::get('/',[ServiceController::class,'index'])->name('services');
//    Route::get('/add',[ServiceController::class,'add'])->name('services.new');
//    Route::post('/add', [ServiceController::class, 'save'])->name('services.save');
//    Route::get('/edit/{service}', [ServiceController::class, 'view'])->name('services.view');
//    Route::put('/edit/{service}',[ServiceController::class, 'edit'])->name('services.edit');
//});
