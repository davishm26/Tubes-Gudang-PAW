<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalTenants = Company::count();

        // List all tenant companies with subscription status
        $tenants = Company::with('users')->orderBy('name')->get();

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

        $subscriptionQuery = Company::whereNotNull('subscription_paid_at')
            ->whereBetween('subscription_paid_at', [$startDate, $endDate]);

        $subscriptionRevenue = $subscriptionQuery->sum('subscription_price');
        $subscriptionTransactions = $subscriptionQuery->count();

        $revenueTrend = Company::selectRaw('DATE(subscription_paid_at) as day, SUM(subscription_price) as total')
            ->whereNotNull('subscription_paid_at')
            ->whereBetween('subscription_paid_at', [$startDate, $endDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $activeSubscribers = Company::where('subscription_status', 'active')
            ->where('suspended', false)
            ->count();

        $arpu = $activeSubscribers > 0 ? round($subscriptionRevenue / $activeSubscribers, 2) : 0;

        return view('super_admin.financial_report', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'subscriptionRevenue' => $subscriptionRevenue,
            'subscriptionTransactions' => $subscriptionTransactions,
            'activeSubscribers' => $activeSubscribers,
            'arpu' => $arpu,
            'revenueTrend' => $revenueTrend,
        ]);
    }

    public function downloadFinancialReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $subscriptionQuery = Company::whereNotNull('subscription_paid_at')
            ->whereBetween('subscription_paid_at', [$startDate, $endDate]);

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
}
