<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use App\Traits\HasModelName;
use App\Traits\QueryExceptionDataTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

use function App\Helpers\AfterCatchUnknown;

class UserController extends Controller
{
    use HasModelName;
    use QueryExceptionDataTrait;

    public function index(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $users = User::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('id', $search)
                        ->orWhere('name', 'LIKE', $search.'%')
                        ->orWhereHas('roles', function (Builder $query) use ($search) {
                            $query->where('name', 'LIKE', $search.'%');
                        });
                })
                ->paginate()
                ->withQueryString();
            $resource = UserResource::collection($users);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'return resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return new ApiErrorResponse(
                $e,
                'Error occurred while fetching users.',
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(Request $request): ApiSuccessResponse|ApiErrorResponse
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
        $teamId = $request->has('team_id') ? $request->get('team_id') : null;
        try {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'team_id' => $teamId,
            ]);
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

    public function update(Request $request, $id): ApiSuccessResponse|ApiErrorResponse
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validatedData->fails()) {
            return new ApiErrorResponse(
                new ValidationException($validatedData->getException()),
                $validatedData->messages(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $user = User::findOrFail($id);

            if ($request->has('name') && ! empty($validatedData['name'])) {
                $user->name = $validatedData['name'];
            }

            if ($request->has('password') && ! empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            return new ApiSuccessResponse(
                $user,
                ['message' => 'User register successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );

        } catch (ModelNotFoundException) {
            return new ApiErrorResponse(
                new ModelNotFoundException,
                'Server Error',
                ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return new ApiErrorResponse(
                new Exception,
                'Server Error',
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @throws Throwable
     */
    public function delete(int $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->deleteOrFail();

            return new ApiSuccessResponse(
                [],
                ['message' => 'User deleted successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (ModelNotFoundException) {
            return new ApiErrorResponse(
                new ModelNotFoundException,
                'Server Error',
                ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            // Error Desconocido
            return AfterCatchUnknown();
        }
    }
}
