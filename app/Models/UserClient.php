<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperUserClient
 */
class UserClient extends Pivot
{
    protected $table = 'user_client';

    protected $fillable = [
        'user_id',
        'client_id',
    ];
}
