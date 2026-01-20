<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class RefBarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::from(database_path('seeders/data/ref_barangays.csv'), 'r');
        $csv->setHeaderOffset(0); // <-- this makes the first row the header
        foreach ($csv as $row) {
            DB::table('ref_barangays')->insert([
                'id' => $row['id'],
                'brgyCode' => $row['brgyCode'],
                'brgyDesc' => $row['brgyDesc'],
                'regCode' => $row['regCode'],
                'provCode' => $row['provCode'],
                'citymunCode' => $row['citymunCode'],
            ]);
        }
    }
}
