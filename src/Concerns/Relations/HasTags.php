<?php

namespace Backstage\Users\Concerns\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTags
{
    public function usersTags(): BelongsToMany
    {
        return $this->belongsToMany(\Backstage\Users\Models\UsersTag::class, 'users_tags_pivot', 'user_id', 'tag_id')
            ->withTimestamps();
    }
}
