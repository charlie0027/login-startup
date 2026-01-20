<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class RefProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::from(database_path('seeders/data/ref_provinces.csv'), 'r');
        $csv->setHeaderOffset(0); // <-- this makes the first row the header
        foreach ($csv as $row) {
            DB::table('ref_provinces')->insert([
                'id' => $row['id'],
                'psgcCode' => $row['psgcCode'],
                'provDesc' => $row['provDesc'],
                'regCode' => $row['regCode'],
                'provCode' => $row['provCode'],
            ]);
        }
    }
}
