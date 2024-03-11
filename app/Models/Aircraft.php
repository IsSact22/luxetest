<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aircraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aircraft';

    protected $fillable = [
        'owner_id',
        'aircraft_model_id',
        'construction_date',
        'flight_hours',
    ];

    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'aircraft_model_id' => 'integer',
        'construction_date' => 'datetime:Y-m-d',
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
