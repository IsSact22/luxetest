<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaborRateValue extends Model
{
    use SoftDeletes;

    protected $table = 'labor_rate_values';

    protected $fillable = [
        'labor_rate_id',
        'date',
        'amount',
    ];

    public function laborRate(): BelongsTo
    {
        return $this->belongsTo(LaborRate::class);
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'labor_rate_id' => 'integer',
            'date' => 'datetime:Y-m-d',
            'amount' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
