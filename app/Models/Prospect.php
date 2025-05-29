<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'notes',
        'status',
        'stage',
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
