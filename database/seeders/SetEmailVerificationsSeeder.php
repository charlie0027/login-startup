<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetEmailVerificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('set_email_verifications')->insert([
            [
                'id' => '1',
                'key' => 'require_email_verification',
                'value' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => null,
            ],
            [
                'id' => '2',
                'key' => 'require_two_factor_auth',
                'value' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => null,
            ]
        ]);
    }
}
