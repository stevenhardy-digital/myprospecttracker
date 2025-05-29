<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribeMonthly(Request $request)
    {
        $user = $request->user();

        if (! $user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        $subscription = $user->subscription('default');

        if ($subscription) {
            if ($subscription->onGracePeriod()) {
                $subscription->resume();
            }

            if ($subscription->valid()) {
                $subscription->swap('price_1RTUImPdkhfPJgwW6LbbkTqW', [
                    'proration_behavior' => 'create_prorations',
                ]);

                return redirect()->route('dashboard')->with('success', 'You’ve switched to the monthly plan.');
            }

            // If status is incomplete or past_due, send them to checkout again
            if (in_array($subscription->stripe_status, ['incomplete', 'past_due'])) {
                return $user->newSubscription('default', 'price_1RTUImPdkhfPJgwW6LbbkTqW')->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('pricing'),
                    'client_reference_id' => $user->id,
                ]);
            }
        }

        // If no subscription at all
        return $user->newSubscription('default', 'price_1RTUImPdkhfPJgwW6LbbkTqW')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
            'client_reference_id' => $user->id,
        ]);
    }

    public function subscribeYearly(Request $request)
    {
        $user = $request->user();

        if (! $user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        $subscription = $user->subscription('default');

        if ($subscription) {
            if ($subscription->onGracePeriod()) {
                $subscription->resume();
            }

            if ($subscription->valid()) {
                $subscription->swap('price_1RU4v8PdkhfPJgwWLL80BsHU', [
                    'proration_behavior' => 'create_prorations',
                ]);

                return redirect()->route('dashboard')->with('success', 'You’ve switched to the yearly plan.');
            }

            if (in_array($subscription->stripe_status, ['incomplete', 'past_due'])) {
                return $user->newSubscription('default', 'price_1RU4v8PdkhfPJgwWLL80BsHU')->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('pricing'),
                    'client_reference_id' => $user->id,
                ]);
            }
        }

        return $user->newSubscription('default', 'price_1RU4v8PdkhfPJgwWLL80BsHU')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
            'client_reference_id' => $user->id,
        ]);
    }
}
