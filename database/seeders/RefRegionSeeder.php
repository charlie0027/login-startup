<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class RefRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::from(database_path('seeders/data/ref_regions.csv'), 'r');
        $csv->setHeaderOffset(0); // <-- this makes the first row the header
        foreach ($csv as $row) {
            DB::table('ref_regions')->insert([
                'id' => $row['id'],
                'psgcCode' => $row['psgcCode'],
                'regDescription' => $row['regDescription'],
                'regCode' => $row['regCode'],
            ]);
        }
    }
}
