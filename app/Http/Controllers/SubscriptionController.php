<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function landing()
    {
        return view('subscription.landing');
    }

    public function subscribe(Request $request)
    {
        if ($request->isMethod('post')) {
            $isTenantAdmin = Auth::check() && Auth::user()->role === 'admin' && Auth::user()->company;

            $request->validate([
                'years' => 'required|integer|min:1',
                'company_name' => $isTenantAdmin ? 'nullable' : 'required|string|max:255',
                'admin_name' => $isTenantAdmin ? 'nullable' : 'required|string|max:255',
                'admin_email' => $isTenantAdmin ? 'nullable|email' : 'required|email|unique:users,email',
            ]);

            $years = (int) $request->years;
            $price = $years * 1_000_000; // 1 juta per tahun

            // Generate unique token for payment
            $token = Str::random(32);

            // Siapkan data untuk session (registrasi baru atau perpanjangan)
            $subscriptionData = [
                'years' => $years,
                'price' => $price,
                'company_name' => $isTenantAdmin ? Auth::user()->company->name : $request->company_name,
                'admin_name' => $isTenantAdmin ? Auth::user()->name : $request->admin_name,
                'admin_email' => $isTenantAdmin ? Auth::user()->email : $request->admin_email,
                'token' => $token,
            ];

            if ($isTenantAdmin) {
                $subscriptionData['renew_company_id'] = Auth::user()->company->id;
            }

            // Store in session
            session(['subscription' => $subscriptionData]);

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
        $paymentInfo = "Pembayaran StockMaster\nHarga: Rp " . number_format($subscription['price'], 0, ',', '.') . "\nToken: " . $subscription['token'];

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
            $endDate = now()->addYears((int)$subscription['years']);

            // Jika ini perpanjangan tenant admin yang sudah ada
            if (!empty($subscription['renew_company_id'])) {
                $company = Company::findOrFail($subscription['renew_company_id']);

                $currentEnd = $company->subscription_end_date
                    ? Carbon::parse($company->subscription_end_date)
                    : now();

                if ($currentEnd->lt(now())) {
                    $currentEnd = now();
                }

                $newEndDate = $currentEnd->copy()->addYears((int)$subscription['years']);

                $company->update([
                    'subscription_end_date' => $newEndDate,
                    'subscription_status' => 'active',
                    'subscription_price' => $subscription['price'],
                    'subscription_paid_at' => now(),
                    'suspended' => false,
                    'suspend_reason' => null,
                    'suspend_reason_type' => null,
                ]);

                $subscriptionData = $subscription;
                session()->forget('subscription');

                return view('subscription.success', [
                    'company' => $company,
                    'subscription' => $subscriptionData
                ]);
            }

            // Registrasi baru
            $company = Company::create([
                'name' => $subscription['company_name'],
                'subscription_status' => 'active',
                'suspended' => false,
                'subscription_end_date' => $endDate,
                'subscription_price' => $subscription['price'],
                'subscription_paid_at' => now(),
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
        $role = $request->input('role', 'staff');

        // Validasi role
        if (!in_array($role, ['admin', 'staff'])) {
            $role = 'staff';
        }

        // Load semua demo data dari config (sesuai dengan mode real)
        $demoData = config('demo_data');

        // Set session untuk demo mode dengan SEMUA data dari config (10 core + 3 optional)
        session([
            'demo_mode' => true,
            'is_demo' => true,  // Support kedua session keys
            'demo_role' => $role,
            'demo_user' => $demoData['demo_user'][$role],
            'demo_categories' => $demoData['categories'],
            'demo_suppliers' => $demoData['suppliers'],
            'demo_products' => $demoData['products'],
            'demo_inventory_in' => $demoData['inventory_ins'],
            'demo_inventory_out' => $demoData['inventory_outs'],
            'demo_users' => $demoData['users'],
            'demo_statistics' => $demoData['statistics'],
            // Optional features
            'demo_audit_logs' => $demoData['audit_logs'],
            'demo_notifications' => $demoData['notifications'],
            'demo_profile_data' => $demoData['profile_data'][$role],
        ]);

        return redirect()->route('dashboard')->with('success', 'Mode Demo aktif! Data dummy telah dimuat dengan 3 optional features (audit logs, notifications, profile management).');
    }

    public function exitDemo()
    {
        // Hapus session demo mode dan semua data demo (10 core + 3 optional) agar bersih saat masuk lagi
        session()->forget([
            'demo_mode',
            'is_demo',
            'demo_role',
            'demo_user',
            'demo_products',
            'demo_suppliers',
            'demo_categories',
            'demo_users',
            'demo_inventory_in',
            'demo_inventory_out',
            'demo_statistics',
            'demo_audit_logs',
            'demo_notifications',
            'demo_profile_data',
        ]);

        return redirect()->route('subscription.landing')->with('success', 'Demo mode dinonaktifkan. Terima kasih telah mencoba sistem kami!');
    }

    public function renew(Request $request)
    {
        $request->validate([
            'years' => 'required|integer|min:1|max:10',
        ]);

        $user = $request->user();

        if (!$user || !$user->company) {
            return redirect()->back()->with('error', 'Tidak ada perusahaan terkait akun Anda.');
        }

        $company = $user->company;

        $years = (int) $request->input('years');
        $pricePerYear = 1_000_000; // Rp 1 juta per tahun
        $newPrice = $years * $pricePerYear;

        $currentEnd = $company->subscription_end_date
            ? \Carbon\Carbon::parse($company->subscription_end_date)
            : now();

        if ($currentEnd->lt(now())) {
            $currentEnd = now();
        }

        $newEndDate = $currentEnd->copy()->addYears($years);

        $company->update([
            'subscription_end_date' => $newEndDate,
            'subscription_status' => 'active',
            'subscription_price' => $newPrice,
            'subscription_paid_at' => now(),
            'suspended' => false,
            'suspend_reason' => null,
            'suspend_reason_type' => null,
        ]);

        return redirect()->back()->with('success', 'Langganan berhasil diperpanjang hingga ' . $newEndDate->format('d M Y') . '.');
    }
}
