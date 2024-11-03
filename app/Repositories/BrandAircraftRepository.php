<?php

namespace App\Repositories;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Models\BrandAircraft;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class BrandAircraftRepository implements BrandAircraftRepositoryInterface
{
    public function __construct(protected BrandAircraft $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model->orderBy('name', 'asc')
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
    public function newBrandAircraft(array $data): ?Model
    {
        return $this->model->create($data);
    }

    #[Override]
    public function updateBrandAircraft(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteBrandAircraft(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
