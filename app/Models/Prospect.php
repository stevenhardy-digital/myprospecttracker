<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'social_handle',
        'pain_points',
        'stage',
        'last_contacted',
        'next_follow_up',
    ];

    protected $casts = [
        'name' => 'encrypted',
        'phone' => 'encrypted',
        'social_handle' => 'encrypted',
        'pain_points' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
