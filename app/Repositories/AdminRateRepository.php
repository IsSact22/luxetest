<?php

namespace App\Repositories;

use App\Contracts\AdminRateRepositoryInterface;
use App\Models\AdminRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class AdminRateRepository implements AdminRateRepositoryInterface
{
    public function __construct(protected AdminRate $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search.'%')
                    ->orWhere('description', 'like', $search.'%');
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
    public function deleteModel(int $id): ?bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
