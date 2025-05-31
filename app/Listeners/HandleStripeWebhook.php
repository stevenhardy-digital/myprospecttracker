<?php

namespace App\Listeners;

use App\Notifications\CompleteStripeVerification;
use App\Notifications\OnboardingReminder;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;
use App\Notifications\DowngradedToFree;
use Illuminate\Support\Facades\Log;
use App\Models\Commission;

class HandleStripeWebhook
{
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;

        $type = $payload['type'] ?? null;
        $object = $payload['data']['object'] ?? [];

        Log::info("Stripe webhook received: {$type}", ['payload' => $payload]);

        if (!isset($object['customer'])) {
            Log::warning("Webhook received without customer ID", ['type' => $type, 'object' => $object]);
            return;
        }

        $user = User::where('stripe_id', $object['customer'])->first();

        if (!$user) {
            Log::warning("Stripe webhook received but user not found: {$object['customer']}");
            return;
        }

        Log::info("Stripe webhook matched to user: {$user->id} ({$user->email})");

        match ($type) {
            'customer.subscription.created',
            'customer.subscription.updated' => tap(
                $this->handleSubscriptionCreatedOrUpdated($user, $object),
                fn () => Log::info("Handled subscription created/updated", ['user_id' => $user->id])
            ),

            'customer.subscription.deleted',
            'invoice.payment_failed' => tap(
                $this->handleSubscriptionCancelled($user),
                fn () => Log::info("Handled subscription cancelled/payment failed", ['user_id' => $user->id])
            ),

            'checkout.session.completed' => tap(
                $this->handleCheckoutCompleted($object),
                fn () => Log::info("Handled checkout session", ['stripe_customer_id' => $object['customer'] ?? null])
            ),

            'invoice.payment_succeeded',
            'invoice.paid' => tap(
                $this->handleInvoicePaid($object),
                fn () => Log::info("Handled invoice paid", ['stripe_customer_id' => $object['customer'] ?? null])
            ),

            'account.updated' =>  tap(
                $this->handleAccountUpdated($object),
                fn () => Log::info("Handled Account Updated", ['stripe_customer_id' => $object['customer'] ?? null])
            ),

            default => Log::info("No handler defined for this webhook type", ['type' => $type]),
        };
    }

    protected function handleSubscriptionCreatedOrUpdated(User $user, array $subscription)
    {
        $status = $subscription['status'] ?? 'incomplete';

        if ($status === 'active' || $status === 'trialing') {
            $trialEnd = isset($subscription['trial_end']) ? now()->setTimestamp($subscription['trial_end']) : null;

            Log::info('Updating trial end', ['user_id' => $user->id, 'trial_end' => $trialEnd]);

            $user->plan = 'pro';
            $user->payment_status = $status === 'trialing' ? 'trial' : 'paid';
            $user->trial_ends_at = $trialEnd;
            $user->save();

            Log::info('User saved with updated trial_end', ['user_id' => $user->id]);
        }
    }

    protected function handleSubscriptionCancelled(User $user)
    {
        $subscription = $user->subscription('default');

        if ($subscription && !$subscription->canceled()) {
            $subscription->markAsCanceled();
            $subscription->grace_ends_at = now()->addDays(7);
            $subscription->save();

            $user->notify(new DowngradedToFree);
        }
    }

    protected function handleCheckoutCompleted(array $session)
    {
        $stripeCustomerId = $session['customer'] ?? null;
        $clientReferenceId = $session['client_reference_id'] ?? null;

        if (!$stripeCustomerId || !$clientReferenceId) {
            return;
        }

        $user = User::find($clientReferenceId);

        if (!$user) {
            return;
        }

        $user->stripe_id = $stripeCustomerId;

        // Only set these if not already set
        if (is_null($user->plan)) {
            $user->plan = 'pro';
        }

        if ($user->payment_status !== 'paid') {
            $user->payment_status = 'trial';
        }

        // Optional: fetch subscription and set trial end date early
        if (isset($session['subscription'])) {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $subscription = \Stripe\Subscription::retrieve($session['subscription']);

                if (isset($subscription->trial_end)) {
                    $trialEnd = \Carbon\Carbon::createFromTimestamp($subscription->trial_end);
                    $user->trial_ends_at = $trialEnd;
                    Log::info('Fetched trial_end from subscription in checkout handler', [
                        'user_id' => $user->id,
                        'trial_end' => $trialEnd,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to retrieve subscription from Stripe during checkout', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'subscription_id' => $session['subscription'] ?? null,
                ]);
            }
        }

        $user->save();

        Log::info('Saving user in checkout handler', [
            'user_id' => $user->id,
            'trial_ends_at' => $user->trial_ends_at,
        ]);
    }


    protected function handleInvoicePaid(array $invoice)
    {
        $customer = $invoice['customer'] ?? null;
        $amount = ($invoice['amount_paid'] ?? 0) / 100;

        if (!$customer || $amount < 1) return;

        $user = User::where('stripe_id', $customer)->first();

        if (!$user) return;

        // ✅ Mark user as paid
        $user->payment_status = 'paid';
        $user->save();

        // ⬇️ Continue with commission logic...
        if (!$user->referrer_id || $user->referrer_id === $user->id) return;
        $referrer = $user->referrer;

        if (!$referrer || $referrer->plan !== 'pro' || !$referrer->stripe_connect_id) {
            if ($referrer && !$referrer->stripe_connect_id && !$referrer->notified_onboarding_reminder_at?->isCurrentMonth()) {
                $referrer->notify(new OnboardingReminder);
                $referrer->notified_onboarding_reminder_at = now();
                $referrer->save();
            }
            return;
        }

        $interval = $invoice['lines']['data'][0]['price']['recurring']['interval'] ?? null;
        if (!in_array($interval, ['month', 'year'])) return;

        $alreadyRewarded = Commission::where('referrer_id', $referrer->id)
            ->where('referred_user_id', $user->id)
            ->where('interval', $interval)
            ->whereMonth('earned_at', now()->month)
            ->exists();

        if ($alreadyRewarded) return;

        $commissionAmount = $interval === 'month' ? 2.00 : 20.00;

        Commission::create([
            'referrer_id' => $referrer->id,
            'referred_user_id' => $user->id,
            'amount' => $commissionAmount,
            'interval' => $interval,
            'earned_at' => now(),
        ]);
    }

    public function handleAccountUpdated(array $payload)
    {
        $account = $payload['data']['object'];

        $user = User::where('stripe_connect_id', $account['id'])->first();

        if (!$user) return;

        $requirements = $account['requirements']['currently_due'] ?? [];

        if (!empty($requirements)) {
            // Store verification status
            $user->stripe_requires_verification = true;
            $user->save();

            // Send reminder
            if (!$user->notified_onboarding_reminder_at || now()->diffInHours($user->notified_onboarding_reminder_at) > 24) {
                $user->notify(new CompleteStripeVerification($account));
                $user->notified_onboarding_reminder_at = now();
                $user->save();
            }
        } else {
            $user->stripe_requires_verification = false;
            $user->save();
        }
    }
}
