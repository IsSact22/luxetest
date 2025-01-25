<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @mixin IdeHelperLaborRateValue
 */
class LaborRateValue extends Model
{
    use SoftDeletes;

    protected $table = 'labor_rate_values';

    protected $fillable = [
        'labor_rate_id',
        'valid_from',
        'valid_to',
        'amount',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($laborRateValue) {
            // Asigna la fecha actual en la zona horaria de Caracas
            $laborRateValue->valid_from = Carbon::now('America/Caracas')->toDateString();

            // Busca la tarifa anterior para la misma labor_rate_id
            $previousRate = LaborRateValue::where('labor_rate_id', $laborRateValue->labor_rate_id)
                ->whereNull('valid_to') // Asegúrate de que la tarifa anterior esté activa
                ->first();

            // Si se encuentra una tarifa anterior, asigna el valid_to
            if ($previousRate) {
                $previousRate->valid_to = $laborRateValue->valid_from; // Asigna la nueva valid_from como valid_to
                $previousRate->save(); // Guarda los cambios en la tarifa anterior
            }
        });
    }

    public function laborRate(): BelongsTo
    {
        return $this->belongsTo(LaborRate::class);
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'labor_rate_id' => 'integer',
            'valid_from' => 'datetime:Y-m-d',
            'valid_to' => 'datetime:Y-m-d',
            'amount' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
