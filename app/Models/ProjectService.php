<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @mixin IdeHelperProjectService
 */
class ProjectService extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'project_services';

    protected $fillable = [
        'service_id',
        'project_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'service_id' => 'integer',
            'project_id' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }
}
