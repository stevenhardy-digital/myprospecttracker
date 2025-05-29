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
}
