<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_details')->insert([
            'id' => '1',
            'user_id' => '1',
            'id_number' => '1234567',
            'gender' => '1',
            'birthdate' => null,
            'house_number' => null,
            'street' => null,
            'barangay' => null,
            'citymun' => null,
            'province' => null,
            'qr_code' => '0000001',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => null,
            'deleted_at' => null,
            'created_by' => '1',
            'contact' => null,
            'bar_code' => null,
            'updated_by' => null,
        ]);
    }
}
