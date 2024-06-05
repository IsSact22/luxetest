<?php

namespace App\Repositories;

use App\Contracts\EngineTypeRepositoryInterface;
use App\Models\EngineType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class EngineTypeRepository implements EngineTypeRepositoryInterface
{
    public function __construct(protected EngineType $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
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
    public function newEngineType(array $data): ?Model
    {
        return $this->model->create($data);
    }

    #[Override]
    public function updateEngineType(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteEngineType(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
