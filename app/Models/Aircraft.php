<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperAircraft
 */
class Aircraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aircraft';

    protected $fillable = [
        'registration',
        'name',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'registration' => 'string',
            'name' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
