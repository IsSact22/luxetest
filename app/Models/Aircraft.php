<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperAircraft
 */
class Aircraft extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'aircrafts';

    protected $fillable = [
        'model_aircraft_id',
        'register',
        'serial',
    ];

    public function modelAircraft(): BelongsTo
    {
        return $this->belongsTo(ModelAircraft::class, 'model_aircraft_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'model_aircraft_id' => 'integer',
            'register' => 'string',
            'serial' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
