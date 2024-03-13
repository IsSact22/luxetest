<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $team_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereUserId($value)
 *
 * @mixin \Eloquent
 */
class TeamUser extends Pivot
{
    protected $table = 'team_user';

    protected $fillable = [
        'team_id',
        'user_id',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
    ];
}
