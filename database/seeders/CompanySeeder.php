<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $c1 = Company::updateOrCreate(['name' => 'PT. Contoh Satu'], ['subscription_status' => 'active', 'suspended' => false]);
        $c2 = Company::updateOrCreate(['name' => 'PT. Contoh Dua'], ['subscription_status' => 'trial', 'suspended' => false]);

        // Create one admin user for company 1
        User::updateOrCreate([
            'email' => 'admin@contoh1.com'
        ], [
            'name' => 'Admin Contoh 1',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'company_id' => $c1->id,
            'email_verified_at' => now(),
        ]);

        // Create one staff user for company 2
        User::updateOrCreate([
            'email' => 'staf@contoh2.com'
        ], [
            'name' => 'Staf Contoh 2',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'company_id' => $c2->id,
            'email_verified_at' => now(),
        ]);
    }
}
