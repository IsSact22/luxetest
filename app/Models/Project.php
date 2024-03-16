<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'client_id',
        'contract',
        'aircraft',
        'description',
        'start_date',
        'end_estimated',
        'finish_date',
        'project_manager',
    ];

    #[Override]
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
