<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    //
    protected $fillable = [
        'user_id',
        'device_name',
        'device_os',
        'os_version',
        'app_version',
        'ip_address',
        'last_login_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
