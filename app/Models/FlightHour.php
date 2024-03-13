<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $aircraft_id
 * @property \Illuminate\Support\Carbon $flight_date
 * @property string $flight_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aircraft $aircraft
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereFlightDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereFlightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FlightHour extends Model
{
    use HasFactory;

    protected $table = 'flight_hours';

    protected $fillable = [
        'aircraft_id',
        'flight_date',
        'flight_hours',
    ];

    protected $casts = [
        'id' => 'integer',
        'aircraft_id' => 'integer',
        'flight_date' => 'datetime:Y-m-d H:i',
        'flight_hours' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function aircraft(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Aircraft::class, 'aircraft_id');
    }
}
