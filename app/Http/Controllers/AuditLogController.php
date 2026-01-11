<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuditLogController extends Controller
{
    /**
     * Display audit logs untuk company yang aktif
     * Support untuk demo mode (session-based)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        // Demo mode: use session data
        if ($isDemo) {
            return $this->indexDemo($request);
        }

        // Real mode: use database
        // Build query berdasarkan role
        $query = AuditLog::with(['user', 'company']);

        // Filter berdasarkan role
        if ($user->role === 'super_admin') {
            // Super admin bisa lihat semua
            // Tambahkan filter company jika dipilih
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }
        } else {
            // Admin & Staff hanya lihat company mereka
            $query->where('company_id', $user->company_id);
        }

        // Filter berdasarkan entity type
        if ($request->filled('entity_type')) {
            // Support both full class name and basename
            $entityType = $request->entity_type;
            if (!str_contains($entityType, '\\')) {
                // If basename provided, convert to full class name
                $query->where('entity_type', 'like', '%\\' . $entityType);
            } else {
                $query->where('entity_type', $entityType);
            }
        }

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('entity_type', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('entity_id', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        // Data untuk filter dropdown - return as associative array with full path as key and basename as value
        $entityTypes = AuditLog::query()
            ->when($user->role !== 'super_admin', fn($q) => $q->where('company_id', $user->company_id))
            ->distinct()
            ->pluck('entity_type')
            ->mapWithKeys(fn($type) => [class_basename($type) => class_basename($type)])
            ->sort()
            ->values();

        $actions = ['created', 'updated', 'deleted'];

        $users = \App\Models\User::query()
            ->when($user->role !== 'super_admin', fn($q) => $q->where('company_id', $user->company_id))
            ->orderBy('name')
            ->get(['id', 'name']);

        $companies = [];
        if ($user->role === 'super_admin') {
            $companies = \App\Models\Company::orderBy('name')->get(['id', 'name']);
        }

        return view('audit_logs.index', compact('logs', 'entityTypes', 'actions', 'users', 'companies'));
    }

    /**
     * Demo mode: Display audit logs dari session
     */
    private function indexDemo(Request $request)
    {
        $demoLogs = Session::get('demo_audit_logs', []);

        // Apply filters jika ada
        $filtered = collect($demoLogs);

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $filtered = $filtered->where('action', $request->action);
        }

        // Filter berdasarkan entity type
        if ($request->filled('entity_type')) {
            $filtered = $filtered->where('entity', $request->entity_type);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $filtered = $filtered->where('user_id', (int)$request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $dateFrom = strtotime($request->date_from);
            $filtered = $filtered->filter(function($log) use ($dateFrom) {
                $logDate = strtotime($log['timestamp'] ?? $log['created_at'] ?? '');
                return $logDate >= $dateFrom;
            });
        }

        if ($request->filled('date_to')) {
            $dateTo = strtotime($request->date_to . ' 23:59:59');
            $filtered = $filtered->filter(function($log) use ($dateTo) {
                $logDate = strtotime($log['timestamp'] ?? $log['created_at'] ?? '');
                return $logDate <= $dateTo;
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $filtered = $filtered->filter(function($log) use ($search) {
                return strpos(strtolower($log['entity'] ?? ''), $search) !== false ||
                       strpos(strtolower($log['action'] ?? ''), $search) !== false ||
                       strpos($log['entity_id'] ?? '', $search) !== false;
            });
        }

        // Sort by timestamp descending
        $logs = $filtered->sortByDesc('timestamp')->values();

        // Get unique values for filters
        $entityTypes = collect($demoLogs)->pluck('entity')->unique()->sort()->values();
        $actions = ['created', 'updated', 'deleted', 'viewed'];

        // Demo users for filter
        $users = collect([
            (object)['id' => 1, 'name' => 'Admin Demo'],
            (object)['id' => 2, 'name' => 'Staff Demo'],
        ]);

        $companies = [];

        return view('audit_logs.index', compact('logs', 'entityTypes', 'actions', 'users', 'companies'))->with('isDemo', true);
    }

    /**
     * Show detail audit log
     */
    public function show($id)
    {
        $user = Auth::user();
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        if ($isDemo) {
            $demoLogs = Session::get('demo_audit_logs', []);
            $log = collect($demoLogs)->firstWhere('id', (int)$id);

            if (!$log) {
                abort(404, 'Audit log tidak ditemukan');
            }

            return view('audit_logs.show', compact('log'))->with('isDemo', true);
        }

        $log = AuditLog::with(['user', 'company'])->findOrFail($id);

        // Authorization check
        if ($user->role !== 'super_admin' && $log->company_id !== $user->company_id) {
            abort(403, 'Unauthorized');
        }

        return view('audit_logs.show', compact('log'));
    }

    /**
     * Export audit logs to CSV (opsional)
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        $query = AuditLog::with(['user', 'company']);

        if ($user->role !== 'super_admin') {
            $query->where('company_id', $user->company_id);
        }

        // Apply same filters as index
        if ($request->filled('entity_type')) {
            // Support both full class name and basename
            $entityType = $request->entity_type;
            if (!str_contains($entityType, '\\')) {
                // If basename provided, convert to full class name
                $query->where('entity_type', 'like', '%\\' . $entityType);
            } else {
                $query->where('entity_type', $entityType);
            }
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, ['Timestamp', 'User', 'Company', 'Entity Type', 'Entity ID', 'Action', 'Changes', 'IP Address']);

            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name ?? '-',
                    $log->company?->name ?? '-',
                    class_basename($log->entity_type),
                    $log->entity_id,
                    $log->action,
                    json_encode($log->changes),
                    $log->ip_address ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
