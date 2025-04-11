<?php

namespace Backstage\UserManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLogin extends Model
{
    use HasFactory;

    public function getTable()
    {
        return config('backstage.user-management.eloquent.user_logins.table', 'user_logins');
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
        return $this->belongsTo(config('backstage.user-management.eloquent.users.model', \App\Models\User::class), 'user_id');
    }
}
