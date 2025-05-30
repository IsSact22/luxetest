<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperEngineType
 */
class EngineType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'engine_types';

    protected $fillable = [
        'name',
    ];

    public function rateable(): MorphMany
    {
        return $this->morphMany(LaborRate::class, 'rateable');
    }

    public function modelAircraft(): HasMany
    {
        return $this->hasMany(ModelAircraft::class, 'engine_type_id', 'id');
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
