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
        // Clear existing records
        DB::table('services')->delete();

        $services = [
            [
                'name' => 'Dialer',
                'description' => 'Dialer Services',
                'connection_parameters' => json_encode([
                    'type' => 'dialer',
                    'server_url' => '',
                    'api_user' => '',
                    'api_pass' => '',
                ]),
            ],
            [
                'name' => 'Automation',
                'description' => 'Automation Services',
                'connection_parameters' => json_encode([
                    'api_key' => '',
                ]),
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
}
