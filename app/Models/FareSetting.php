<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['minimum_fare', 'base_fare', 'per_km_rate'])]
class FareSetting extends Model
{
    protected function casts(): array
    {
        return [
            'minimum_fare' => 'decimal:2',
            'base_fare' => 'decimal:2',
            'per_km_rate' => 'decimal:2',
        ];
    }
}
