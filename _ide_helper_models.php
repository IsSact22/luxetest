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
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $rateable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRate withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdminRate {}
}

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $aircraftOwner
 * @property-read int|null $aircraft_owner_count
 * @property-read mixed $engine_type
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\ModelAircraft $modelAircraft
 * @method static \Database\Factories\AircraftFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereModelAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aircraft withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandAircraft withoutTrashed()
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
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $estimate_finish_date
 * @property \Illuminate\Support\Carbon|null $finish_date
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Aircraft $aircraft
 * @property-read \App\Models\User|null $cam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CamoActivity> $camoActivity
 * @property-read int|null $camo_activity_count
 * @property-read mixed $camo_rate
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\CamoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereCamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereEstimateFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camo withoutTrashed()
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
 * @property int $labor_rate_id
 * @property int $labor_rate_value_id
 * @property bool $required
 * @property \Illuminate\Support\Carbon|null $date
 * @property string $name
 * @property string $description
 * @property numeric $estimate_time
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \App\ActivityStatus $status
 * @property string|null $comments
 * @property numeric|null $labor_mount
 * @property numeric|null $material_mount
 * @property string|null $material_information
 * @property string|null $awr
 * @property \App\ApprovalStatus|null $approval_status
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed $0
 * @property-read \App\Models\Camo $camo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection $images
 * @property-read mixed $get_special_rate
 * @property-read \App\Models\LaborRate $laborRate
 * @property-read \App\Models\LaborRateValue $laborRateValue
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read mixed $missing_rate_name
 * @property-read mixed $missing_rate_value
 * @property-read \App\Models\SpecialRate|null $specialRate
 * @method static \Database\Factories\CamoActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereAwr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereCamoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereEstimateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereLaborMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereLaborRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereLaborRateValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereMaterialInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereMaterialMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CamoActivity withoutTrashed()
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ModelAircraft> $modelAircraft
 * @property-read int|null $model_aircraft_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LaborRate> $rateable
 * @property-read int|null $rateable_count
 * @method static \Database\Factories\EngineTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EngineType withoutTrashed()
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
 * @property string $rateable_type
 * @property int $rateable_id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $amount
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $rateable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LaborRateValue> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereRateableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereRateableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRate withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLaborRate {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $labor_rate_id
 * @property \Illuminate\Support\Carbon $valid_from
 * @property \Illuminate\Support\Carbon|null $valid_to
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\LaborRate $laborRate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereLaborRateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaborRateValue withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLaborRateValue {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereBrandAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereEngineTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelAircraft withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperModelAircraft {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $owner_id
 * @property int $aircraft_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aircraft|null $aircraftOwner
 * @property-read \App\Models\User|null $ownerAircraft
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerAircraft whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOwnerAircraft {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $camo_activity_id
 * @property string $name
 * @property string|null $description
 * @property numeric $amount
 * @property bool $is_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CamoActivity $activity
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereCamoActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpecialRate withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSpecialRate {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $owner_id
 * @property bool $disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $crew
 * @property-read int|null $crew_count
 * @property-read mixed $is_admin
 * @property-read mixed $is_cam
 * @property-read mixed $is_crew
 * @property-read mixed $is_owner
 * @property-read mixed $is_super
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OwnerAircraft> $ownerAircraft
 * @property-read int|null $owner_aircraft_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

