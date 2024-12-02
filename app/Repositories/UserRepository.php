<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Override;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected ?User $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->withTrashed()
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search.'%')
                    ->orWhereHas('roles', static function ($query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    });
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    #[Override]
    public function getCams(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->role('cam')
            ->where('owner_id', null)
            ->paginate($perPage)
            ->withQueryString();
    }

    #[Override]
    public function getOwners(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->role('owner')
            ->where('owner_id', null)
            ->paginate($perPage)
            ->withQueryString();
    }

    #[Override]
    public function getCrew(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->role('crew')
            ->has('crew')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function getById(int $id): ?Model
    {
        try {
            return $this->model::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function newModel(array $data): ?Model
    {
        try {
            $user = $this->model::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'owner_id' => $data['owner_id'] ?? null,
            ]);

            $user->assignRole($data['role']);

            return $user;
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateModel(array $data, int $id): ?Model
    {
        try {
            $user = $this->model::query()->findOrFail($id);
            $user->update($data);

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user->fresh();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function deleteModel(int $id): bool
    {
        try {
            return $this->model->findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }
}
