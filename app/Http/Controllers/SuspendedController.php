<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuspendedController extends Controller
{
    public function show()
    {
        // Cek apakah user masih login (jika sudah logout, ambil dari session flash)
        $suspendReason = session('suspend_reason');
        $suspendReasonType = session('suspend_reason_type');
        $company = null;

        // Jika belum ada di session, coba ambil dari user yang login
        if (!$suspendReason && Auth::check()) {
            $user = Auth::user();

            if ($user && $user->company_id) {
                $company = \App\Models\Company::find($user->company_id);

                if ($company && $company->suspended) {
                    $suspendReason = $company->suspend_reason;
                    $suspendReasonType = $company->suspend_reason_type;

                    // Simpan ke session untuk akses selanjutnya
                    session(['suspend_reason' => $suspendReason]);
                    session(['suspend_reason_type' => $suspendReasonType]);
                }
            }
        } else if (session('company_id')) {
            // Ambil company dari session jika user sudah logout
            $company = \App\Models\Company::find(session('company_id'));
        }

        return view('subscription.suspended', compact('suspendReason', 'suspendReasonType', 'company'));
    }

    public function requestReactivation(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $companyId = session('company_id');
        if (!$companyId && Auth::check()) {
            $companyId = Auth::user()->company_id;
        }

        if (!$companyId) {
            return redirect()->route('subscription.suspended')->with('error', 'Tidak dapat menemukan informasi perusahaan.');
        }

        $company = \App\Models\Company::find($companyId);
        if (!$company) {
            return redirect()->route('subscription.suspended')->with('error', 'Perusahaan tidak ditemukan.');
        }

        // Buat notifikasi untuk super admin
        $superAdmins = \App\Models\User::where('role', 'super_admin')->get();

        // Buat sender_id sebagai ID user yang login atau null jika sudah logout
        $senderId = Auth::check() ? Auth::id() : null;

        // Jika tidak ada user yang login, gunakan ID admin dari company (jika ada)
        if (!$senderId) {
            $companyAdmin = \App\Models\User::where('company_id', $companyId)
                ->where('role', 'admin')
                ->first();
            $senderId = $companyAdmin ? $companyAdmin->id : 1; // Fallback ke user pertama (super admin)
        }

        foreach ($superAdmins as $superAdmin) {
            \App\Models\Notification::create([
                'sender_id' => $senderId,
                'recipient_id' => $superAdmin->id,
                'template' => 'reactivation_request',
                'message' => "PERMINTAAN REAKTIVASI AKUN\n\nPerusahaan: {$company->name}\n\nPesan: {$request->message}\n\nKontak Email: {$request->contact_email}" . ($request->contact_phone ? "\nTelpon: {$request->contact_phone}" : ''),
            ]);
        }

        return redirect()->route('subscription.suspended')->with('success', 'Permintaan reaktivasi telah dikirim ke administrator. Tim kami akan segera meninjau dan menghubungi Anda.');
    }
}
