<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperBrandAircraft
 */
class BrandAircraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'brand_aircrafts';

    protected $fillable = [
        'name',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::creating(static function ($model) {
            $model->name = strtoupper($model->name);
        });
        static::updating(static function ($model) {
            $model->name = strtoupper($model->name);
        });
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }

}
