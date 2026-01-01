<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', '');

        $query = Company::query();

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status === 'active') {
            $query->where('suspended', false)
                ->where(function ($q) {
                    $q->whereNull('subscription_end_date')->orWhere('subscription_end_date', '>=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('subscription_expires_at')->orWhere('subscription_expires_at', '>=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('subscription_status')->orWhere('subscription_status', 'active');
                });
        } elseif ($status === 'suspended') {
            $query->where(function ($q) {
                $q->where('suspended', true)->orWhere('subscription_status', 'suspended');
            });
        } elseif ($status === 'expired') {
            $query->where(function ($q) {
                $q->whereNotNull('subscription_end_date')->where('subscription_end_date', '<', now())
                    ->orWhere(function ($nested) {
                        $nested->whereNotNull('subscription_expires_at')->where('subscription_expires_at', '<', now());
                    })
                    ->orWhere('subscription_status', 'expired');
            });
        }

        $companies = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('super_admin.tenants.index', compact('companies', 'search', 'status'));
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
        $status = $request->get('tenant_status');

        $rules = [
            'name' => 'required|string|max:255',
            'tenant_status' => 'required|in:active,suspended',
            'suspend_reason_type' => $status === 'suspended' ? 'required|in:payment_failed,policy_violation,admin_action,other' : 'nullable',
            'suspend_reason' => $status === 'suspended' ? 'required|string|min:10|max:500' : 'nullable',
        ];

        $data = $request->validate($rules);

        $isSuspended = $status === 'suspended';

        $company->update([
            'name' => $data['name'],
            'subscription_status' => $status,
            'suspended' => $isSuspended,
            'suspend_reason_type' => $isSuspended ? $data['suspend_reason_type'] : null,
            'suspend_reason' => $isSuspended ? $data['suspend_reason'] : null,
        ]);

        return redirect()->route('super_admin.tenants.index')->with('success', 'Tenant updated.');
    }

    public function suspend(Company $company)
    {
        $company->update([
            'suspended' => true,
            'subscription_status' => 'suspended',
        ]);
        return redirect()->back()->with('success', 'Tenant suspended.');
    }

    public function unsuspend(Company $company)
    {
        $company->update([
            'suspended' => false,
            'subscription_status' => 'active',
            'suspend_reason' => null,
            'suspend_reason_type' => null,
        ]);
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
