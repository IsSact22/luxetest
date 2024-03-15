<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model
{
    use HasFactory;
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
}
