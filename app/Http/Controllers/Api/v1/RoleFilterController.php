<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiErrorResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

use function App\Helpers\AfterCatchUnknown;

class RoleFilterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function filterByRole($role): AnonymousResourceCollection|ApiErrorResponse
    {
        try {
            //dd($role);
            $user = User::role($role, 'web')->get();
            if ($user->isEmpty()) {
                $emptyCollect = collect();

                return UserResource::collection($emptyCollect)->additional(['message' => $role.' not found']);
            }

            return UserResource::collection($user);
        } catch (RoleDoesNotExist $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Exception) {
            return AfterCatchUnknown();
        }
    }
}
