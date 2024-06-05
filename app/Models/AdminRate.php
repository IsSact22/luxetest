<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'admin_rates';

    protected $fillable = [
        'name',
        'description',
    ];

    public function rateable(): MorphTo
    {
        return $this->morphTo(LaborRate::class, 'rateable');
    }
}
