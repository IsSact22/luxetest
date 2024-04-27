<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Override;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected ?User $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
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

    #[Override]
    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    #[Override]
    public function newUser(array $data): ?Model
    {
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'owner_id' => $data['owner_id'] ?: null,
        ]);

        $user->assignRole($data['role']);

        return $user;
    }

    #[Override]
    public function updateUser(array $data, int $id): ?Model
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user->fresh();
    }

    #[Override]
    public function deleteUser(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
