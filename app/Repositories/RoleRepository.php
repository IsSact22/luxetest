<?php

namespace App\Repositories;

use App\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(protected ?Role $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), function ($query, string $search) {
                $query->where('name', 'like', $search.'%');
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    #[Override]
    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    #[Override]
    public function newRole(array $data): ?Model
    {
        return $this->model->create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);
    }

    #[Override]
    public function updateRole(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteRole(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
