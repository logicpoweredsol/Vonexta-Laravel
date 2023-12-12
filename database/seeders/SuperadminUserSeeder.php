<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $superadmin = User::create([
            "name" => "Vonexta Admin",
            "email" => "superadmin@gmail.com",
            "phone" => "3144327451",
            "password" => Hash::make('admin@123'),
            "email_verified_at" => date('Y-m-d H:i:s'),
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        $superadmin->assignRole('superadmin');
        $superadmin->givePermissionTo(Permission::all());
    }
}
