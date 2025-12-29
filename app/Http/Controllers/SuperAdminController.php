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

        // Real revenue: total value of stock going out (quantity * product price)
        $totalRevenue = InventoryOut::join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * COALESCE(products.price, 0)'));

        return view('super_admin.dashboard', compact('totalTenants', 'tenants', 'allUsers', 'totalRevenue'));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Pendapatan langganan (subscription) yang dibayar pada periode
        $subscriptionRevenue = Company::whereNotNull('subscription_paid_at')
            ->whereBetween('subscription_paid_at', [$startDate, $endDate])
            ->sum('subscription_price');

        // Pendapatan operasional: nilai stok keluar (inventory outs) * harga produk
        $operationalIncome = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Total pemasukan = subscription + operasional
        $totalIncome = $subscriptionRevenue + $operationalIncome;

        // Hitung total pengeluaran dari inventory out: sum(quantity * product.price)
        $totalExpense = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Hitung profit
        $profit = $totalIncome - $totalExpense;

        return view('super_admin.financial_report', compact(
            'startDate',
            'endDate',
            'subscriptionRevenue',
            'operationalIncome',
            'totalIncome',
            'totalExpense',
            'profit'
        ));
    }

    public function downloadFinancialReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Pendapatan langganan (subscription) yang dibayar pada periode
        $subscriptionRevenue = Company::whereNotNull('subscription_paid_at')
            ->whereBetween('subscription_paid_at', [$startDate, $endDate])
            ->sum('subscription_price');

        // Pendapatan operasional: nilai stok keluar (inventory outs) * harga produk
        $operationalIncome = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Total pemasukan = subscription + operasional
        $totalIncome = $subscriptionRevenue + $operationalIncome;

        // Hitung total pengeluaran dari inventory out: sum(quantity * product.price)
        $totalExpense = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Hitung profit
        $profit = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('super_admin.financial_report_pdf', compact(
            'startDate',
            'endDate',
            'subscriptionRevenue',
            'operationalIncome',
            'totalIncome',
            'totalExpense',
            'profit'
        ));

        return $pdf->download('laporan_keuangan_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}
