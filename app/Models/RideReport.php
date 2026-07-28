<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'ride_id', 'student_user_id', 'driver_user_id', 'pickup_label', 'destination_label',
    'fare', 'status', 'notes', 'cancel_reason', 'ride_created_at',
])]
class RideReport extends Model
{
    protected function casts(): array
    {
        return [
            'fare' => 'decimal:2',
            'ride_created_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
}
