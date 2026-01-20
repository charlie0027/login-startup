<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\Clock\now;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'superadmin',
            'last_name' => 'Doe',
            'first_name' => 'John',
            'middle_name' => null,
            'name_extension' => null,
            'email' => 'john@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('admin123'),
            'remember_token' => null,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => null,
            'created_by' => 0,
            'updated_by' => null,
            'two_factor_code' => null,
            'two_factor_expires_at' => null
        ]);
    }
}
