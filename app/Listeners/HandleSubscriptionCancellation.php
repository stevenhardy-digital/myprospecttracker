<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;
use App\Notifications\DowngradedToFree;

class HandleSubscriptionCancellation
{
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;

        $type = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? [];

        if (!in_array($type, ['customer.subscription.deleted', 'invoice.payment_failed'])) {
            return;
        }

        $stripeId = $data['customer'] ?? null;

        if (!$stripeId) {
            return;
        }

        $user = User::where('stripe_id', $stripeId)->first();

        if ($user && $user->plan === 'pro') {
            $user->plan = 'free';
            $user->save();

            $user->notify(new DowngradedToFree);
        }
    }
}
