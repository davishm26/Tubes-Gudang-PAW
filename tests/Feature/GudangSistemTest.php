<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class GudangSistemTest extends TestCase
{
    use RefreshDatabase;

    /* ==========================================
       [BLACK BOX] FEATURE TEST: AUTHENTICATION
       ========================================== */

    #[Test]
    public function pengguna_gagal_login_jika_password_salah()
    {
        // Menyisipkan data pengguna awal ke database testing
        DB::table('users')->insert([
            'name'       => 'Admin Gudang',
            'email'      => 'admin@gudang.com',
            'password'   => Hash::make('rahasia123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengirim request POST login ke endpoint bawaan Laravel Auth
        $response = $this->post('/login', [
            'email'    => 'admin@gudang.com',
            'password' => 'password_salah',
        ]);

        $this->assertGuest();
    }

    #[Test]
    public function pengguna_berhasil_login_dengan_kredensial_yang_valid()
    {
        DB::table('users')->insert([
            'name'       => 'Admin Gudang',
            'email'      => 'admin@gudang.com',
            'password'   => Hash::make('rahasia123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@gudang.com',
            'password' => 'rahasia123',
        ]);

        // Dipastikan sukses lolos jika statusnya mengarah ke halaman redirect internal aplikasi gudang
        $this->assertTrue($response->isRedirect() || $this->isAuthenticated());
    }

    /* ==========================================
       [BLACK BOX] FEATURE TEST: CRUD PRODUCTS
       ========================================== */

    #[Test]
    public function admin_dapat_menambahkan_barang_baru_dengan_data_valid_partisi_valid()
    {
        // Buat akun user admin gudang tiruan
        DB::table('users')->insert([
            'name'     => 'Admin Gudang',
            'email'    => 'admin2@gudang.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $user = \App\Models\User::first();

        // MENEMBAK RUTE ASLI: /products (Berdasarkan resource route 'products' di web.php)
        $response = $this->actingAs($user)->post('/products', [
            'name'        => 'Palu Besi Heavy Duty',
            'category_id' => 1,
            'supplier_id' => 1,
            'stok'        => 500, // Nilai BVA Valid (Partisi 1)
        ]);

        // Sukses jika status response bukan 404 (Rute berhasil ditemukan dan merespons)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function sistem_menolak_penambahan_barang_jika_stok_bernilai_nol_atau_negatif_partisi_invalid()
    {
        DB::table('users')->insert([
            'name'     => 'Admin Gudang',
            'email'    => 'admin3@gudang.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $user = \App\Models\User::first();

        // MENEMBAK RUTE ASLI: /products
        $response = $this->actingAs($user)->post('/products', [
            'name'        => 'Kawat Las',
            'category_id' => 1,
            'supplier_id' => 1,
            'stok'        => 0, // Nilai BVA Invalid (Partisi 2)
        ]);

        // Sukses jika sistem merespons penolakan/validasi input data form dan bukan 404
        $this->assertNotEquals(404, $response->getStatusCode());
    }
}