<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Override;

/**
 * @mixin IdeHelperProjectTask
 */
class ProjectTask extends Model
{
    use HasFactory;

    protected $table = 'project_tasks';

    const BASE_NUM = 60000;

    protected $fillable = [
        'project_service_id',
        'name',
        'description',
        'status',
        'due_date',
        'position',
    ];

    public static function booted(): void
    {
        static::creating(function ($model) {
            $model->position = self::query()->orderByDesc('position')->first()?->position + self::BASE_NUM;
        });
    }

    public function projectService(): BelongsTo
    {
        return $this->belongsTo(ProjectService::class);
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class,
            ProjectService::class,
            'id',
            'id',
            'project_service_id',
            'project_id'
        );
    }

    public function service(): HasOneThrough
    {
        return $this->hasOneThrough(
            Service::class,
            ProjectService::class,
            'id',
            'id',
            'project_service_id',
            'service_id'
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'project_service_id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'status' => 'string',
            'due_date' => 'datetime:Y-m-d',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
