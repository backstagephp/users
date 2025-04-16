<?php

namespace Backstage\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UsersTag extends Model
{
    protected $table = 'users_tags';

    protected $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'users_tags_pivot', 'tag_id', 'user_id')
            ->withTimestamps();
    }
}
