<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
            $this->call(RolesAndPermissionsSeeder::class);
            $this->call(ServicesTableSeeder::class);
            $this->call(SuperadminUserSeeder::class);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@test.com',
        //     'password' => Hash::make('test@test.com')
        // ]);
    }
}
