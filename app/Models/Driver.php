<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'full_name', 'first_name', 'last_name', 'status', 'verified', 'suspended',
    'phone', 'address', 'license_no', 'license_expiry_date', 'psv_insurance_expiry_date',
    'national_id_number', 'vehicle_make_model', 'vehicle_reg_no',
    'emergency_contact_name', 'emergency_contact_phone',
    'driver_license_url', 'psv_insurance_sticker_url', 'national_id_url', 'passport_photo_url',
    'has_good_conduct_certificate', 'notes', 'rejection_reason',
    'verified_at', 'verified_by', 'rejected_at', 'rejected_by',
    'suspended_at', 'suspended_by', 'suspended_reason', 'unsuspended_at', 'unsuspended_by',
])]
class Driver extends Model
{
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'suspended' => 'boolean',
            'has_good_conduct_certificate' => 'boolean',
            'license_expiry_date' => 'date',
            'psv_insurance_expiry_date' => 'date',
            'verified_at' => 'datetime',
            'rejected_at' => 'datetime',
            'suspended_at' => 'datetime',
            'unsuspended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
