<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements HasMedia, JWTSubject
{
    use HasFactory;
    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'owner_id',
    ];

    protected $guarded = ['disabled'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'owner_id' => 'integer',
        'disabled' => 'boolean',
    ];

    protected $appends = ['is_admin', 'is_cam', 'is_owner', 'is_crew', 'is_super'];

    #[Override]
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function isReadOnly(): bool
    {
        return $this->id === 1;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function crew(): HasMany
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    #[Override]
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    #[Override]
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function ownerAircraft(): BelongsToMany
    {
        return $this->belongsToMany(OwnerAircraft::class, 'owner_aircraft', 'owner_id', 'aircraft_id', 'user_id')->withTimestamps();
    }

    protected function isSuper(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->hasRole('super-admin')
        );
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->hasRole('admin')
        );
    }

    protected function isCam(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->hasRole('cam')
        );
    }

    protected function isOwner(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->hasRole('owner')
        );
    }

    protected function isCrew(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->hasRole('crew')
        );
    }
}
