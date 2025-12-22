<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // WAJIB: Sudah ada

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call Super Admin and Company seeders
        $this->call([
            SuperAdminSeeder::class,
            CompanySeeder::class,
        ]);
    }
}
