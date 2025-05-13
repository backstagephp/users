<?php

namespace Backstage\Filament\Users\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionEventLog extends Model
{
    protected $fillable = [
        'event',
        'model_type',
        'model_id',
        'target_type',
        'target_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo(__FUNCTION__, 'target_type', 'target_id');
    }
}
