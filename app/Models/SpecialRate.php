<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperSpecialRate
 */
class SpecialRate extends Model
{
    use SoftDeletes;

    protected $table = 'special_rates';

    protected $fillable = [
        'camo_activity_id',
        'name',
        'description',
        'amount',
        'is_used',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(CamoActivity::class, 'camo_activity_id');
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'camo_activity_id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'amount' => 'decimal:2',
            'is_used' => 'boolean',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
