<?php

namespace Backstage\Users\Concerns\Relations;

use Backstage\Users\Models\UserNotificationPreference;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasNotificationPreferences
{
    public function notificationPreferences(): HasMany
    {
        return $this->hasMany(UserNotificationPreference::class, 'user_id');
    }
}
