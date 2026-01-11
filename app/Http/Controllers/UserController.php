<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\SystemNotificationService;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin only).
     */
    public function index(Request $request)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            // Get demo users from session or config
            $demoUsers = session('demo_users', config('demo_data.users', []));

            $users = collect($demoUsers)->map(fn($u) => (object) $u);

            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower($request->search);
                $users = $users->filter(function($u) use ($search) {
                    return str_contains(strtolower($u->name ?? ''), $search) ||
                           str_contains(strtolower($u->email ?? ''), $search) ||
                           str_contains(strtolower($u->role ?? ''), $search);
                })->values();
            }

            return view('users.index', ['users' => $users, 'isDemo' => true]);
        }

        // Tentukan company yang sedang aktif (hanya boleh melihat user dalam company tersebut)
        $activeCompanyId = Auth::user()?->company_id ?: $request->get('company_id');

        $query = User::query();

        // Batasi hanya untuk company yang aktif; jika tidak ada company dipilih, kembalikan kosong
        if ($activeCompanyId) {
            $query->where('company_id', $activeCompanyId);
        } else {
            $users = collect();
            return view('users.index', compact('users'));
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['admin', 'staf'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'role' => ['required', Rule::in(['admin', 'staf'])],
            ]);
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan! (Simulasi - Data tidak tersimpan)');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(fn($q) => $q->where('company_id', Auth::user()?->company_id)),
            ],
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin', 'staf'])],
        ]);

        // Normalisasi input role agar sesuai enum di database
        if (($data['role'] ?? null) === 'staff') {
            $data['role'] = 'staf';
        }

        // Pastikan user baru terikat ke company yang sama dengan user yang membuatnya
        $data['company_id'] = Auth::user()?->company_id ?? $request->get('company_id');

        if (!$data['company_id']) {
            return redirect()->route('users.index')->with('error', 'Pilih perusahaan terlebih dahulu sebelum membuat user.');
        }

        $data['password'] = Hash::make($data['password']);

        $newUser = User::create($data);

        // Notifikasi user baru untuk admin & super admin
        app(SystemNotificationService::class)->notifyUserCreated($newUser);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            $users = config('demo_data.users', []);
            $user = collect($users)->firstWhere('id', (int)$id);
            if (!$user) {
                return redirect()->route('users.index')->with('error', 'User tidak ditemukan.');
            }
            $user = (object) $user;
            $roles = ['admin', 'staf'];
            return view('users.edit', compact('user', 'roles'));
        }

        $activeCompanyId = Auth::user()?->company_id ?: request()->get('company_id');
        $user = User::where('id', $id)
            ->when($activeCompanyId, fn($q) => $q->where('company_id', $activeCompanyId))
            ->firstOrFail();
        $roles = ['admin', 'staf'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui! (Simulasi - Data tidak tersimpan)');
        }

        $activeCompanyId = Auth::user()?->company_id ?: $request->get('company_id');
        $user = User::where('id', $id)
            ->when($activeCompanyId, fn($q) => $q->where('company_id', $activeCompanyId))
            ->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->where(fn($q) => $q->where('company_id', $activeCompanyId)),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin','staf'])],
        ]);

        // Normalisasi input role agar sesuai enum di database
        if (($data['role'] ?? null) === 'staff') {
            $data['role'] = 'staf';
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $isDemoMode = session('is_demo') || session('demo_mode');

        if ($isDemoMode) {
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus! (Simulasi - Data tidak tersimpan)');
        }

        $activeCompanyId = Auth::user()?->company_id ?: request()->get('company_id');
        $user = User::where('id', $id)
            ->when($activeCompanyId, fn($q) => $q->where('company_id', $activeCompanyId))
            ->firstOrFail();

        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Hapus notifikasi yang terkait user ini
        \App\Models\Notification::where('recipient_id', $user->id)->delete();

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
