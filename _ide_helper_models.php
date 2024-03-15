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
 * @property int $project_id
 * @property string $approved_labor
 * @property string $pending_labor
 * @property string $approved_material
 * @property string $pending_material
 * @property string $total_labor
 * @property string $total_material
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereApprovedLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereApprovedMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope wherePendingLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope wherePendingMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereTotalLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereTotalMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalScope whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdditionalScope {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $owner_id
 * @property int $aircraft_model_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $construction_date
 * @property string $serial
 * @property string $registration
 * @property string|null $flight_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AircraftModel $aircraftModel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlightHour> $flightHours
 * @property-read int|null $flight_hours_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\AircraftFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereAircraftModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereConstructionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereFlightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereRegistration($value)
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
 * @property int $manufacturer_id
 * @property string $name
 * @property string $category
 * @property string $class
 * @property int $motor_type
 * @property int $motor_qty
 * @property int $passenger_qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Manufacturer $manufacturer
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereMotorQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereMotorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel wherePassengerQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AircraftModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAircraftModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $customer_name
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ClientProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperClientProfile {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $aircraft_id
 * @property \Illuminate\Support\Carbon $flight_date
 * @property string $flight_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aircraft $aircraft
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereFlightDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereFlightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightHour whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFlightHour {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $project_id
 * @property string $labor
 * @property string $material
 * @property string $adjusted_labor
 * @property string $adjusted_material
 * @property string $total_labor
 * @property string $total_material
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope query()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereAdjustedLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereAdjustedMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereTotalLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereTotalMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialScope whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperInitialScope {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $acronym
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AircraftModel> $aircraftModels
 * @property-read int|null $aircraft_models_count
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereAcronym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manufacturer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperManufacturer {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int $project_id
 * @property string $mount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPayment {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property int $client
 * @property int $project_manager
 * @property string $contract
 * @property string $aircraft
 * @property string $description
 * @property string $start_date
 * @property string $end_estimated
 * @property string|null $finish_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAircraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEndEstimated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProject {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $start_date
 * @property string $interval
 * @property string $mount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereMount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchedulingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSchedulingPayment {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $estimate_time
 * @property bool $has_material
 * @property bool $disable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDisable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereEstimateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereHasMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperService {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $project_id
 * @property string $gum_ecotax_labor
 * @property string $gum_ecotax_material
 * @property string $discount_labor
 * @property string $discount_material
 * @property string $nte_discount_labor
 * @property string $nte_discount_material
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereDiscountLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereDiscountMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereGumEcotaxLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereGumEcotaxMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereNteDiscountLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereNteDiscountMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurchargeDiscount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSurchargeDiscount {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $user_id Group Leader
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeam {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $team_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamUser whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeamUser {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClientProfile|null $clientProfile
 * @property-read bool $has_profile
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|User clients()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

