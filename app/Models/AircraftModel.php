<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAircraftModel
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
