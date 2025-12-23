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

        // Dummy revenue: sum of random values per company (temporary)
        $totalRevenue = $tenants->reduce(function ($carry, $company) {
            return $carry + rand(1000, 10000);
        }, 0);

        return view('super_admin.dashboard', compact('totalTenants', 'tenants', 'allUsers', 'totalRevenue'));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Hitung total pemasukan dari inventory in: sum(quantity * product.price)
        $totalIncome = InventoryIn::whereBetween('inventory_ins.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_ins.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_ins.quantity * products.price'));

        // Hitung total pengeluaran dari inventory out: sum(quantity * product.price)
        $totalExpense = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Hitung profit
        $profit = $totalIncome - $totalExpense;

        return view('super_admin.financial_report', compact('startDate', 'endDate', 'totalIncome', 'totalExpense', 'profit'));
    }

    public function downloadFinancialReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Hitung total pemasukan dari inventory in: sum(quantity * product.price)
        $totalIncome = InventoryIn::whereBetween('inventory_ins.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_ins.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_ins.quantity * products.price'));

        // Hitung total pengeluaran dari inventory out: sum(quantity * product.price)
        $totalExpense = InventoryOut::whereBetween('inventory_outs.created_at', [$startDate, $endDate])
            ->join('products', 'inventory_outs.product_id', '=', 'products.id')
            ->sum(DB::raw('inventory_outs.quantity * products.price'));

        // Hitung profit
        $profit = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('super_admin.financial_report_pdf', compact('startDate', 'endDate', 'totalIncome', 'totalExpense', 'profit'));

        return $pdf->download('laporan_keuangan_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}
