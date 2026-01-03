<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SystemNotificationService
{
    /**
     * Kirim notifikasi stok menipis ke admin perusahaan.
     */
    public function notifyLowStock(Product $product): void
    {
        if (!$product->company_id) {
            return;
        }

        $threshold = (int) config('notifications.low_stock_threshold', 5);
        if ($product->stock === null || $product->stock > $threshold) {
            return;
        }

        $message = "PERINGATAN STOK MENIPIS\n" .
            "Produk: {$product->name} ({$product->sku})\n" .
            "Sisa stok: {$product->stock} unit";

        $this->notifyCompanyAdmins(
            $product->company_id,
            $message,
            'low_stock'
        );
    }

    /**
     * Kirim notifikasi langganan mendekati kadaluarsa.
     */
    public function notifySubscriptionExpiring(Company $company, int $daysLeft): void
    {
        if ($company->suspended) {
            return;
        }

        $expiresAt = $company->subscription_end_date ?? $company->subscription_expires_at;
        $dateText = $expiresAt ? Carbon::parse($expiresAt)->toDateString() : '-';

        $message = "LANGGANAN SEGERA BERAKHIR\n" .
            "Perusahaan: {$company->name}\n" .
            "Berakhir: {$dateText}\n" .
            "Sisa hari: {$daysLeft}\n" .
            "Mohon perpanjang untuk menghindari suspensi layanan.";

        $this->notifyCompanyAdmins($company->id, $message, 'subscription_expiring');
        $this->notifySuperAdmins($message, 'subscription_expiring');
    }

    /**
    * Cari perusahaan yang langganannya segera berakhir dan kirim notifikasi (dedupe harian).
    */
    public function notifySubscriptionExpiringForSoonDueCompanies(): void
    {
        $days = (int) config('notifications.subscription_expiry_days', 7);
        $today = Carbon::now();

        $companies = Company::where(function ($q) {
                $q->whereNotNull('subscription_end_date')
                    ->orWhereNotNull('subscription_expires_at');
            })
            ->where(function ($q) use ($today, $days) {
                $q->whereBetween('subscription_end_date', [$today, (clone $today)->addDays($days)])
                  ->orWhereBetween('subscription_expires_at', [$today, (clone $today)->addDays($days)]);
            })
            ->where(function ($q) {
                $q->whereNull('subscription_status')->orWhere('subscription_status', '!=', 'expired');
            })
            ->get();

        foreach ($companies as $company) {
            $expiresAt = $company->subscription_end_date ?? $company->subscription_expires_at;
            if (!$expiresAt) {
                continue;
            }

            $daysLeft = $today->diffInDays(Carbon::parse($expiresAt), false);
            if ($daysLeft < 0 || $daysLeft > $days) {
                continue;
            }

            $this->notifySubscriptionExpiring($company, $daysLeft);
        }
    }

    /**
     * Kirim notifikasi ketika user baru dibuat.
     */
    public function notifyUserCreated(User $user): void
    {
        if (!$user->company_id) {
            return;
        }

        $message = "USER BARU DIBUAT\n" .
            "Nama: {$user->name}\n" .
            "Email: {$user->email}\n" .
            "Role: {$user->role}\n" .
            "Perusahaan ID: {$user->company_id}";

        $this->notifyCompanyAdmins($user->company_id, $message, 'user_created');
    }

    /**
     * Helper: kirim ke semua admin perusahaan.
     */
    private function notifyCompanyAdmins(int $companyId, string $message, string $template): void
    {
        $admins = User::where('company_id', $companyId)
            ->where('role', 'admin')
            ->get();

        foreach ($admins as $admin) {
            $this->createIfNotDuplicate($admin->company_id, null, $admin->id, $template, $message);
        }
    }

    /**
     * Helper: kirim ke semua super admin.
     */
    private function notifySuperAdmins(string $message, string $template): void
    {
        $superAdmins = User::where('role', 'super_admin')->get();

        foreach ($superAdmins as $superAdmin) {
            $this->createIfNotDuplicate($superAdmin->company_id, null, $superAdmin->id, $template, $message);
        }
    }

    /**
     * Buat notifikasi jika belum ada notifikasi dengan template yang sama untuk penerima pada hari yang sama (dedupe).
     */
    private function createIfNotDuplicate(?int $companyId, ?int $senderId, int $recipientId, string $template, string $message): void
    {
        $dedupe = config('notifications.dedupe_same_day', true);

        if ($dedupe) {
            $exists = Notification::where('recipient_id', $recipientId)
                ->where('template', $template)
                ->where('message', $message)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($exists) {
                return;
            }
        }

        // Pastikan sender selalu terisi agar tidak melanggar constraint DB
        $senderId = $senderId ?? Auth::id() ?? $recipientId;

        // Isi company_id bila kosong menggunakan company penerima (opsional)
        if (!$companyId) {
            $companyId = User::find($recipientId)?->company_id;
        }

        Notification::create([
            'company_id' => $companyId,
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'template' => $template,
            'message' => $message,
        ]);
    }
}
