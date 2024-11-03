<?php

namespace App\Repositories;

use App\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(protected Permission $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model->orderBy('name')
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search . '%');
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
    public function newModel(array $attributes): ?Model
    {
        return $this->model->create($attributes);
    }

    #[Override]
    public function updateModel(array $attributes, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($attributes);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteModel(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
