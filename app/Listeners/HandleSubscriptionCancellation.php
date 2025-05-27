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
        // Optional: Extract customer ID or user
        $payload = $event->payload;
        $customerId = $payload['data']['object']['customer'] ?? null;

        // Example: Find the user via Stripe customer ID
        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            // Notify the user (or yourself/admin)
            $user->notify(new SubscriptionCancelled());
        }

        // OR notify an admin:
        // Notification::route('mail', 'admin@example.com')->notify(new SubscriptionCancelled());
    }
}
