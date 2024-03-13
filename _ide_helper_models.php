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


namespace App\Models{use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;
/**
 *
 *
 * @property int $id
 * @property int $owner_id
 * @property int $aircraft_model_id
 * @property Carbon $construction_date
 * @property string $serial
 * @property string $registration
 * @property string|null $flight_hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AircraftModel $aircraftModel
 * @property-read Collection<int, FlightHour> $flightHours
 * @property-read int|null $flight_hours_count
 * @property-read User $owner
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
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Aircraft withoutTrashed()
 */
	class Aircraft extends \Eloquent {}
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 */
	class AircraftModel extends \Eloquent {}
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
 */
	class FlightHour extends \Eloquent {}
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
 */
	class Manufacturer extends \Eloquent {}
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
 */
	class Team extends \Eloquent {}
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
 */
	class TeamUser extends \Eloquent {}
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
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
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

