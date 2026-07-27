<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'full_name', 'reg_number', 'course', 'year', 'phone', 'photo_url',
    'accessibility_wheelchair', 'accessibility_visual', 'accessibility_hearing',
    'accessibility_assistance', 'emergency_contact_name', 'emergency_contact_phone',
    'suspended', 'suspended_at', 'suspended_by', 'suspended_reason',
    'unsuspended_at', 'unsuspended_by',
])]
class Student extends Model
{
    protected function casts(): array
    {
        return [
            'suspended' => 'boolean',
            'accessibility_wheelchair' => 'boolean',
            'accessibility_visual' => 'boolean',
            'accessibility_hearing' => 'boolean',
            'accessibility_assistance' => 'boolean',
            'suspended_at' => 'datetime',
            'unsuspended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
