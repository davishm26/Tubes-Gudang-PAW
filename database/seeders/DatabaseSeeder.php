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
        // 1. Akun ADMIN
        // Menggunakan updateOrCreate untuk menghindari error duplikat email
        User::updateOrCreate(
            ['email' => 'admin1@gudang.com'], // Kondisi pencarian
            [
                'name' => 'Admin Gudang Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(), // Tambahkan verifikasi instan
            ]
        );

        // 2. Akun STAF GUDANG
        // Menggunakan updateOrCreate untuk menghindari error duplikat email
        User::updateOrCreate(
            ['email' => 'staf1@gudang.com'], // Kondisi pencarian
            [
                'name' => 'Staf',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'email_verified_at' => now(), // Tambahkan verifikasi instan
            ]
        );

        // --- Bagian Factory (Opsional, untuk membuat data dummy) ---

        // Jika Anda memiliki seeder untuk Kategori, Pemasok, Produk,
        // Anda bisa memanggilnya di sini:
        // $this->call([
        //     CategorySeeder::class,
        //     SupplierSeeder::class,
        //     // ... dan seeder lainnya
        // ]);
    }
}
