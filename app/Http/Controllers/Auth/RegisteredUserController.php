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
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\Subscription;

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan' => ['required', 'in:monthly,yearly'],
        ]);

        // Handle referral logic
        if (session()->has('referrer')) {
            $referrer = User::where('username', session('referrer'))->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => Str::slug($request->name),
            'password' => Hash::make($request->password),
            'plan' => $request->plan,
            'referrer_id' => $referrerId,
        ]);

        if (isset($referrer)) {
            $referrer->notify(new ReferredUserSignedUp($user));
        }

        event(new Registered($user));
        Auth::login($user);
        session()->forget('referrer');

        // Stripe setup
        $user->createAsStripeCustomer(); // creates stripe_id if not present
        Stripe::setApiKey(config('services.stripe.secret'));

        // Price ID lookup
        $priceId = $request->plan === 'yearly' ? 'price_1RU4v8PdkhfPJgwWLL80BsHU' : 'price_1RTUImPdkhfPJgwW6LbbkTqW';

        // Optional billing anchor (e.g., next 1st of month)
        $trialEnd = now()->addDays(14)->timestamp;
        $billingAnchor = now()->addMonth()->startOfMonth()->timestamp;

        // Create subscription manually via API
        Subscription::create([
            'customer' => $user->stripe_id,
            'items' => [['price' => $priceId]],
            'trial_end' => $trialEnd,
            'billing_cycle_anchor' => $billingAnchor,
            'proration_behavior' => 'create_prorations',
        ]);

        $user->update([
            'payment_status' => 'trial',
        ]);

        return redirect()->route('dashboard');
    }
}
