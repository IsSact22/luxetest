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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use function App\Helpers\AfterCatchUnknown;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
        } catch (Exception $exception) {
            LogHelper::logError($exception);

            return AfterCatchUnknown();
        }
    }

    /**
     * Logout the user (Invalidate the token).
     */
    public function logout(): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            auth('api')->logout();

            return new ApiSuccessResponse(
                null,
                ['message' => 'Successfully logged out'],
                ResponseAlias::HTTP_OK
            );
        } catch (Exception $exception) {
            LogHelper::logError($exception);

            return AfterCatchUnknown();
        }
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            \DB::beginTransaction();

            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
            ]);

            // Asignar el rol por defecto si no lo tiene
            if (!$user->hasRole('cam')) {
                $user->assignRole('cam');
            }

            \DB::commit();

            // Generar token JWT
            $token = auth('api')->login($user);

            return new ApiSuccessResponse(
                [
                    'status' => 'success',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                ],
                ['message' => 'User registered successfully'],
                ResponseAlias::HTTP_CREATED
            );

        } catch (Exception $exception) {
            \DB::rollBack();
            LogHelper::logError($exception);

            return AfterCatchUnknown();
        }
    }
}
