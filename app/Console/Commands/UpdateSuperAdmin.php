<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update first user to super_admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        if ($user) {
            $user->role = 'super_admin';
            $user->save();
            $this->info("User {$user->email} updated to super_admin role");
        } else {
            $this->error("No users found");
        }
    }
}
