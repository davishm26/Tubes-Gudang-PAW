<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DemoController extends Controller
{
    /**
     * Masuk ke mode demo dengan role tertentu
     *
     * @param string $role ('admin' atau 'staff')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enter($role)
    {
        // Validasi role yang diperbolehkan
        if (!in_array($role, ['admin', 'staff'])) {
            return redirect('/')->with('error', 'Role tidak valid. Pilih admin atau staff.');
        }

        // Set session untuk mode demo
        Session::put('is_demo', true);
        Session::put('demo_role', $role);

        // Ambil data user dummy dari config
        $demoUsers = config('demo_data.user');
        $demoUser = $demoUsers[$role];

        // Simpan data user demo ke session
        Session::put('demo_user', $demoUser);

        // Flash message sukses
        Session::flash('success', "Mode Demo aktif sebagai {$role}! Anda dapat mencoba semua fitur tanpa mempengaruhi data asli.");

        // Redirect ke dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Keluar dari mode demo
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exit()
    {
        // Hapus semua session demo
        Session::forget('is_demo');
        Session::forget('demo_role');
        Session::forget('demo_user');

        Session::flash('success', 'Anda telah keluar dari Mode Demo.');

        // Redirect ke landing page atau login
        return redirect('/');
    }

    /**
     * Info mode demo (opsional - untuk debugging)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        return response()->json([
            'is_demo' => Session::get('is_demo', false),
            'demo_role' => Session::get('demo_role'),
            'demo_user' => Session::get('demo_user'),
        ]);
    }
}
