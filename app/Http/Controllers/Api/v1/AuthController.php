<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken($request->get('email'))->plainTextToken;
            $success['name'] = $user->name;

            return new ApiSuccessResponse(
                $success,
                ['message' => 'User login successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );

        }

        return new ApiErrorResponse(
            new AuthorizationException,
            'User not authorized',
            ResponseAlias::HTTP_FORBIDDEN
        );
    }

    public function register(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return new ApiErrorResponse(
                new ValidationException($validator->getException()),
                $validator->messages(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
            $success['token'] = $user->createToken($request->get('email'))->plainTextToken;
            $success['name'] = $user->name;

            return new ApiSuccessResponse(
                $success,
                ['message' => 'User register successfully.'],
                ResponseAlias::HTTP_CREATED
            );
        } catch (QueryException $e) {
            $connectionName = $e->getConnectionName();
            $sql = $e->getSql();
            $bindings = $e->getBindings();
            $previous = $e->getPrevious();

            return new ApiErrorResponse(
                new QueryException($connectionName, $sql, $bindings, $previous),
                'Duplicate entry',
                ResponseAlias::HTTP_CONFLICT
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                $e,
                'Server Error',
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        if (auth()->user()) {
            auth()->user()->tokens()->delete();

            return new ApiSuccessResponse(
                [],
                ['message' => 'User logged out successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );

        }

        // failure to authenticate
        return new ApiErrorResponse(
            new AuthorizationException,
            'User not authorized',
            ResponseAlias::HTTP_FORBIDDEN
        );
    }
}
