<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\authorization_key;
use Illuminate\Support\Facades\DB;
class keySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('authorization_keys')->delete();


        $keys = [
            [
                'key' => 'ErsE9E2OlXRvc88x',
            ],
        ];

        foreach ($keys as $key) {
            DB::table('authorization_keys')->insert($key);
        }

    }
}
