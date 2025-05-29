<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'social_handle',
        'pain_points',
        'status',
        'last_contacted',
        'next_follow_up',
    ];

    protected $casts = [
        'name' => 'encrypted',
        'email' => 'encrypted',
        'phone' => 'encrypted',
        'notes' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
