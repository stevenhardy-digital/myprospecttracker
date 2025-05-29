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

        return $user->newSubscription('default', 'price_monthly_id_here')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
        ]);
    }

    public function subscribeYearly(Request $request)
    {
        $user = $request->user();

        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        return $user->newSubscription('default', 'price_yearly_id_here')->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('pricing'),
        ]);
    }
}
