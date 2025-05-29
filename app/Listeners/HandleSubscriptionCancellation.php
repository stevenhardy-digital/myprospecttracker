<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionCancelled;
use App\Models\User;

class HandleSubscriptionCancellation
{
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;

        // Only act on subscription deleted or failed payment
        if (
            $payload['type'] === 'customer.subscription.deleted' ||
            $payload['type'] === 'invoice.payment_failed'
        ) {
            $stripeId = $payload['data']['object']['customer'];

            $user = User::where('stripe_id', $stripeId)->first();

            if ($user && $user->plan === 'pro') {
                $user->plan = 'free';
                $user->save();
            }
            $user->notify(new \App\Notifications\DowngradedToFree);
        }
    }
}
