<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class keyRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('authorization_key_rules')->delete();
        
        $keys = [
            [
                'authorization_key_id' => '1',
                'path'=> 'api/validate'
            ],
        ];
        foreach ($keys as $key) {
            DB::table('authorization_key_rules')->insert($key);
        }
    }
}
