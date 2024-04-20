<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class CamoRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'camo_rates';

    protected $fillable = [
        'engine_type_id',
        'code',
        'name',
        'mount',
    ];

    public function engineType(): BelongsTo
    {
        return $this->belongsTo(EngineType::class, 'engine_type_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'engine_type_id' => 'integer',
            'code' => 'string',
            'name' => 'string',
            'mount' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
