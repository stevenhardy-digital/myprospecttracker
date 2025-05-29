<?php

namespace App\Models;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    protected $casts = [
        'grace_ends_at' => 'datetime',
    ];
}
