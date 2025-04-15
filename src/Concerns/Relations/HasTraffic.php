<?php

namespace Backstage\UserManagement\Concerns\Relations;

trait HasTraffic
{
    /**
     * Get the traffic for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traffic()
    {
        return $this->hasMany(config('backstage.users.eloquent.user_traffic.model', \Backstage\UserManagement\Models\UserTraffic::class), 'user_id');
    }
}
