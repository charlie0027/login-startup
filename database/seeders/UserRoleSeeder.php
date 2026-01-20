<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_roles')->insert([
            'id' => '1',
            'role_name' => 'Super Admin',
            'role_code' => 'SA',
            'description' => 'Default Super Admin',
            'is_default' => '0',
            'status' => '1',
            'updated_by' => '1',
            'created_by' => '1',
            'deleted_at' => null,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => null,
        ]);
    }
}
