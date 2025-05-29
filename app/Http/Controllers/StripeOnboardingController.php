<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeOnboardingController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        if (!$user->stripe_connect_id) {
            Stripe::setApiKey(config('services.stripe.secret'));

            $account = Account::create([
                'type' => 'express',
                'country' => 'GB',
                'email' => $user->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
            ]);

            $user->stripe_connect_id = $account->id;
            $user->save();
        }

        $accountLink = AccountLink::create([
            'account' => $user->stripe_connect_id,
            'refresh_url' => route('stripe.refresh'),
            'return_url' => route('dashboard'),
            'type' => 'account_onboarding',
        ]);

        return Redirect::to($accountLink->url);
    }

    public function refresh()
    {
        return redirect()->route('stripe.connect');
    }
}
