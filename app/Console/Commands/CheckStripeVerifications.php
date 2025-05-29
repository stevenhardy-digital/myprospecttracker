<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Account;
use Illuminate\Support\Facades\Log;

class CheckStripeVerifications extends Command
{
    protected $signature = 'stripe:check-verifications';
    protected $description = 'Check Stripe Connect account verification status for all users';

    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $users = User::whereNotNull('stripe_connect_id')->get();

        foreach ($users as $user) {
            try {
                $account = Account::retrieve($user->stripe_connect_id);

                $requirements = $account->requirements->currently_due ?? [];

                $needsVerification = !empty($requirements);

                if ($user->stripe_requires_verification !== $needsVerification) {
                    $user->stripe_requires_verification = $needsVerification;
                    $user->save();

                    Log::info("Updated verification status for user #{$user->id} ({$user->email}): needs_verification = " . ($needsVerification ? 'true' : 'false'));
                }
            } catch (\Exception $e) {
                Log::error("Failed to check verification for user #{$user->id}: " . $e->getMessage());
            }
        }

        $this->info('Stripe verification check completed.');
    }
}
