<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{ 
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create organizations']);
        Permission::create(['name' => 'edit organizations']);
        Permission::create(['name' => 'view organizations']);
        Permission::create(['name' => 'delete organizations']);
        Permission::create(['name' => 'create services']);
        Permission::create(['name' => 'edit services']);
        Permission::create(['name' => 'view services']);
        Permission::create(['name' => 'delete services']);
        Permission::create(['name' => 'create campaigns']);
        Permission::create(['name' => 'edit campaigns']);
        Permission::create(['name' => 'view campaigns']);
        Permission::create(['name' => 'delete campaigns']);

        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'writer']);
        // $role->givePermissionTo('edit articles');

        // // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);

        Role::create([
            'name' => 'admin',
            'guard_name'=>'web'
         ]);
         Role::create([
            'name' => 'user',
            'guard_name'=>'web'
         ]);

         $role = Role::create([
            'name' => 'superadmin',
            'guard_name'=>'web'
         ]);

     
        // $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());
    }
}
