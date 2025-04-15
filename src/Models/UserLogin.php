<?php

namespace Backstage\UserManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    public function getTable()
    {
        return config('backstage.user.eloquent.user_logins.table', 'user_logins');
    }

    protected $fillable = [
        'user_id',
        'type',
        'url',
        'referrer',
        'inputs',
        'user_agent',
        'ip_address',
        'hostname',
        'isp',
        'org',
        'city',
        'region',
        'country_code',
    ];

    public function user()
    {
        return $this->belongsTo(config('backstage.user.eloquent.users.model', User::class), 'user_id');
    }
}
