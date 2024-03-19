<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @mixin IdeHelperCamoActivity
 */
class CamoActivity extends Model
{
    use HasFactory;
    use SoftDeletes;

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
