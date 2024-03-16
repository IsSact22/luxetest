<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @mixin IdeHelperService
 */
class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'estimate_time',
        'has_material',
        'disable',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'estimate_time' => 'string',
        'has_material' => 'boolean',
        'disable' => 'boolean',
    ];

    #[Override]
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            //
        });
    }
}
