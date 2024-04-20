<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class CamoActivityRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'camo_activity_rates';

    protected $fillable = [
        'code',
        'name',
        'mount',
    ];

    public function camoActivityType(): HasMany
    {
        return $this->hasMany(CamoActivityType::class, 'camo_activity_rate_id', 'id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'code' => 'string',
            'name' => 'string',
            'mount' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
