<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'ride_id', 'driver_user_id', 'student_user_id', 'student_firebase_uid',
    'duration_seconds', 'fare', 'pickup', 'destination', 'pickup_label', 'destination_label',
    'student_rating_stars', 'student_rating_comment', 'student_rated_at',
    'driver_rating_stars', 'driver_rating_comment', 'driver_rated_at',
])]
class Trip extends Model
{
    protected function casts(): array
    {
        return [
            'fare' => 'decimal:2',
            'pickup' => 'array',
            'destination' => 'array',
            'student_rated_at' => 'datetime',
            'driver_rated_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}
