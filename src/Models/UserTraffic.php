<?php

namespace Backstage\Filament\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTraffic extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'path',
        'full_url',
        'ip',
        'user_agent',
        'referer',
        'route_name',
        'route_action',
        'route_parameters',
    ];

    protected $casts = [
        'route_parameters' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('backstage.users.eloquent.users.model', User::class), 'user_id');
    }
}
