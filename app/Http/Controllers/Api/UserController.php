<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $companyId = $request->input('company_id');
        $query = User::query();
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        $query->where('role', '!=', 'super_admin');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        $users = $query->get();
        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $companyId = Auth::user()->company_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'staf', 'staff'])],
        ]);

        $role = $request->role === 'staff' ? 'staf' : $request->role;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'company_id' => $companyId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified user
     */
    public function show($id, Request $request)
    {
        $companyId = $request->input('company_id');
        $user = User::query();
        if ($companyId) {
            $user->where('company_id', $companyId);
        }
        $user->where('role', '!=', 'super_admin');
        $user = $user->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $companyId = $request->input('company_id');
        $user = User::query();
        if ($companyId) {
            $user->where('company_id', $companyId);
        }
        $user->where('role', '!=', 'super_admin');
        $user = $user->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['admin', 'staf', 'staff'])],
        ]);
        $role = $request->role === 'staff' ? 'staf' : $request->role;
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified user
     */
    public function destroy($id, Request $request)
    {
        $companyId = $request->input('company_id');
        $user = User::query();
        if ($companyId) {
            $user->where('company_id', $companyId);
        }
        $user->where('role', '!=', 'super_admin');
        $user = $user->findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }
}
