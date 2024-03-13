<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Aircraft;
use App\Models\FlightHour;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

use function App\Helpers\AfterCatchUnknown;

class FlightHourController extends Controller
{
    protected Aircraft $aircraft;

    protected Carbon $time;

    public function register($id, $date, $time): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $flightHours = null;
            DB::transaction(function () use ($id, $date, $time) {
                $this->aircraft = Aircraft::findOrFail($id);
                $flightDate = Carbon::parse($date);

                $flightHours = new FlightHour;
                $flightHours->aircraft_id = $this->aircraft->id;
                $flightHours->flight_date = $flightDate->format('Y-m-d H:i');
                $flightHours->flight_hour = (float) $time;
                $flightHours->save();

                $this->aircraft->flight_hours = (float) $time;
                $this->aircraft->save();
            });

            return new ApiSuccessResponse(
                $flightHours,
                ['message' => 'flight hours updated'],
                ResponseAlias::HTTP_ACCEPTED
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

    }
}
