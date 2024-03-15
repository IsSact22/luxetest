<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperClientProfile
 */
class ClientProfile extends Model
{
    use HasFactory;

    protected $table = 'client_profiles';

    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'customer_name' => 'string',
        'phone' => 'string',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];
}
