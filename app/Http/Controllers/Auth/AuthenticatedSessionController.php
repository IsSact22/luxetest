<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\ApiErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Authenticate user and return token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new AuthenticationException;
            }
    
            $user = Auth::user();
            
            return new ApiSuccessResponse(
                data: [
                    'token' => $token, // Token JWT
                    'user' => $user
                ],
                metaData: ['message' => 'Authenticated successfully'],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Invalid credentials',
                statusCode: HttpResponse::HTTP_UNAUTHORIZED
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Authentication failed',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="Revoke current access token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Successfully logged out"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return new ApiSuccessResponse(
                data: null,
                metaData: ['message' => 'Successfully logged out'],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Logout failed',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}