<?php

namespace Backstage\UserManagement\Concerns\Scopes;

use Illuminate\Database\Eloquent\Builder;


trait IsVerfiedScope
{
    /**
     * Scope a query to only include verified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query)
    {
        return $query->where('email_verified_at', '!=', null)
            ->where('password', '!=', null);
    }

    /**
     * Scope a query to only include unverified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverified(Builder $query)
    {
        return  $query->where('email_verified_at', null)
            ->orWhere('password', null);
    }
}
