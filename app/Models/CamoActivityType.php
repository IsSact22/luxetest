<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperCamoActivityType
 */
class CamoActivityType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'camo_activity_types';

    protected $fillable = [
        'camo_activity_rate_id',
        'name',
        'description',
    ];

    public function activityRate(): BelongsTo
    {
        return $this->belongsTo(CamoActivityRate::class, 'camo_activity_rate_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'camo_activity_rate_id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
