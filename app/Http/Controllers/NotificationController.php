<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function index()
    {
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        // Notifikasi hanya untuk admin atau super admin
        $role = Auth::user()?->role;
        if (!$isDemo && !in_array($role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        if ($isDemo) {
            return $this->indexDemo();
        }

        // Real mode: Untuk admin melihat notifikasi
        $notifications = Notification::where('recipient_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Demo mode: Display notifications dari session
     */
    private function indexDemo()
    {
        $demoNotifications = Session::get('demo_notifications', []);

        // Filter untuk user yang login (berdasarkan demo role)
        $demoRole = Session::get('demo_role');
        $userId = $demoRole === 'admin' ? 1 : 2;

        // Filter notifications untuk user yang login
        $notifications = collect($demoNotifications)
            ->where('user_id', $userId)
            ->sortByDesc('created_at')
            ->values();

        return view('notifications.index', compact('notifications'))->with('isDemo', true);
    }

    public function create(Request $request)
    {
        // Untuk super admin membuat notifikasi
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        $companies = User::where('role', 'admin')->with('company')->get();
        $templates = $this->getTemplates();

        // Pre-fill recipient jika ada company_id dari query parameter (dari Tenants page)
        $selectedCompanyId = $request->get('company_id');
        $selectedRecipient = null;
        if ($selectedCompanyId) {
            $selectedRecipient = User::where('company_id', $selectedCompanyId)
                ->where('role', 'admin')
                ->first();
        }

        return view('notifications.create', compact('companies', 'templates', 'selectedRecipient'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'template' => 'nullable|string',
            'message' => 'required|string',
        ]);

        $recipient = User::findOrFail($request->recipient_id);

        Notification::create([
            'company_id' => $recipient->company_id,
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'template' => $request->template,
            'message' => $request->message,
        ]);

        return redirect()->route('super_admin.dashboard')->with('success', 'Notifikasi berhasil dikirim.');
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('recipient_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back();
    }

    private function getTemplates()
    {
        return [
            'maintenance' => 'Pemberitahuan Maintenance: Sistem akan mengalami downtime untuk pemeliharaan pada [tanggal].',
            'update' => 'Update Sistem: Fitur baru telah ditambahkan. Silakan periksa dashboard untuk detail.',
            'reminder' => 'Pengingat: Pastikan semua data inventaris sudah diperbarui.',
            'subscription_expiry' => 'Pemberitahuan: Sisa waktu langganan Anda tersisa 7 hari. Silakan perpanjang langganan untuk menghindari gangguan layanan.',
            'announcement' => 'Pengumuman: [Pesan khusus dari Super Admin].',
        ];
    }
}
