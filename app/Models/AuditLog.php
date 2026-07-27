<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['action', 'target_type', 'target_user_id', 'admin_id', 'reason'])]
class AuditLog extends Model
{
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function record(
        string $action,
        string $targetType,
        int $targetUserId,
        int $adminId,
        ?string $reason = null,
    ): self {
        return self::create([
            'action' => $action,
            'target_type' => $targetType,
            'target_user_id' => $targetUserId,
            'admin_id' => $adminId,
            'reason' => $reason,
        ]);
    }
}
