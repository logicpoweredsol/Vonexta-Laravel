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
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\AgentRolesConroller;
use \App\Http\Controllers\CustomAttributesController;
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




// use Illuminate\Support\Facades\Mail;
// use App\Mail\SendImpersonation;

// Route::get('/testmail',function(){
   
//     $code = "azhar";
//     Mail::to('azharkhancs@gmail.com')->send(new SendImpersonation($code));
       
//     dd("Email is sent successfully.");
// });



Route::get('/', function () {
    if(Auth::check()) {
        if(auth()->user()->hasRole('superadmin')){
            return redirect('/dashboard');
        }
        return redirect('/home');
    } else {
        return view('welcome');
    }
});

Route::get('home',[HomeController::class,'index'])->middleware(['auth', 'verified', 'checkUserStatus'])->name('home');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'checkUserStatus'])->name('dashboard');



Route::middleware(['auth','checkUserStatus'])->group(function () {
    Route::get('reset-password', [ProfileController::class, 'reset_password'])->name('reset-password');

    // //Extra Route :
    // Route::post('get-permissions-attribute', [PermissionController::class, 'get_permissions'])->name('permissions');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('services/{service}')->group(function() {
        Route::prefix('agents')->group(function() {
            Route::get('/{organization_services_id}', [UserController::class, 'index'])->name('services.agents');
            Route::get('/edit/{organization_services_id}/{AgentID}', [UserController::class, 'edit'])->name('services.agents.edit');
            Route::get('/detail/{organization_services_id}/{AgentID}', [UserController::class, 'detail'])->name('services.agents.detail');
            Route::post('/update-agent-details', [UserController::class, 'update_agent_in_db_detail'])->name('services.update-agent.details');
            Route::post('/update-agent-options', [UserController::class, 'update_agent_in_db_options'])->name('services.update-agent.options');
            Route::post('/add-custom-attribute',[CustomAttributesController::class,'store'])->name('services.store-custom-attribute');
            Route::post('/update-agent-custom_attribute', [UserController::class, 'update_agent_in_db_custom_attribute'])->name('services.update-agent.custom_attribute');
    

            Route::POST('/check-extension', [UserController::class, 'check_extension'])->name('services.extenxion-detail');
            Route::POST('/check-previous-api-name',[CustomAttributesController::class,'check_previous_api_name'])->name('services.check-previous-api');
            Route::POST('/copy-skill', [UserController::class, 'extension_skill'])->name('services.copy-skill');

            Route::post('/save-agent', [UserController::class, 'save_agents'])->name('services.save-agent');
            // Route::post('/create-session-variable', [UserController::class, 'save_agentcreate_session_variable'])->name('services.create-session-variable');
            Route::post('/bulk-agent', [UserController::class, 'save_bulk_agents'])->name('services.bulk-save-agent');

            Route::POST('/emergency-logout', [UserController::class, 'emergency_logout'])->name('services.emergency-logout');
            Route::POST('/bulk-action', [UserController::class, 'bulk_action'])->name('services.bulk-action');
            Route::POST('/activity',[UserController::class,'check_activity'])->name('services.activity');
            Route::POST('/call_log',[UserController::class,'check_call_log'])->name('services.call_log');
            Route::get('/log/{organization_services_id}/{AgentID}', [UserController::class, 'log'])->name('services.agents.log');

            Route::POST('/update-skills', [UserController::class, 'model_update_skills'])->name('services.agents.update-skills');

            //update the skill tab record in edit agent 
            Route::POST('/update_skill_inbound',[UserController::class,'update_skill_inbound'])->name('services.skill_inbound');
            Route::POST('/update_skill_outbound',[UserController::class,'update_skill_outbound'])->name('services.update_skill_outbound');
            //update the skill tab record in edit agent 

            Route::POST('/update_inblound_call_limit',[UserController::class,'update_inblound_call_limit'])->name('services.update_inblound_call_limit');


            Route::POST('/get_skill_inbound_level',[UserController::class,'get_skill_inbound_level'])->name('services.get_skill_inbound_level');
            Route::POST('/get_skill_outbound_level',[UserController::class,'get_skill_outbound_level'])->name('services.get_skill_outbound_level');
            // Route::POST('/inbound_Skills_on_add_agent',[UserController::class,'Skills_on_add_agent'])->name('services.Skills_on_add_agent');
            // Route::POST('/compaign_Skills_on_add_agent',[UserController::class,'compaign_Skills_on_add_agent'])->name('services.compaign_Skills_on_add_agent');
            
          
            //sydy this route
            // Routes for rendering compaign
            // Route::get('compaign', [CampaignController::class, 'all_compaigns'])->name('services.all_compaigns');
        });


        Route::prefix('admin')->group(function() {

            Route::get('agent-role/{organization_services_id}', [AgentRolesConroller::class, 'agentRole'])->name('services.agent-role');
            Route::POST('/add-agent-role', [AgentRolesConroller::class, 'addAgentRole'])->name('services.add-agent-role');
            Route::get('/agent-role-edit/{organization_services_id}/{user_group}', [AgentRolesConroller::class, 'edit'])->name('services.agent-role-edit');
            Route::POST('/agent-role-update', [AgentRolesConroller::class, 'agentRoleUpdate'])->name('services.agent-role-update');
            

            Route::get('custom-attribute/{organization_services_id}', [CustomAttributesController::class, 'customattributes'])->name('services.custom-attribute');

        });




        Route::middleware(['role_or_permission:admin|view campaigns'])->prefix('campaigns')->group(function() {
            Route::get('/{organization_services_id}',[CampaignController::class, 'index'])->name('services.campaigns');
            Route::get('/edit/{organization_services_id}/{CampaignID}',[CampaignController::class, 'edit'])->name('services.campaigns.edit');
            Route::post('/update',[CampaignController::class, 'update'])->name('services.campaigns.update');
            Route::post('/add-custom-attributes',[CustomAttributesController::class,'store_custom_attribute_in_outbound'])->name('add-custom-attributes');
            Route::post('/check-previous-api-name-outbound',[CustomAttributesController::class,'previous_api_name_outbound'])->name('check-previous-api-name-outbound');
            Route::post('/update-profile-custom_attribute',[CampaignController::class,'update_profile_in_db_custom_attribute'])->name('update-profile-custom_attribute');
            Route::post('/Outbound-customAttribute',[CampaignController::class, 'Servicescampaigns_customAttributes'])->name('Outbount.customAttribute');
        });
    });


   
    // Route::POST('/add-agents', [UserController::class, 'add_agents'])->name('add-agents');

    Route::prefix('administration')->group(function() {
        Route::prefix('users')->group(function(){
            Route::get('/', [SystemUserController::class, 'index'])->name('administration.users');
            Route::get('/add', [SystemUserController::class, 'add'])->name('administration.users.new');
            Route::post('/add', [SystemUserController::class, 'store'])->name('administration.users.store');
            Route::get('/edit/{user}', [SystemUserController::class, 'view'])->name('administration.users.view');
            Route::put('/edit/{user}',[SystemUserController::class, 'edit'])->name('administration.users.edit');
            Route::post('/delete',[SystemUserController::class, 'delete'])->name('administration.users.delete');

            Route::post('/store_user_by_agent_side', [SystemUserController::class, 'store_user_by_agent_side'])->name('administration.users.store_user_by_agent_side');
        });

        // Route::middleware(['role_or_permission:admin|view users'])->group(function() {
          
        // });
    });

    // Route::get('/customattributes', [DashboardController::class, 'customattributes'])->middleware(['auth', 'verified', 'checkUserStatus'])->name('custom-attributes');
    // Route::prefix('customattributes')->group(function(){
    //     Route::get('/', [CustomAttributesController::class, 'index'])->name('custom-attributes');
    //     Route::post('/store', [CustomAttributesController::class, 'add'])->name('customattributes.store');
    // });

});

require __DIR__.'/auth.php';


Route::post('/leaveImpersonation',[UserOrganizationController::class,'leaveImpersonation'])->name('leaveImpersonation');

Route::prefix('organizations')->middleware(['role:superadmin','auth', 'checkUserStatus'])->group(function(){
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

        Route::post('/ceck_service_detail',[OrganizationServicesController::class, 'ceck_service_detail'])->name('organizations.services.ceck_service_detail');

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
        Route::post('/send-email',[UserOrganizationController::class,'send_impersonation_email'])->name('organizations/user/send-email');
        Route::post('/verified',[UserOrganizationController::class,'verified'])->name('organizations.user.verified');
        
    });

    
});


Route::prefix('accounts')->middleware(['role:superadmin','auth', 'checkUserStatus'])->group(function(){
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
