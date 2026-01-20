<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::from(database_path('seeders/data/permission_role.csv'), 'r');
        $csv->setHeaderOffset(0); // <-- this makes the first row the header
        foreach ($csv as $row) {
            DB::table('permission_role')->insert([
                'id' => $row['id'],
                'permission_id' => $row['permission_id'],
                'user_role_id' => $row['user_role_id'],
                'created_at' => ($row['created_at'] === 'NULL' || $row['created_at'] === '') ? null : $row['created_at'],
                'updated_at' => ($row['updated_at'] === 'NULL' || $row['updated_at'] === '') ? null : $row['updated_at'],
            ]);
        }
    }
}
