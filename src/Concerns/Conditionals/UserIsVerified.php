<?php

namespace Backstage\Users\Concerns\Conditionals;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Backstage\Users\Models\UserLogin[] $logins
 * @property-read int|null $logins_count
 *
 * @mixin \Backstage\Users\Models\User
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
