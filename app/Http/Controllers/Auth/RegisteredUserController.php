<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ReferredUserSignedUp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan' => ['required', 'in:monthly,yearly'],
            'referrer' => ['nullable', 'string'],
        ]);

        // Generate unique username
        $username = Str::slug($validated['name']);
        if (User::where('username', $username)->exists()) {
            $username .= '-' . Str::random(4);
        }

        // Find referrer
        $referrerId = null;
        if (!empty($validated['referrer'])) {
            $referrer = User::where('username', $validated['referrer'])->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        // Create user immediately
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'username' => $username,
            'plan' => 'pro',
            'billing_interval' => $validated['plan'],
            'referrer_id' => $referrerId,
            'payment_status' => 'incomplete',
        ]);

        event(new Registered($user));
        Auth::login($user); // Log them in immediately if needed

        // Stripe setup
        Stripe::setApiKey(config('services.stripe.secret'));
        $user->createAsStripeCustomer();

        $priceId = $validated['plan'] === 'yearly'
            ? 'price_1RU4v8PdkhfPJgwWLL80BsHU'
            : 'price_1RTUImPdkhfPJgwW6LbbkTqW';

        $checkoutSession = CheckoutSession::create([
            'customer' => $user->stripe_id,
            'client_reference_id' => $user->id,
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'subscription_data' => [
                'trial_period_days' => 14,
            ],
            'success_url' => route('register.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('register'),
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request): RedirectResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->get('session_id');
        $session = CheckoutSession::retrieve($sessionId);

        $userId = $session->client_reference_id ?? null;

        if (!$userId || !$session->customer) {
            return redirect()->route('register')->withErrors('Missing Stripe session or user.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')->withErrors('User not found.');
        }

        $user->stripe_id = $session->customer;
        $user->payment_status = 'trial';
        $user->trial_ends_at = now()->addDays(14);
        $user->save();

        Log::info('User updated with trial end', ['user_id' => $user->id, 'trial_ends_at' => $user->trial_ends_at]);

        return redirect()->route('dashboard')->with('success', 'Welcome! Your 14-day trial has started.');
    }
}
