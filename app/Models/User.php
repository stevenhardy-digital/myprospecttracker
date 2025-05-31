<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'referrer_id',
        'name',
        'email',
        'username',
        'password',
        'last_login_at',
        'stripe_id',
        'stripe_connect_id',
        'notified_onboarding_reminder_at',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'role',
        'plan',
        'payment_status',
        'stripe_requires_verification',
        'billing_interval',
        'streak'
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'notified_onboarding_reminder_at' => 'datetime',
            'stripe_requires_verification' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPro()
    {
        return $this->plan === 'pro' && in_array($this->payment_status, ['trial', 'paid']);
    }

    public function inGracePeriod(): bool
    {
        $subscription = $this->subscription('default');

        return $subscription &&
            $subscription->ended() &&
            $subscription->grace_ends_at &&
            now()->lt($subscription->grace_ends_at);
    }

    public function gracePeriodEndsAt(): ?\Carbon\Carbon
    {
        $subscription = $this->subscription('default');

        return $subscription?->grace_ends_at;
    }

    public function daysLeftInGrace(): int
    {
        $end = $this->gracePeriodEndsAt();

        return $end ? now()->diffInDays($end, false) : 0;
    }

    public function hasProAccess(): bool
    {
        return $this->plan === 'pro' || $this->inGracePeriod();
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'referrer_id');
    }

    public function nextExpectedCommission()
    {
        return $this->commissions()
            ->where('paid', false)
            ->whereDate('earned_at', '>', now()->subDays(7))
            ->orderBy('earned_at')
            ->first();
    }

    /**
     * Check if the user is currently on a Cashier trial.
     */
    public function onTrial(): bool
    {
        $subscription = $this->subscription('default');
        return $subscription && $subscription->onTrial();
    }

    /**
     * Return the trial_ends_at (Carbon) if the subscription is on trial, or null otherwise.
     */
    public function trialEndsAt(): ?Carbon
    {
        $subscription = $this->subscription('default');

        return ($subscription && $subscription->onTrial())
            ? $subscription->trial_ends_at
            : null;
    }

    /**
     * How many whole days are left in the current trial? 0 if none.
     */
    public function daysLeftInTrial(): int
    {
        $endsAt = $this->trialEndsAt();

        return $endsAt
            ? now()->diffInDays($endsAt, false)
            : 0;
    }

    /**
     * Check if the user’s subscription is active (i.e. paid) and not on trial.
     */
    public function isSubscribedAndActive(): bool
    {
        $subscription = $this->subscription('default');
        return $subscription && $subscription->valid() && ! $subscription->onTrial();
    }

    public function stripeOnboardingLink(): string
    {
        $accountLink = \Stripe\AccountLink::create([
            'account' => $this->stripe_connect_id,
            'refresh_url' => route('stripe.onboarding.retry'),
            'return_url' => route('dashboard'),
            'type' => 'account_onboarding',
        ]);

        return $accountLink->url;
    }
    public function prospects()
    {
        return $this->hasMany(Prospect::class);
    }



}
