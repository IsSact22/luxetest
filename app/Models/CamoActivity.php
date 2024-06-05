<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin IdeHelperCamoActivity
 */
class CamoActivity extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public string $mediaCollectionName = 'activity_images';

    protected $table = 'camo_activities';

    protected $fillable = [
        'camo_id',
        'labor_rate_id',
        'required',
        'date',
        'name',
        'description',
        'estimate_time',
        'started_at',
        'completed_at',
        'status',
        'comments',
        'labor_mount',
        'material_mount',
        'material_information',
        'awr',
        'approval_status',
        'priority',
    ];

    protected $appends = ['images'];

    public function camo(): BelongsTo
    {
        return $this->belongsTo(Camo::class, 'camo_id');
    }

    public function laborRate(): BelongsTo
    {
        return $this->belongsTo(LaborRate::class, 'labor_rate_id');
    }

    #[Override]
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 120, 120)
            ->nonQueued();
    }

    #[Override]
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->mediaCollectionName);
    }

    public function getImagesAttribute(): MediaCollection
    {
        return $this->getMedia($this->mediaCollectionName);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'camo_id' => 'integer',
            'labor_rate_id' => 'integer',
            'required' => 'boolean',
            'date' => 'datetime:Y-m-d',
            'name' => 'string',
            'description' => 'string',
            'estimate_time' => 'decimal:2',
            'started_at' => 'datetime:Y-m-d H:i',
            'completed_at' => 'datetime:Y-m-d H:i',
            'status' => 'string',
            'comments' => 'string',
            'labor_mount' => 'decimal:2',
            'material_mount' => 'decimal:2',
            'material_information' => 'string',
            'awr' => 'string',
            'approval_status' => 'string',
            'priority' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
