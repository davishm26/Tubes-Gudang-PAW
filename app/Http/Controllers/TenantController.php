<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class TenantController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        return view('super_admin.tenants.index', compact('companies'));
    }

    public function create()
    {
        return view('super_admin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subscription_status' => 'nullable|string|max:50',
        ]);

        Company::create($data + ['suspended' => false]);

        return redirect()->route('super_admin.tenants.index')->with('success', 'Tenant created.');
    }

    public function edit(Company $company)
    {
        return view('super_admin.tenants.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subscription_status' => 'nullable|string|max:50',
        ]);

        $company->update($data);

        return redirect()->route('super_admin.tenants.index')->with('success', 'Tenant updated.');
    }

    public function suspend(Company $company)
    {
        $company->update(['suspended' => true]);
        return redirect()->back()->with('success', 'Tenant suspended.');
    }

    public function unsuspend(Company $company)
    {
        $company->update(['suspended' => false]);
        return redirect()->back()->with('success', 'Tenant unsuspended.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('super_admin.tenants.index')->with('success', 'Tenant deleted.');
    }

    public function sendNotification(Company $company)
    {
        // Kirim notifikasi ke perusahaan tentang sisa waktu
        // Untuk sementara, kita asumsikan menggunakan email atau notifikasi internal
        // Anda bisa menggunakan Laravel Notification atau Mail

        // Contoh: Kirim email
        // Mail::to($company->users->first()->email ?? 'admin@company.com')->send(new SubscriptionExpiryNotification($company));

        return redirect()->back()->with('success', 'Notification sent to ' . $company->name);
    }
}
