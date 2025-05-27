<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;
use App\Listeners\HandleSubscriptionCancellation;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WebhookReceived::class => [
            HandleSubscriptionCancellation::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
