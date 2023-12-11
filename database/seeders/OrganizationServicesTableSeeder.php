<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\Organization;

class OrganizationServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Clear existing records
        DB::table('organization_services')->delete();

        // DB::table('organization_services')->insert([
        //     'service_id' => Service::first()->id,
        //     'organization_id' => Organization::first()->id,
        //     'connection_parameters' => json_encode([
        //         'type' => 'mysql',
        //         'host' => '66.45.254.2',
        //         'database' => 'asterisk',
        //         'user' => 'cron',
        //         'pass' => '1234',
        //         'port' => '3306',
        //         'api_user' => 'apiuser',
        //         'api_pass' => 'apiuser',
        //     ])
        // ]);
    }
}
