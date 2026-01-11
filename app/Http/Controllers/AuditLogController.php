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
            // Admin & Staf hanya lihat company mereka
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
            $search = trim((string) $request->search);
            if ($search !== '') {
                $companyIdForEntityLookup = ($user->role === 'super_admin')
                    ? ($request->filled('company_id') ? (int) $request->company_id : null)
                    : (int) $user->company_id;

                // Agar sesuai dengan tabel: jika entity_name di audit_logs kosong, kita cari juga di tabel entitasnya.
                // (Produk/Kategori/Pemasok/User, serta InventoryIn/Out via nama produk)
                $productIds = collect();
                $categoryIds = collect();
                $supplierIds = collect();
                $userEntityIds = collect();
                $inventoryInIds = collect();
                $inventoryOutIds = collect();

                $productQuery = \App\Models\Product::query()->where('name', 'like', "%{$search}%");
                $categoryQuery = \App\Models\Category::query()->where('name', 'like', "%{$search}%");
                $supplierQuery = \App\Models\Supplier::query()->where('name', 'like', "%{$search}%");
                $userEntityQuery = \App\Models\User::query()->where(function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });

                if ($companyIdForEntityLookup) {
                    $productQuery->where('company_id', $companyIdForEntityLookup);
                    $categoryQuery->where('company_id', $companyIdForEntityLookup);
                    $supplierQuery->where('company_id', $companyIdForEntityLookup);
                    $userEntityQuery->where('company_id', $companyIdForEntityLookup);
                }

                $productIds = $productQuery->limit(1000)->pluck('id');
                $categoryIds = $categoryQuery->limit(1000)->pluck('id');
                $supplierIds = $supplierQuery->limit(1000)->pluck('id');
                $userEntityIds = $userEntityQuery->limit(1000)->pluck('id');

                $inventoryInQuery = \App\Models\InventoryIn::query()->whereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
                $inventoryOutQuery = \App\Models\InventoryOut::query()->whereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });

                if ($companyIdForEntityLookup) {
                    $inventoryInQuery->where('company_id', $companyIdForEntityLookup);
                    $inventoryOutQuery->where('company_id', $companyIdForEntityLookup);
                }

                $inventoryInIds = $inventoryInQuery->limit(1000)->pluck('id');
                $inventoryOutIds = $inventoryOutQuery->limit(1000)->pluck('id');

                $query->where(function ($q) use (
                    $search,
                    $productIds,
                    $categoryIds,
                    $supplierIds,
                    $userEntityIds,
                    $inventoryInIds,
                    $inventoryOutIds
                ) {
                    // Kolom yang memang ada di audit_logs
                    $q->where('entity_type', 'like', "%{$search}%")
                        ->orWhere('entity_name', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('entity_id', 'like', "%{$search}%")
                        ->orWhere('changes', 'like', "%{$search}%")
                        // Pengguna (nama/email) untuk kolom PENGGUNA di tabel
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        // Perusahaan (kolom PERUSAHAAN di tabel untuk super admin)
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });

                    // Fallback: cari berdasarkan nama entitas sebenarnya (sesuai tampilan getEntityName())
                    if ($productIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($productIds) {
                            $qq->where('entity_type', \App\Models\Product::class)->whereIn('entity_id', $productIds);
                        });
                    }
                    if ($categoryIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($categoryIds) {
                            $qq->where('entity_type', \App\Models\Category::class)->whereIn('entity_id', $categoryIds);
                        });
                    }
                    if ($supplierIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($supplierIds) {
                            $qq->where('entity_type', \App\Models\Supplier::class)->whereIn('entity_id', $supplierIds);
                        });
                    }
                    if ($userEntityIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($userEntityIds) {
                            $qq->where('entity_type', \App\Models\User::class)->whereIn('entity_id', $userEntityIds);
                        });
                    }
                    if ($inventoryInIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($inventoryInIds) {
                            $qq->where('entity_type', \App\Models\InventoryIn::class)->whereIn('entity_id', $inventoryInIds);
                        });
                    }
                    if ($inventoryOutIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($inventoryOutIds) {
                            $qq->where('entity_type', \App\Models\InventoryOut::class)->whereIn('entity_id', $inventoryOutIds);
                        });
                    }
                });
            }
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
            $search = strtolower(trim((string) $request->search));

            $filtered = $filtered->filter(function ($log) use ($search) {
                // Catatan: pencarian hanya mencakup data yang tampil di tabel (kecuali waktu)
                $haystacks = [
                    strtolower((string) ($log['user_name'] ?? '')),
                    strtolower((string) ($log['entity'] ?? '')),
                    strtolower((string) ($log['entity_name'] ?? '')),
                    strtolower((string) ($log['action'] ?? '')),
                    strtolower((string) ($log['entity_id'] ?? '')),
                    strtolower(json_encode($log['old_values'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
                    strtolower(json_encode($log['new_values'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
                ];

                foreach ($haystacks as $text) {
                    if ($text !== '' && str_contains($text, $search)) {
                        return true;
                    }
                }

                return false;
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
            (object)['id' => 2, 'name' => 'Staf Demo'],
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
                abort(404, 'Aktivitas tidak ditemukan');
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

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            if ($search !== '') {
                $companyIdForEntityLookup = ($user->role === 'super_admin' && $request->filled('company_id'))
                    ? (int) $request->company_id
                    : (($user->role !== 'super_admin') ? (int) $user->company_id : null);

                $productQuery = \App\Models\Product::query()->where('name', 'like', "%{$search}%");
                $categoryQuery = \App\Models\Category::query()->where('name', 'like', "%{$search}%");
                $supplierQuery = \App\Models\Supplier::query()->where('name', 'like', "%{$search}%");
                $userEntityQuery = \App\Models\User::query()->where(function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });

                if ($companyIdForEntityLookup) {
                    $productQuery->where('company_id', $companyIdForEntityLookup);
                    $categoryQuery->where('company_id', $companyIdForEntityLookup);
                    $supplierQuery->where('company_id', $companyIdForEntityLookup);
                    $userEntityQuery->where('company_id', $companyIdForEntityLookup);
                }

                $productIds = $productQuery->limit(1000)->pluck('id');
                $categoryIds = $categoryQuery->limit(1000)->pluck('id');
                $supplierIds = $supplierQuery->limit(1000)->pluck('id');
                $userEntityIds = $userEntityQuery->limit(1000)->pluck('id');

                $inventoryInQuery = \App\Models\InventoryIn::query()->whereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
                $inventoryOutQuery = \App\Models\InventoryOut::query()->whereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });

                if ($companyIdForEntityLookup) {
                    $inventoryInQuery->where('company_id', $companyIdForEntityLookup);
                    $inventoryOutQuery->where('company_id', $companyIdForEntityLookup);
                }

                $inventoryInIds = $inventoryInQuery->limit(1000)->pluck('id');
                $inventoryOutIds = $inventoryOutQuery->limit(1000)->pluck('id');

                $query->where(function ($q) use (
                    $search,
                    $productIds,
                    $categoryIds,
                    $supplierIds,
                    $userEntityIds,
                    $inventoryInIds,
                    $inventoryOutIds
                ) {
                    $q->where('entity_type', 'like', "%{$search}%")
                        ->orWhere('entity_name', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('entity_id', 'like', "%{$search}%")
                        ->orWhere('changes', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });

                    if ($productIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($productIds) {
                            $qq->where('entity_type', \App\Models\Product::class)->whereIn('entity_id', $productIds);
                        });
                    }
                    if ($categoryIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($categoryIds) {
                            $qq->where('entity_type', \App\Models\Category::class)->whereIn('entity_id', $categoryIds);
                        });
                    }
                    if ($supplierIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($supplierIds) {
                            $qq->where('entity_type', \App\Models\Supplier::class)->whereIn('entity_id', $supplierIds);
                        });
                    }
                    if ($userEntityIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($userEntityIds) {
                            $qq->where('entity_type', \App\Models\User::class)->whereIn('entity_id', $userEntityIds);
                        });
                    }
                    if ($inventoryInIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($inventoryInIds) {
                            $qq->where('entity_type', \App\Models\InventoryIn::class)->whereIn('entity_id', $inventoryInIds);
                        });
                    }
                    if ($inventoryOutIds->isNotEmpty()) {
                        $q->orWhere(function ($qq) use ($inventoryOutIds) {
                            $qq->where('entity_type', \App\Models\InventoryOut::class)->whereIn('entity_id', $inventoryOutIds);
                        });
                    }
                });
            }
        }

        $logs = $query->latest()->get();

        $filename = 'aktivitas_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, ['Timestamp', 'User', 'Company', 'Entity Type', 'Entity ID', 'Action', 'Changes']);

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
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
