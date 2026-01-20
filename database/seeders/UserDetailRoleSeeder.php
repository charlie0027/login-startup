<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDetailRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_detail_role')->insert([
            'id' => '1',
            'user_detail_id' => '1',
            'user_role_id' => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => null,
        ]);
    }
}
