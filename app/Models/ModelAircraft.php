<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperModelAircraft
 */
class ModelAircraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'model_aircrafts';

    protected $fillable = [
        'brand_aircraft_id',
        'engine_type_id',
        'name',
    ];

    public function brandAircraft(): BelongsTo
    {
        return $this->belongsTo(BrandAircraft::class, 'brand_aircraft_id');
    }

    public function engineType(): BelongsTo
    {
        return $this->belongsTo(EngineType::class, 'engine_type_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'brand_aircraft_id' => 'integer',
            'engine_type_id' => 'integer',
            'name' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
