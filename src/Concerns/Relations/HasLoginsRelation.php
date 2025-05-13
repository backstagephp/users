<?php

namespace Backstage\Filament\Users\Concerns\Relations;

trait HasLoginsRelation
{
    /**
     * Get the logins for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logins()
    {
        return $this->hasMany(config('backstage.users.eloquent.user_logins.model', \Backstage\Filament\Users\Models\UserLogin::class), 'user_id');
    }
}
