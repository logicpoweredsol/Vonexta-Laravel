<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records
        DB::table('organizations')->delete();

        // Seed organization records
        DB::table('organizations')->insert([
            [
                'name' => 'Vonexta',
                'phone' => '123-456-7890',
                'address' => '123 Main St',
                'address2' => 'Suite 100',
                'city' => 'City 1',
                'state' => 'State 1',
                'zip' => '12345',
            ],
            [
                'name' => 'Vonexta 2',
                'phone' => '987-654-3210',
                'address' => '456 Elm St',
                'address2' => 'Apt 200',
                'city' => 'City 2',
                'state' => 'State 2',
                'zip' => '54321',
            ],
        ]);
    }
}
