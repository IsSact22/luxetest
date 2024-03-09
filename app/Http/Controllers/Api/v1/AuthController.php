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
            $credentials = $request->only(['email', 'password']);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $success['token'] = $user->createToken($request->get('email'))->plainTextToken;
                $success['name'] = $user->name;

                return new ApiSuccessResponse($success, ['message' => 'login successfully']);
            }
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

        return new ApiErrorResponse(new AuthenticationException, 'login failed');
    }

    public function logout(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $user = auth()->user();
            $user->tokens()->delete();

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
