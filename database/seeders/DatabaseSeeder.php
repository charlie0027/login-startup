<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserDetailSeeder::class,
            UserRoleSeeder::class,
            UserDetailRoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            SetEmailVerificationsSeeder::class,
            RefRegionSeeder::class,
            RefProvinceSeeder::class,
            RefCitySeeder::class,
            RefBarangaySeeder::class,
        ]);
    }
}
