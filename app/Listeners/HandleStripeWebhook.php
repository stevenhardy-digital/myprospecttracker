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

        if (!isset($object['customer'])) {
            return;
        }

        $user = User::where('stripe_id', $object['customer'])->first();

        if (!$user) {
            Log::warning("Stripe webhook received but user not found: {$object['customer']}");
            return;
        }

        match ($type) {
            'customer.subscription.created',
            'customer.subscription.updated' => $this->handleSubscriptionCreatedOrUpdated($user, $object),

            'customer.subscription.deleted',
            'invoice.payment_failed' => $this->handleSubscriptionCancelled($user),
            'checkout.session.completed' => $this->handleCheckoutCompleted($object),

            default => null,
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
        if ($user->plan !== 'free') {
            $user->plan = 'free';
            $user->save();

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
