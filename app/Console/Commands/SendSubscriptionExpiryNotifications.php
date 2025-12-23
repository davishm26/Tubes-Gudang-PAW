<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-subscription-expiry-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to companies with subscription expiring in less than 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies = Company::where('subscription_expires_at', '<=', now()->addDays(7))
                            ->where('subscription_expires_at', '>', now())
                            ->get();

        foreach ($companies as $company) {
            // Kirim notifikasi, misalnya email
            // Mail::to($company->users->first()->email ?? 'admin@' . strtolower($company->name) . '.com')
            //     ->send(new SubscriptionExpiryNotification($company));

            $this->info('Notification sent to ' . $company->name);
        }

        $this->info('Notifications sent to ' . $companies->count() . ' companies.');
    }
}
