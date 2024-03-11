<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = 'manufacturers';

    protected $fillable = [
        'acronym',
        'name',
    ];

    protected $casts = [
        'id' => 'integer',
        'acronym' => 'string',
        'name' => 'string',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function aircraftModels(): HasMany
    {
        return $this->hasMany(AircraftModel::class, 'manufacturer_id', 'id');
    }
}
