<?php

namespace Backstage\UserManagement\Concerns\Conditionals;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Backstage\UserManagement\Models\UserLogin[] $logins
 * @property-read int|null $logins_count
 *
 * @mixin \Backstage\UserManagement\Models\User
 */
trait UserIsVerified
{
   public function isVerified(): bool
   {
      return $this->email_verified_at !== null;
   }

   public function isNotVerified(): bool
   {
      return !$this->isVerified();
   }
}
