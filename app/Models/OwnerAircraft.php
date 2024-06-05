<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OwnerAircraft extends Pivot
{
    public $touches = [
        'owner',
        'aircraft',
    ];

    protected $table = 'owner_aircraft';

    protected $fillable = [
        'owner_id',
        'aircraft_id',
    ];

    public function ownerAircraft(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function aircraftOwner(): BelongsTo
    {
        return $this->belongsTo(Aircraft::class, 'aircraft_id', 'id');
    }
}
