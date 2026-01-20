<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::from(database_path('seeders/data/permissions.csv'), 'r');
        $csv->setHeaderOffset(0); // <-- this makes the first row the header
        foreach ($csv as $row) {
            DB::table('permissions')->insert([
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'created_at' => $row['created_at'],
                'updated_at' => ($row['updated_at'] === 'NULL' || $row['updated_at'] === '') ? null : $row['updated_at'],
                'deleted_at' => ($row['deleted_at'] === 'NULL' || $row['deleted_at'] === '') ? null : $row['deleted_at'],
            ]);
        }
    }
}
