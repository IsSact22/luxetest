<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $model_aircraft_id
 * @property string $register
 * @property string $serial
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\ModelAircraft $modelAircraft
 * @method static \Database\Factories\AircraftFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereModelAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAircraft {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\BrandAircraftFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandAircraft withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBrandAircraft {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $customer
 * @property int $owner_id
 * @property string $contract
 * @property int $cam_id
 * @property int $aircraft_id
 * @property string $description
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $finish_date
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CamoActivity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $cam
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\CamoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Camo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Camo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereCamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camo withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Camo withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCamo {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $camo_id
 * @property bool $required
 * @property \Illuminate\Support\Carbon|null $date
 * @property string $name
 * @property string $description
 * @property string $status
 * @property string|null $comments
 * @property string|null $labor_mount
 * @property string|null $material_mount
 * @property string|null $material_information
 * @property string|null $awr
 * @property string|null $approval_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Camo $camo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection $images
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Database\Factories\CamoActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereAwr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereCamoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereLaborMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereMaterialInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereMaterialMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CamoActivity withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCamoActivity {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\EngineTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EngineType withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperEngineType {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_aircraft_id
 * @property int $engine_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BrandAircraft $brandAircraft
 * @property-read \App\Models\EngineType $engineType
 * @method static \Database\Factories\ModelAircraftFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereBrandAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereEngineTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAircraft withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperModelAircraft {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property int|null $owner_id
 * @property bool $disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $crew
 * @property-read int|null $crew_count
 * @property-read bool $is_admin
 * @property-read bool $is_cam
 * @property-read bool $is_crew
 * @property-read bool $is_owner
 * @property-read bool $is_super
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

