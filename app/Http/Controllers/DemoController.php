<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DemoController extends Controller
{
    /**
     * Masuk ke mode demo dengan role tertentu
     * Seeder semua data demo dari config/demo_data.php
     *
     * @param string $role ('admin' atau 'staf')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enter($role)
    {
        // Backward compatibility
        if ($role === 'staff') {
            $role = 'staf';
        }

        // Validasi role yang diperbolehkan
        if (!in_array($role, ['admin', 'staf'], true)) {
            return redirect('/')->with('error', 'Role tidak valid. Pilih admin atau staf.');
        }

        // Load semua demo data dari config
        $demoData = config('demo_data');

        // Set session untuk mode demo
        Session::put('is_demo', true);
        Session::put('demo_mode', true);  // Support kedua session keys
        Session::put('demo_role', $role);

        // Ambil data user dummy dari config
        $demoUser = $demoData['demo_user'][$role];
        Session::put('demo_user', $demoUser);

        // Seeder data lengkap ke session (sesuai dengan struktur real mode)
        Session::put('demo_categories', $demoData['categories']);
        Session::put('demo_suppliers', $demoData['suppliers']);
        Session::put('demo_products', $demoData['products']);
        Session::put('demo_inventory_in', $demoData['inventory_ins']);
        Session::put('demo_inventory_out', $demoData['inventory_outs']);
        Session::put('demo_users', $demoData['users']);
        Session::put('demo_statistics', $demoData['statistics']);

        // Seed data optional features
        Session::put('demo_audit_logs', $demoData['audit_logs']);
        Session::put('demo_notifications', $demoData['notifications']);
        Session::put('demo_profile_data', $demoData['profile_data'][$role]);

        // Flash message sukses dengan ringkasan data
        Session::flash('success', "Mode Demo aktif sebagai {$role}! Anda dapat mencoba semua fitur (17 produk, 6 supplier, 8 aktivitas, 7 notifikasi, lengkap manajemen profil).");

        // Redirect ke dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Keluar dari mode demo dan bersihkan semua session data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exit()
    {
        // Bersihkan semua session data demo (10 core + 3 optional)
        Session::forget('is_demo');
        Session::forget('demo_mode');
        Session::forget('demo_role');
        Session::forget('demo_user');
        Session::forget('demo_categories');
        Session::forget('demo_suppliers');
        Session::forget('demo_products');
        Session::forget('demo_inventory_in');
        Session::forget('demo_inventory_out');
        Session::forget('demo_users');
        Session::forget('demo_statistics');
        Session::forget('demo_audit_logs');
        Session::forget('demo_notifications');
        Session::forget('demo_profile_data');

        // Flash pesan sukses
        Session::flash('success', 'Anda telah keluar dari Mode Demo. Terima kasih telah mencoba sistem kami!');

        // Redirect ke halaman landing
        return redirect()->route('subscription.landing');
    }

    /**
     * Info mode demo (untuk debugging dan development)
     * Menampilkan informasi session demo yang saat ini aktif
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        $isDemoMode = Session::get('is_demo') || Session::get('demo_mode');

        return response()->json([
            'is_demo' => $isDemoMode,
            'demo_role' => Session::get('demo_role'),
            'demo_user' => Session::get('demo_user'),
            'demo_data_loaded' => [
                'categories' => Session::has('demo_categories') ? count(Session::get('demo_categories', [])) : 0,
                'suppliers' => Session::has('demo_suppliers') ? count(Session::get('demo_suppliers', [])) : 0,
                'products' => Session::has('demo_products') ? count(Session::get('demo_products', [])) : 0,
                'inventory_in' => Session::has('demo_inventory_in') ? count(Session::get('demo_inventory_in', [])) : 0,
                'inventory_out' => Session::has('demo_inventory_out') ? count(Session::get('demo_inventory_out', [])) : 0,
                'audit_logs' => Session::has('demo_audit_logs') ? count(Session::get('demo_audit_logs', [])) : 0,
                'notifications' => Session::has('demo_notifications') ? count(Session::get('demo_notifications', [])) : 0,
                'profile_data' => Session::has('demo_profile_data') ? true : false,
            ]
        ]);
    }
}
