<?php

namespace App\Models;

use App\ActivityStatus;
use App\ApprovalStatus;
use App\Helpers\HasRateValue;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'labor_rate_value_id',
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

    protected $appends = ['images', 'get_special_rate', 'missing_rate_value', 'missing_rate_name'];

    public function camo(): BelongsTo
    {
        return $this->belongsTo(Camo::class, 'camo_id');
    }

    public function laborRate(): BelongsTo
    {
        return $this->belongsTo(LaborRate::class, 'labor_rate_id')->with('amount');
    }

    public function laborRateValue(): BelongsTo
    {
        return $this->belongsTo(LaborRateValue::class, 'labor_rate_value_id');
    }

    #[Override]
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 150, 150)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections($this->mediaCollectionName);

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Contain, 400, 300)
            ->sharpen(10)
            ->nonQueued()
            ->performOnCollections($this->mediaCollectionName);
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

    protected function getSpecialRate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->specialRate()->latest()->first()
        );
    }

    public function specialRate(): HasOne
    {
        return $this->hasOne(SpecialRate::class, 'camo_activity_id');
    }

    protected function missingRateValue(): Attribute
    {
        return Attribute::make(
            get: fn () => HasRateValue::hasRate($this->labor_rate_id)
        );
    }

    protected function missingRateName(): Attribute
    {
        return Attribute::make(
            get: fn () => HasRateValue::getRate($this->labor_rate_id)
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'camo_id' => 'integer',
            'labor_rate_id' => 'integer',
            'labor_rate_value_id',
            'required' => 'boolean',
            'date' => 'datetime:Y-m-d',
            'name' => 'string',
            'description' => 'string',
            'estimate_time' => 'decimal:2',
            'started_at' => 'datetime:Y-m-d H:i',
            'completed_at' => 'datetime:Y-m-d H:i',
            'status' => ActivityStatus::class,
            'comments' => 'string',
            'labor_mount' => 'decimal:2',
            'material_mount' => 'decimal:2',
            'material_information' => 'string',
            'awr' => 'string',
            'approval_status' => ApprovalStatus::class,
            'priority' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
