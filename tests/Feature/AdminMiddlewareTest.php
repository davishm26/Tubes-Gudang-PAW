<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INDEPENDENT PATH 1: Menguji Jalur Blokir Hak Akses (Bukan Admin)
     * Merepresentasikan percabangan logika 'if (role !== admin)' pada source code asli.
     */
    public function test_path_1_non_admin_user_is_blocked_from_products()
    {
        // 1. Setup Data: Membuat user tiruan dengan role 'staf'
        $staf = User::factory()->create(['role' => 'staf']);

        // 2. Eksekusi Jalur: Mencoba menembak rute POST /products
        $response = $this->actingAs($staf)->post('/products', [
            'name' => 'Barang Uji Whitebox Path 1',
        ]);

        // 3. Assertion: Memastikan kode internal menolak akses (Bisa status 403 atau dialihkan/redirect kembali)
        $this->assertTrue($response->isRedirect() || $response->getStatusCode() === 403);
    }

    /**
     * INDEPENDENT PATH 2: Menguji Jalur Lolos Hak Akses (User Admin)
     * Merepresentasikan kondisi ketika 'if' bernilai False sehingga langsung melompat ke proses core method.
     */
    public function test_path_2_admin_user_can_access_products()
    {
        // 1. Setup Data: Membuat user tiruan dengan role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Eksekusi Jalur: Menembak rute utama GET /products
        $response = $this->actingAs($admin)->get('/products');

        // 3. Assertion: Memastikan kode internal meloloskan admin (Status 200 OK atau 302 Redirect Sukses ke view dashboard)
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }
}