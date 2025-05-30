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
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan' => ['required', 'in:monthly,yearly'],
            'referrer' => ['nullable', 'string'],
        ]);

        // Save to session (we'll use this after Stripe Checkout)
        session([
            'registration_payload' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'plan_choice' => $validated['plan'],
                'referrer_username' => $validated['referrer'] ?? null,
            ]
        ]);

        // Create temporary customer
        Stripe::setApiKey(config('services.stripe.secret'));

        $priceId = $validated['plan'] === 'yearly'
            ? 'price_1RU4v8PdkhfPJgwWLL80BsHU'
            : 'price_1RTUImPdkhfPJgwW6LbbkTqW';

        $checkoutSession = CheckoutSession::create([
            'client_reference_id' => Str::uuid(), // reference for user creation
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

        // Save session ID
        session(['stripe_checkout_id' => $checkoutSession->id]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
        Log::debug('Success route hit', [
            'session_id' => $request->get('session_id'),
            'registration_payload' => session('registration_payload'),
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->get('session_id');
        $session = CheckoutSession::retrieve($sessionId);

        $customerId = $session->customer;
        $clientRef = $session->client_reference_id;

        $payload = session('registration_payload');

        if (!$payload || !$sessionId) {
            return redirect()->route('register')->withErrors('Missing session.');
        }

        // Ensure user does not already exist
        if (User::where('email', $payload['email'])->exists()) {
            return redirect()->route('login')->with('status', 'You already have an account.');
        }

        // Generate unique username
        $username = Str::slug($payload['name']);
        if (User::where('username', $username)->exists()) {
            $username .= '-' . Str::random(4);
        }

        // Find referrer if exists
        $referrerId = null;
        if ($payload['referrer_username']) {
            $referrer = User::where('username', $payload['referrer_username'])->first();
            if ($referrer) $referrerId = $referrer->id;
        }

        // Create the user now
        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'username' => $username,
            'stripe_id' => $customerId,
            'plan' => 'pro',
            'billing_interval' => $payload['plan_choice'],
            'referrer_id' => $referrerId,
            'payment_status' => 'trial',
        ]);

        Auth::login($user);
        session()->forget(['registration_payload', 'stripe_checkout_id']);

        return redirect()->route('dashboard')->with('success', 'Welcome!');
    }
}
