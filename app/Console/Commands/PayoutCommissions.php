<?php

namespace App\Console\Commands;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\ReferrerFee;
use Stripe\StripeClient;

class PayoutCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pay:commissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $eligible = Commission::where('paid', false)
            ->whereDate('earned_at', '<=', now()->subDays(7))
            ->get();

        foreach ($eligible->groupBy('referrer_id') as $referrerId => $commissions) {
            $total = $commissions->sum('amount');

            $referrer = User::find($referrerId);
            if (! $referrer || ! $referrer->stripe_connect_id) continue;

            // Deduct £2 platform fee once per month per referrer
            $month = now()->format('Y-m');
            $alreadyCharged = ReferrerFee::where('referrer_id', $referrerId)->where('month', $month)->exists();

            if (! $alreadyCharged) {
                $total -= 2.00;

                ReferrerFee::create([
                    'referrer_id' => $referrerId,
                    'amount' => 2.00,
                    'month' => $month,
                ]);
            }

            // Deduct payout processing fee (0.25% + £0.10)
            $processingFee = round($total * 0.0025 + 0.10, 2);
            $finalPayout = max(0, $total - $processingFee);

            if ($finalPayout <= 0) continue;

            // Send payout via Stripe Connect
            $stripe = new StripeClient(config('services.stripe.secret'));
            $stripe->transfers->create([
                'amount' => (int) round($finalPayout * 100),
                'currency' => 'gbp',
                'destination' => $referrer->stripe_connect_id,
                'transfer_group' => 'referral_payout_' . now()->format('Ymd'),
            ]);

            Log::info("Paid £{$finalPayout} to user #{$referrerId}");

            // Mark commissions as paid
            Commission::whereIn('id', $commissions->pluck('id'))->update(['paid' => true]);
        }
    }
}
