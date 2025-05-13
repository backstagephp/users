<?php

namespace Backstage\Filament\Users\Concerns\Conditionals;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Backstage\Filament\Users\Models\UserLogin[] $logins
 * @property-read int|null $logins_count
 *
 * @mixin \Backstage\Filament\Users\Models\User
 */
trait UserIsVerified
{
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null
            && $this->password !== null;
    }

    public function isNotVerified(): bool
    {
        return ! $this->isVerified();
    }
}
