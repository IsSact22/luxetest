<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
