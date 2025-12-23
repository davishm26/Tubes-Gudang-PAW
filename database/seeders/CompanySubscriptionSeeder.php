<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Company::where('name', 'PT. Contoh Satu')->update([
            'subscription_expires_at' => now()->addDays(30), // 30 hari lagi
        ]);

        \App\Models\Company::where('name', 'PT. Contoh Dua')->update([
            'subscription_expires_at' => now()->addDays(5), // 5 hari lagi (harus dikirim notifikasi)
        ]);
    }
}
