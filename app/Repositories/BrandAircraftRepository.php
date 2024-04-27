<?php

namespace App\Repositories;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Models\BrandAircraft;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandAircraftRepository implements BrandAircraftRepositoryInterface
{
    public function __construct(protected BrandAircraft $model)
    {
    }

    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->has('search'), function ($query, string $search) {
                $query->where('name', 'like', "{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function newBrandAircraft(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function updateBrandAircraft(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    public function deleteBrandAircraft(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
