<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use function App\Helpers\AfterCatchUnknown;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function login(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');

            if (! $token = auth('api')->attempt($credentials)) {
                return new ApiErrorResponse(
                    new AuthenticationException,
                    'Unauthorized',
                    ResponseAlias::HTTP_UNAUTHORIZED
                );
            }

            $user = auth('api')->user();

            return new ApiSuccessResponse(
                [
                    'status' => 'success',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                ],
                ['message' => 'login successfully'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function refresh(): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            return new ApiSuccessResponse(
                [
                    'status' => 'success',
                    'user' => Auth::user(),
                    'authorisation' => [
                        'token' => Auth::refresh(),
                        'type' => 'bearer',
                    ],
                ],
                ['message' => 'login successfully'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

    }

    public function logout(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            Auth::logout();

            return new ApiSuccessResponse(
                [],
                ['message' => 'User logged out successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (AuthorizationException $e) {
            // failure to logout
            return new ApiErrorResponse(
                $e,
                'User not authorized',
                ResponseAlias::HTTP_FORBIDDEN
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }
}
