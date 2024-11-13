<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperLaborRate
 */
class LaborRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'labor_rates';

    protected $fillable = [
        'rateable_id',
        'rateable_type',
        'engine_type_id',
        'code',
        'name',
    ];

    protected $appends = ['amount'];

    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'engine_type_id' => 'integer',
            'code' => 'string',
            'name' => 'string',
            'amount' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => round($this->values()->latest()->value('amount') ?? 0, 2)
        );
    }

    public function values(): HasMany
    {
        return $this->hasMany(LaborRateValue::class);
    }
}
