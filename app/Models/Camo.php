<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperCamo
 */
class Camo extends Model
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'camos';

    protected $fillable = [
        'customer',
        'owner_id',
        'contract',
        'cam_id',
        'aircraft',
        'description',
        'start_date',
        'finish_date',
        'location',
    ];

    public function isCrewOfOwner(User $user): bool
    {
        return $user->isCrew() && $this->owner_id === $user->owner_id;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function cam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cam_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CamoActivity::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'customer' => 'string',
            'owner_id' => 'integer',
            'contract' => 'string',
            'cam_id' => 'integer',
            'aircraft' => 'string',
            'description' => 'string',
            'start_date' => 'datetime:Y-m-d',
            'finish_date' => 'datetime:Y-m-d',
            'location' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
