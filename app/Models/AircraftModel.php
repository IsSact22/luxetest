<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $manufacturer_id
 * @property string $name
 * @property string $category
 * @property string $class
 * @property int $motor_type
 * @property int $motor_qty
 * @property int $passenger_qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Manufacturer $manufacturer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereMotorQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereMotorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel wherePassengerQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AircraftModel extends Model
{
    use HasFactory;

    protected $table = 'aircraft_models';

    protected $fillable = [
        'manufacturer_id',
        'name',
        'category',
        'class',
        'motor_type',
        'motor_qty',
        'passenger_qty',
    ];

    protected $casts = [
        'id' => 'integer',
        'manufacturer_id' => 'integer',
        'name' => 'string',
        'category' => 'string',
        'class' => 'string',
        'motor_type' => 'integer',
        'motor_qty' => 'integer',
        'passenger_qty' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }
}
