<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Organization;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Clear existing records
        DB::table('services')->delete();

        // Seed service records
        DB::table('services')->insert([
            [
                'name' => 'Dialler',
                'description' => 'Dialler Services',
                'connection_parameters' => json_encode([
                    'type' => 'mysql',
                    'host' => '66.45.254.2',
                    'database' => 'asterisk',
                    'user' => 'cron',
                    'pass' => '1234',
                    'port' => '3306',
                    'api_user' => 'apiuser',
                    'api_pass' => 'apiuser',
                ]),
            ]
        ]);
    }
}
