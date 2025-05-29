<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'name',
        'email',
        'password',
    ];

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
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPro()
    {
        return $this->plan === 'pro';
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


}
