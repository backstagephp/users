<?php

namespace Backstage\UserManagement\Concerns\Scopes;

use Filament\Forms\Components\Builder;

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
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include unverified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverified(Builder $query)
    {
        return $query->where('is_verified', false);
    }
}
