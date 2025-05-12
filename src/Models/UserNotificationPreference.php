<?php

namespace Backstage\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Backstage\Users\Enums\NotificationType;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'navigation_type',
    ];

    protected $casts = [
        'navigation_type' => NotificationType::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('backstage.users.eloquent.users.model', User::class), 'user_id');
    }
}
