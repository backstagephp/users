<?php

namespace Backstage\Users\Concerns\Relations;

trait HasTraffic
{
    /**
     * Get the traffic for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traffic()
    {
        return $this->hasMany(config('backstage.users.eloquent.user_traffic.model', \Backstage\Users\Models\UserTraffic::class), 'user_id');
    }
}
