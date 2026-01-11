<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        // Log saat model dibuat
        static::created(function ($model) {
            if (static::shouldAudit('created')) {
                $name = static::extractEntityName($model);
                static::logAudit($model, 'created', [
                    'new' => $model->getAuditableAttributes(),
                ], $name);
            }
        });

        // Log saat model diupdate
        static::updated(function ($model) {
            if (static::shouldAudit('updated')) {
                $changes = [];
                $dirty = $model->getDirty();

                foreach ($dirty as $key => $newValue) {
                    if (in_array($key, static::getAuditExcludedAttributes())) {
                        continue;
                    }

                    $changes[$key] = [
                        'old' => $model->getOriginal($key),
                        'new' => $newValue,
                    ];
                }

                if (!empty($changes)) {
                    static::logAudit($model, 'updated', $changes);
                }
            }
        });

        // Log saat model dihapus
        static::deleted(function ($model) {
            if (static::shouldAudit('deleted')) {
                static::logAudit($model, 'deleted', [
                    'deleted' => $model->getAuditableAttributes(),
                ]);
            }
        });
    }

    /**
     * Catat aktivitas ke audit log
     */
    protected static function logAudit($model, string $action, ?array $changes = null, ?string $entityName = null): void
    {
        $user = Auth::user();

        // Skip jika tidak ada user
        if (!$user) {
            return;
        }

        // Untuk model dengan BelongsToCompany trait
        $companyId = $model->company_id ?? $user->company_id ?? null;

        // Skip jika tidak ada company_id (kecuali super admin)
        if (!$companyId && (($user->role ?? null) !== 'super_admin')) {
            return;
        }

        // Jika user adalah super admin dan tidak ada company_id, gunakan company pertama sebagai default
        if (!$companyId && (($user->role ?? null) === 'super_admin')) {
            $companyId = \App\Models\Company::first()?->id;

            // Jika tidak ada company sama sekali, skip
            if (!$companyId) {
                return;
            }
        }

        // Extract entity name jika tidak diberikan
        if (!$entityName) {
            $entityName = static::extractEntityName($model);
        }

        $auditData = [
            'company_id' => $companyId,
            'user_id' => $user->id,
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'entity_name' => $entityName,
            'action' => $action,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        AuditLog::create($auditData);
    }

    /**
     * Extract nama entity untuk disimpan di audit log
     */
    protected static function extractEntityName($model): ?string
    {
        // Model dapat mengoverride dengan method getAuditEntityName()
        if (method_exists($model, 'getAuditEntityName')) {
            try {
                $custom = $model->getAuditEntityName();
                if ($custom) return $custom;
            } catch (\Throwable $e) {
                // ignore failures
            }
        }

        return $model->name ?? $model->title ?? $model->email ?? null;
    }

    /**
     * Cek apakah aksi tertentu harus di-audit
     */
    protected static function shouldAudit(string $action): bool
    {
        // Override method ini di model jika ingin custom
        $auditActions = static::getAuditActions();

        return in_array($action, $auditActions);
    }

    /**
     * Daftar aksi yang di-audit (default semua)
     */
    protected static function getAuditActions(): array
    {
        $defaults = static::getDefaultStaticProperties();

        return $defaults['auditActions'] ?? ['created', 'updated', 'deleted'];
    }

    /**
     * Atribut yang dikecualikan dari audit (seperti password, timestamps)
     */
    protected static function getAuditExcludedAttributes(): array
    {
        $defaults = static::getDefaultStaticProperties();

        return $defaults['auditExcluded'] ?? ['password', 'remember_token', 'updated_at'];
    }

    protected static function getDefaultStaticProperties(): array
    {
        static $cache = [];
        $class = static::class;

        if (!isset($cache[$class])) {
            $cache[$class] = (new \ReflectionClass($class))->getDefaultProperties();
        }

        return $cache[$class];
    }

    /**
     * Ambil atribut yang akan di-audit
     */
    protected function getAuditableAttributes(): array
    {
        $attributes = $this->getAttributes();
        $excluded = static::getAuditExcludedAttributes();

        return array_diff_key($attributes, array_flip($excluded));
    }

    /**
     * Log manual untuk aksi custom (view, export, dll)
     */
    public function logCustomAction(string $action, ?array $changes = null): void
    {
        static::logAudit($this, $action, $changes);
    }
}
