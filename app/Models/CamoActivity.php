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
        'required',
        'date',
        'name',
        'description',
        'status',
        'comments',
        'labor_mount',
        'material_mount',
        'material_information',
        'awr',
        'approval_status',
    ];

    protected $appends = ['images'];

    public function camo(): BelongsTo
    {
        return $this->belongsTo(Camo::class, 'camo_id');
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

    public function getImagesAttribute(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        return $this->getMedia($this->mediaCollectionName);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'camo_id' => 'integer',
            'required' => 'boolean',
            'date' => 'datetime:Y-m-d',
            'name' => 'string',
            'description' => 'string',
            'status' => 'string',
            'comments' => 'string',
            'labor_mount' => 'decimal:2',
            'material_mount' => 'decimal:2',
            'material_information' => 'string',
            'awr' => 'string',
            'approval_status' => 'string',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
