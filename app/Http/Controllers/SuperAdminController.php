<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalTenants = Company::count();

        // List all tenant companies with subscription status and revenue
        $tenants = Company::with('users')
            ->orderBy('name')
            ->get()
            ->map(function ($company) {
                $company->subscription_revenue = $company->subscription_price ?? 0;
                return $company;
            });

        // All users (global)
        $allUsers = User::with('company')->orderBy('name')->get();

        // Total Subscription Revenue (all time)
        $totalRevenue = Company::whereNotNull('subscription_paid_at')
            ->sum('subscription_price');

        return view('super_admin.dashboard', compact('totalTenants', 'tenants', 'allUsers', 'totalRevenue'));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Query companies yang melakukan pembayaran/subscription dalam range
        // Tidak filter berdasarkan status karena revenue tetap dihitung meskipun sudah suspend/expired
        $subscriptionQuery = Company::whereNotNull('subscription_price')
            ->whereBetween(
                DB::raw("COALESCE(DATE(subscription_paid_at), DATE(created_at))"),
                [$startDate, $endDate]
            );

        // Hitung revenue untuk date range yang dipilih
        $subscriptionRevenue = $subscriptionQuery->sum('subscription_price');

        // Hitung transaksi dalam range - gunakan COALESCE seperti di chart
        $subscriptionTransactionsInRange = $subscriptionQuery->count();

        // Revenue trend untuk chart - dengan fallback ke created_at jika subscription_paid_at kosong
        $revenueTrendRaw = Company::selectRaw("
            COALESCE(DATE(subscription_paid_at), DATE(created_at)) as day,
            SUM(subscription_price) as total
        ")
            ->whereNotNull('subscription_price')
            ->whereBetween(
                DB::raw("COALESCE(DATE(subscription_paid_at), DATE(created_at))"),
                [$startDate, $endDate]
            )
            ->groupBy(DB::raw("COALESCE(DATE(subscription_paid_at), DATE(created_at))"))
            ->orderBy('day')
            ->get();

        // Generate all dates dalam range dan fill dengan 0 jika tidak ada data
        $allDates = [];
        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDateObj = \Carbon\Carbon::parse($endDate);

        while ($currentDate <= $endDateObj) {
            $allDates[$currentDate->format('Y-m-d')] = 0;
            $currentDate->addDay();
        }

        // Merge dengan actual data
        foreach ($revenueTrendRaw as $item) {
            $allDates[$item->day] = $item->total;
        }

        // Convert ke array of objects untuk chart
        $revenueTrend = collect($allDates)->map(function($total, $day) {
            return (object)['day' => $day, 'total' => $total];
        })->values();

        $activeSubscribers = Company::where('subscription_status', 'active')
            ->where('suspended', false)
            ->count();

        $arpu = $activeSubscribers > 0 ? round($subscriptionRevenue / $activeSubscribers, 2) : 0;

        return view('super_admin.financial_report', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'subscriptionRevenue' => $subscriptionRevenue,
            'subscriptionTransactions' => $subscriptionTransactionsInRange,
            'activeSubscribers' => $activeSubscribers,
            'arpu' => $arpu,
            'revenueTrend' => $revenueTrend,
        ]);
    }

    public function downloadFinancialReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Selaraskan logika dengan tampilan dashboard: gunakan COALESCE(subscription_paid_at, created_at)
        // agar transaksi yang belum memiliki paid_at tetapi memiliki harga tetap terhitung.
        $subscriptionQuery = Company::whereNotNull('subscription_price')
            ->whereBetween(
                \DB::raw("COALESCE(DATE(subscription_paid_at), DATE(created_at))"),
                [$startDate, $endDate]
            );

        $subscriptionRevenue = $subscriptionQuery->sum('subscription_price');
        $subscriptionTransactions = $subscriptionQuery->count();

        $activeSubscribers = Company::where('subscription_status', 'active')
            ->where('suspended', false)
            ->count();

        $arpu = $activeSubscribers > 0 ? round($subscriptionRevenue / $activeSubscribers, 2) : 0;

        $pdf = Pdf::loadView('super_admin.financial_report_pdf', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'subscriptionRevenue' => $subscriptionRevenue,
            'subscriptionTransactions' => $subscriptionTransactions,
            'activeSubscribers' => $activeSubscribers,
            'arpu' => $arpu,
        ]);

        return $pdf->download('laporan_keuangan_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    public function reactivationRequests()
    {
        // Ambil semua notifikasi dengan template 'reactivation_request'
        $requests = \App\Models\Notification::where('template', 'reactivation_request')
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                // Parse informasi dari message
                $message = $notification->message;
                preg_match("/Perusahaan: (.+?)\n/", $message, $companyName);
                preg_match("/Pesan: (.+?)\n/s", $message, $requestMessage);
                preg_match("/Kontak Email: (.+?)(?:\n|$)/", $message, $email);
                preg_match("/Telpon: (.+?)$/", $message, $phone);

                // Cari company berdasarkan nama
                $company = null;
                if (isset($companyName[1])) {
                    $company = Company::where('name', $companyName[1])->first();
                }

                return [
                    'id' => $notification->id,
                    'created_at' => $notification->created_at,
                    'is_read' => !is_null($notification->read_at),
                    'company_name' => $companyName[1] ?? 'Unknown',
                    'company' => $company,
                    'message' => trim($requestMessage[1] ?? ''),
                    'email' => $email[1] ?? '',
                    'phone' => $phone[1] ?? null,
                    'full_message' => $notification->message,
                ];
            });

        return view('super_admin.reactivation_requests', compact('requests'));
    }

    public function approveReactivation($companyId)
    {
        $company = Company::findOrFail($companyId);

        // Reactive company - ubah suspended dan subscription_status
        $company->update([
            'suspended' => false,
            'subscription_status' => 'active',
            'suspend_reason' => null,
            'suspend_reason_type' => null,
        ]);

        // Kirim notifikasi ke admin company
        $companyAdmin = User::where('company_id', $companyId)
            ->where('role', 'admin')
            ->first();

        if ($companyAdmin) {
            \App\Models\Notification::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $companyAdmin->id,
                'template' => 'account_reactivated',
                'message' => "AKUN TELAH DIAKTIFKAN KEMBALI\n\nSelamat! Akun perusahaan '{$company->name}' telah diaktifkan kembali oleh administrator. Anda sekarang dapat mengakses sistem kembali.",
            ]);
        }

        // Tandai notifikasi reactivation_request sebagai sudah dibaca
        \App\Models\Notification::where('template', 'reactivation_request')
            ->where('message', 'LIKE', "%{$company->name}%")
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', "Akun perusahaan '{$company->name}' berhasil diaktifkan kembali.");
    }

    public function rejectReactivation(Request $request, $companyId)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ]);

        $company = Company::findOrFail($companyId);

        // Kirim notifikasi penolakan ke admin company
        $companyAdmin = User::where('company_id', $companyId)
            ->where('role', 'admin')
            ->first();

        if ($companyAdmin) {
            \App\Models\Notification::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $companyAdmin->id,
                'template' => 'reactivation_rejected',
                'message' => "PERMINTAAN REAKTIVASI DITOLAK\n\nPermintaan reaktivasi akun perusahaan '{$company->name}' telah ditolak.\n\nAlasan: {$request->reject_reason}",
            ]);
        }

        // Tandai notifikasi reactivation_request sebagai sudah dibaca
        \App\Models\Notification::where('template', 'reactivation_request')
            ->where('message', 'LIKE', "%{$company->name}%")
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', "Permintaan reaktivasi untuk '{$company->name}' telah ditolak.");
    }
}
