<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribeMonthly(Request $request)
    {
        $user = $request->user();

        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        return $user->newSubscription('default', 'price_1RTUImPdkhfPJgwW6LbbkTqW')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
            'client_reference_id' => $user->id,
        ]);
    }

    public function subscribeYearly(Request $request)
    {
        $user = $request->user();

        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        return $user->newSubscription('default', 'price_1RU4v8PdkhfPJgwWLL80BsHU')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
            'client_reference_id' => $user->id,
        ]);
    }
}
