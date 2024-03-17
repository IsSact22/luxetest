<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @mixin IdeHelperProjectTask
 */
class ProjectTask extends Model
{
    use HasFactory;

    protected $table = 'project_tasks';

    protected $fillable = [
        'project_service_id',
        'name',
        'description',
        'status',
        'due_date',
    ];

    public function projectService(): BelongsTo
    {
        return $this->belongsTo(ProjectService::class);
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
