<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $owner_id
 * @property int $aircraft_model_id
 * @property \Illuminate\Support\Carbon $construction_date
 * @property string $serial
 * @property string $registration
 * @property string|null $flight_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AircraftModel $aircraftModel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlightHour> $flightHours
 * @property-read int|null $flight_hours_count
 * @property-read \App\Models\User $owner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereAircraftModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereConstructionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereFlightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Aircraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aircraft';

    protected $fillable = [
        'owner_id',
        'aircraft_model_id',
        'construction_date',
        'serial',
        'registration',
        'flight_hours',
    ];

    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'aircraft_model_id' => 'integer',
        'construction_date' => 'datetime:Y-m-d',
        'serial' => 'string',
        'registration' => 'string',
        'flight_hours' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function aircraftModel(): BelongsTo
    {
        return $this->belongsTo(AircraftModel::class, 'aircraft_model_id');
    }

    public function flightHours(): HasMany
    {
        return $this->hasMany(FlightHour::class, 'aircraft_id', 'id');
    }
}
