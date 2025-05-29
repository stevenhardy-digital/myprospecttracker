<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DowngradeExpiredGraceUsers extends Command
{
    protected $signature = 'users:downgrade-expired-grace';
    protected $description = 'Downgrade users whose grace period has expired';

    public function handle()
    {
        $users = User::whereHas('subscriptions', function ($q) {
            $q->whereNotNull('ends_at');
        })->get();

        $now = now();

        foreach ($users as $user) {
            if ($user->plan === 'pro' && $user->gracePeriodEndsAt() && $now->gt($user->gracePeriodEndsAt())) {
                $user->plan = 'free';
                $user->save();

                Log::info("Downgraded user {$user->email} after grace period.");
            }
        }

        $this->info('Checked all users for expired grace periods.');
    }
}
