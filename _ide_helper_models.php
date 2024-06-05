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


namespace App\Models{use AllowDynamicProperties;use Database\Factories\AircraftFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;use Spatie\MediaLibrary\MediaCollections\Models\Media;
/**
 *
 *
 * @property int $id
 * @property int $model_aircraft_id
 * @property string $register
 * @property string $serial
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read mixed $engine_type
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read ModelAircraft $modelAircraft
 * @method static AircraftFactory factory($count = null, $state = [])
 * @method static Builder|Aircraft newModelQuery()
 * @method static Builder|Aircraft newQuery()
 * @method static Builder|Aircraft onlyTrashed()
 * @method static Builder|Aircraft query()
 * @method static Builder|Aircraft whereCreatedAt($value)
 * @method static Builder|Aircraft whereDeletedAt($value)
 * @method static Builder|Aircraft whereId($value)
 * @method static Builder|Aircraft whereModelAircraftId($value)
 * @method static Builder|Aircraft whereRegister($value)
 * @method static Builder|Aircraft whereSerial($value)
 * @method static Builder|Aircraft whereUpdatedAt($value)
 * @method static Builder|Aircraft withTrashed()
 * @method static Builder|Aircraft withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperAircraft {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\BrandAircraftFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static BrandAircraftFactory factory($count = null, $state = [])
 * @method static Builder|BrandAircraft newModelQuery()
 * @method static Builder|BrandAircraft newQuery()
 * @method static Builder|BrandAircraft onlyTrashed()
 * @method static Builder|BrandAircraft query()
 * @method static Builder|BrandAircraft whereCreatedAt($value)
 * @method static Builder|BrandAircraft whereDeletedAt($value)
 * @method static Builder|BrandAircraft whereId($value)
 * @method static Builder|BrandAircraft whereName($value)
 * @method static Builder|BrandAircraft whereUpdatedAt($value)
 * @method static Builder|BrandAircraft withTrashed()
 * @method static Builder|BrandAircraft withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperBrandAircraft {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\CamoFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
 * @property Carbon $start_date
 * @property Carbon $finish_date
 * @property string|null $location
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Aircraft $aircraft
 * @property-read User|null $cam
 * @property-read Collection<int, CamoActivity> $camoActivity
 * @property-read int|null $camo_activity_count
 * @property-read mixed $camo_rate
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read User $owner
 * @method static CamoFactory factory($count = null, $state = [])
 * @method static Builder|Camo newModelQuery()
 * @method static Builder|Camo newQuery()
 * @method static Builder|Camo onlyTrashed()
 * @method static Builder|Camo query()
 * @method static Builder|Camo whereAircraftId($value)
 * @method static Builder|Camo whereCamId($value)
 * @method static Builder|Camo whereContract($value)
 * @method static Builder|Camo whereCreatedAt($value)
 * @method static Builder|Camo whereCustomer($value)
 * @method static Builder|Camo whereDeletedAt($value)
 * @method static Builder|Camo whereDescription($value)
 * @method static Builder|Camo whereFinishDate($value)
 * @method static Builder|Camo whereId($value)
 * @method static Builder|Camo whereLocation($value)
 * @method static Builder|Camo whereOwnerId($value)
 * @method static Builder|Camo whereStartDate($value)
 * @method static Builder|Camo whereUpdatedAt($value)
 * @method static Builder|Camo withTrashed()
 * @method static Builder|Camo withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperCamo {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\CamoActivityFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;use Spatie\MediaLibrary\MediaCollections\Models\Media;
/**
 *
 *
 * @property int $id
 * @property int $camo_id
 * @property int $camo_activity_rate_id
 * @property bool $required
 * @property Carbon|null $date
 * @property string $name
 * @property string $description
 * @property string $status
 * @property string|null $comments
 * @property string|null $labor_mount
 * @property string|null $material_mount
 * @property string|null $material_information
 * @property string|null $awr
 * @property string|null $approval_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Camo $camo
 * @property-read MediaCollection $images
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @method static CamoActivityFactory factory($count = null, $state = [])
 * @method static Builder|CamoActivity newModelQuery()
 * @method static Builder|CamoActivity newQuery()
 * @method static Builder|CamoActivity onlyTrashed()
 * @method static Builder|CamoActivity query()
 * @method static Builder|CamoActivity whereApprovalStatus($value)
 * @method static Builder|CamoActivity whereAwr($value)
 * @method static Builder|CamoActivity whereCamoActivityRateId($value)
 * @method static Builder|CamoActivity whereCamoId($value)
 * @method static Builder|CamoActivity whereComments($value)
 * @method static Builder|CamoActivity whereCreatedAt($value)
 * @method static Builder|CamoActivity whereDate($value)
 * @method static Builder|CamoActivity whereDeletedAt($value)
 * @method static Builder|CamoActivity whereDescription($value)
 * @method static Builder|CamoActivity whereId($value)
 * @method static Builder|CamoActivity whereLaborMount($value)
 * @method static Builder|CamoActivity whereMaterialInformation($value)
 * @method static Builder|CamoActivity whereMaterialMount($value)
 * @method static Builder|CamoActivity whereName($value)
 * @method static Builder|CamoActivity whereRequired($value)
 * @method static Builder|CamoActivity whereStatus($value)
 * @method static Builder|CamoActivity whereUpdatedAt($value)
 * @method static Builder|CamoActivity withTrashed()
 * @method static Builder|CamoActivity withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperCamoActivity {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\CamoActivityRateFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $mount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, CamoActivityType> $camoActivityType
 * @property-read int|null $camo_activity_type_count
 * @method static CamoActivityRateFactory factory($count = null, $state = [])
 * @method static Builder|CamoActivityRate newModelQuery()
 * @method static Builder|CamoActivityRate newQuery()
 * @method static Builder|CamoActivityRate onlyTrashed()
 * @method static Builder|CamoActivityRate query()
 * @method static Builder|CamoActivityRate whereCode($value)
 * @method static Builder|CamoActivityRate whereCreatedAt($value)
 * @method static Builder|CamoActivityRate whereDeletedAt($value)
 * @method static Builder|CamoActivityRate whereId($value)
 * @method static Builder|CamoActivityRate whereMount($value)
 * @method static Builder|CamoActivityRate whereName($value)
 * @method static Builder|CamoActivityRate whereUpdatedAt($value)
 * @method static Builder|CamoActivityRate withTrashed()
 * @method static Builder|CamoActivityRate withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperCamoActivityRate {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\CamoActivityTypeFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property int $camo_activity_rate_id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read CamoActivityRate $activityRate
 * @method static CamoActivityTypeFactory factory($count = null, $state = [])
 * @method static Builder|CamoActivityType newModelQuery()
 * @method static Builder|CamoActivityType newQuery()
 * @method static Builder|CamoActivityType onlyTrashed()
 * @method static Builder|CamoActivityType query()
 * @method static Builder|CamoActivityType whereCamoActivityRateId($value)
 * @method static Builder|CamoActivityType whereCreatedAt($value)
 * @method static Builder|CamoActivityType whereDeletedAt($value)
 * @method static Builder|CamoActivityType whereDescription($value)
 * @method static Builder|CamoActivityType whereId($value)
 * @method static Builder|CamoActivityType whereName($value)
 * @method static Builder|CamoActivityType whereUpdatedAt($value)
 * @method static Builder|CamoActivityType withTrashed()
 * @method static Builder|CamoActivityType withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperCamoActivityType {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\CamoRateFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property int $engine_type_id
 * @property string $code
 * @property string $name
 * @property string $mount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read EngineType $engineType
 * @method static CamoRateFactory factory($count = null, $state = [])
 * @method static Builder|LaborRate newModelQuery()
 * @method static Builder|LaborRate newQuery()
 * @method static Builder|LaborRate onlyTrashed()
 * @method static Builder|LaborRate query()
 * @method static Builder|LaborRate whereCode($value)
 * @method static Builder|LaborRate whereCreatedAt($value)
 * @method static Builder|LaborRate whereDeletedAt($value)
 * @method static Builder|LaborRate whereEngineTypeId($value)
 * @method static Builder|LaborRate whereId($value)
 * @method static Builder|LaborRate whereMount($value)
 * @method static Builder|LaborRate whereName($value)
 * @method static Builder|LaborRate whereUpdatedAt($value)
 * @method static Builder|LaborRate withTrashed()
 * @method static Builder|LaborRate withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperCamoRate {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\EngineTypeFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, LaborRate> $camoRate
 * @property-read int|null $camo_rate_count
 * @property-read Collection<int, ModelAircraft> $modelAircraft
 * @property-read int|null $model_aircraft_count
 * @method static EngineTypeFactory factory($count = null, $state = [])
 * @method static Builder|EngineType newModelQuery()
 * @method static Builder|EngineType newQuery()
 * @method static Builder|EngineType onlyTrashed()
 * @method static Builder|EngineType query()
 * @method static Builder|EngineType whereCreatedAt($value)
 * @method static Builder|EngineType whereDeletedAt($value)
 * @method static Builder|EngineType whereId($value)
 * @method static Builder|EngineType whereName($value)
 * @method static Builder|EngineType whereUpdatedAt($value)
 * @method static Builder|EngineType withTrashed()
 * @method static Builder|EngineType withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperEngineType {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\ModelAircraftFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property int $brand_aircraft_id
 * @property int $engine_type_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read BrandAircraft $brandAircraft
 * @property-read EngineType $engineType
 * @method static ModelAircraftFactory factory($count = null, $state = [])
 * @method static Builder|ModelAircraft newModelQuery()
 * @method static Builder|ModelAircraft newQuery()
 * @method static Builder|ModelAircraft onlyTrashed()
 * @method static Builder|ModelAircraft query()
 * @method static Builder|ModelAircraft whereBrandAircraftId($value)
 * @method static Builder|ModelAircraft whereCreatedAt($value)
 * @method static Builder|ModelAircraft whereDeletedAt($value)
 * @method static Builder|ModelAircraft whereEngineTypeId($value)
 * @method static Builder|ModelAircraft whereId($value)
 * @method static Builder|ModelAircraft whereName($value)
 * @method static Builder|ModelAircraft whereUpdatedAt($value)
 * @method static Builder|ModelAircraft withTrashed()
 * @method static Builder|ModelAircraft withoutTrashed()
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperModelAircraft {}
}

namespace App\Models{use AllowDynamicProperties;use Database\Factories\UserFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Notifications\DatabaseNotification;use Illuminate\Notifications\DatabaseNotificationCollection;use Illuminate\Support\Carbon;use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;use Spatie\MediaLibrary\MediaCollections\Models\Media;use Spatie\Permission\Models\Permission;use Spatie\Permission\Models\Role;
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property int|null $owner_id
 * @property bool $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, User> $crew
 * @property-read int|null $crew_count
 * @property-read mixed $is_admin
 * @property-read mixed $is_cam
 * @property-read mixed $is_crew
 * @property-read mixed $is_owner
 * @property-read mixed $is_super
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read User|null $owner
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDisabled($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereOwnerId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @mixin Eloquent
 */
	#[AllowDynamicProperties]
	class IdeHelperUser {}
}

