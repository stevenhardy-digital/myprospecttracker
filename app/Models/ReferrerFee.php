<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferrerFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'month',
        'amount',
    ];

    /**
     * A referrer fee belongs to a user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
