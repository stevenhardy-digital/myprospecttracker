<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;
use App\Notifications\DowngradedToFree;
use Illuminate\Support\Facades\Log;

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

            'checkout.session.completed',
            'invoice.payment_succeeded',
            'invoice.paid' => tap(
                $this->handleCheckoutCompleted($object),
                fn () => Log::info("Handled checkout/invoice success", ['stripe_customer_id' => $object['customer'] ?? null])
            ),

            default => Log::info("No handler defined for this webhook type", ['type' => $type]),
        };
    }

    protected function handleSubscriptionCreatedOrUpdated(User $user, array $subscription)
    {
        $status = $subscription['status'] ?? 'incomplete';

        if ($status === 'active' || $status === 'trialing') {
            $user->plan = 'pro';
            $user->trial_ends_at = isset($subscription['trial_end']) ? now()->setTimestamp($subscription['trial_end']) : null;
            $user->save();
        }
    }

    protected function handleSubscriptionCancelled(User $user)
    {
        $subscription = $user->subscription('default');

        if ($subscription && !$subscription->cancelled()) {
            $subscription->markAsCancelled();
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

        $user = \App\Models\User::find($clientReferenceId); // client_reference_id must be the user's ID

        if ($user) {
            $user->stripe_id = $stripeCustomerId;
            $user->plan = 'pro';
            $user->save();
        }
    }
}
