<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;

class SubscriptionController extends Controller
{
    public function landing()
    {
        return view('subscription.landing');
    }

    public function subscribe(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'years' => 'required|integer|min:1',
                'company_name' => 'required|string|max:255',
                'admin_name' => 'required|string|max:255',
                'admin_email' => 'required|email|unique:users,email',
            ]);

            $years = $request->years;
            $price = $years * 1000000; // 1 juta per tahun

            // Generate unique token for payment
            $token = Str::random(32);

            // Store in session
            session([
                'subscription' => [
                    'years' => $years,
                    'price' => $price,
                    'company_name' => $request->company_name,
                    'admin_name' => $request->admin_name,
                    'admin_email' => $request->admin_email,
                    'token' => $token,
                ]
            ]);

            return redirect()->route('subscription.payment');
        }

        return view('subscription.subscribe');
    }

    public function payment()
    {
        $subscription = session('subscription');
        if (!$subscription) {
            return redirect()->route('subscription.subscribe');
        }

        // Generate QR Code with payment info text
        $paymentInfo = "Pembayaran Sistem Gudang\nHarga: Rp " . number_format($subscription['price'], 0, ',', '.') . "\nToken: " . $subscription['token'];

        $builder = new Builder(
            writer: new SvgWriter(),
            data: $paymentInfo,
            size: 300,
            margin: 10
        );

        $result = $builder->build();
        $qrCodeDataUri = $result->getDataUri();

        return view('subscription.payment', compact('subscription', 'qrCodeDataUri'));
    }

    public function pay($token)
    {
        $subscription = session('subscription');
        if (!$subscription || $subscription['token'] !== $token) {
            return redirect()->route('subscription.subscribe')->with('error', 'Session expired. Please start over.');
        }

        try {
            // Simulate payment success
            // Create company
            $endDate = now()->addYears((int)$subscription['years']);
            $company = Company::create([
                'name' => $subscription['company_name'],
                'subscription_status' => 'active',
                'suspended' => false,
                'subscription_end_date' => $endDate,
            ]);

            // Create admin user
            User::create([
                'name' => $subscription['admin_name'],
                'email' => $subscription['admin_email'],
                'password' => Hash::make('password'), // Default password
                'role' => 'admin',
                'company_id' => $company->id,
                'email_verified_at' => now(),
            ]);

            // Save subscription data before clearing session
            $subscriptionData = $subscription;

            // Clear session
            session()->forget('subscription');

            return view('subscription.success', [
                'company' => $company,
                'subscription' => $subscriptionData
            ]);
        } catch (\Exception $e) {
            return redirect()->route('subscription.subscribe')->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }

    public function startDemo(Request $request)
    {
        $role = $request->query('role', 'staff');

        // Validasi role
        if (!in_array($role, ['admin', 'staff'])) {
            $role = 'staff';
        }

        // Set session untuk demo mode
        session([
            'demo_mode' => 'true',
            'demo_role' => $role
        ]);

        return redirect()->route('dashboard');
    }

    public function exitDemo()
    {
        // Hapus session demo mode dan semua data demo agar bersih saat masuk lagi
        session()->forget([
            'demo_mode',
            'demo_role',
            'demo_products',
            'demo_suppliers',
            'demo_categories',
            'demo_users',
            'demo_inventory_in',
            'demo_inventory_out',
        ]);

        return redirect()->route('subscription.landing');
    }
}
