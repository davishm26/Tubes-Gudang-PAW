<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\InventoryIn::create([
            'product_id' => 1,
            'supplier_id' => 2,
            'quantity' => 100,
            'date' => now()->subDays(10),
            'user_id' => 2,
            'description' => 'Pembelian ikan',
        ]);

        \App\Models\InventoryIn::create([
            'product_id' => 2,
            'supplier_id' => 2,
            'quantity' => 50,
            'date' => now()->subDays(5),
            'user_id' => 2,
            'description' => 'Pembelian ayam',
        ]);
    }
}
