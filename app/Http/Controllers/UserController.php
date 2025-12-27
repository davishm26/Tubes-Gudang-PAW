<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin only).
     */
    public function index()
    {
        if (session('demo_mode') === 'true') {
            $users = collect(session('demo_users', []))->map(function ($u) {
                return (object) $u;
            });
            if ($users->isEmpty()) {
                $users = collect([
                    ['id' => 1, 'name' => 'Demo Admin', 'email' => 'admin@demo.com', 'role' => 'admin'],
                    ['id' => 2, 'name' => 'Demo Staff', 'email' => 'staff@demo.com', 'role' => 'staff'],
                ])->map(fn($u) => (object) $u);
                session(['demo_users' => $users]);
            }
            return view('users.index', ['users' => $users]);
        }
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['admin', 'staff'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (session('demo_mode') === 'true') {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'role' => ['required', Rule::in(['admin', 'staff'])],
            ]);
            $users = collect(session('demo_users', []));
            $nextId = ($users->max('id') ?? 0) + 1;
            $users = $users->push(['id' => $nextId, 'name' => $data['name'], 'email' => $data['email'], 'role' => $data['role']]);
            session(['demo_users' => $users]);
            return redirect()->route('users.index')->with('success', 'Pengguna demo ditambahkan (tidak ke database).');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin', 'staff'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (session('demo_mode') === 'true') {
            return redirect()->route('users.index')->with('info', 'Edit user dinonaktifkan pada demo mode.');
        }
        $roles = ['admin', 'staff'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (session('demo_mode') === 'true') {
            return redirect()->route('users.index')->with('info', 'Update user dinonaktifkan pada demo mode.');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin','staff'])],
        ]);

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
    public function destroy(User $user)
    {
        if (session('demo_mode') === 'true') {
            return redirect()->route('users.index')->with('info', 'Hapus user dinonaktifkan pada demo mode.');
        }
        // Prevent deleting self
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
