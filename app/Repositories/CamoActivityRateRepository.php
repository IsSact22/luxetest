<?php

namespace App\Repositories;

use App\Contracts\CamoActivityRateRepositoryInterface;
use App\Models\CamoActivityRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CamoActivityRateRepository implements CamoActivityRateRepositoryInterface
{
    public function __construct(protected CamoActivityRate $model)
    {
    }

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

    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function newModel(array $array): ?Model
    {
        return $this->model->create($array);
    }

    public function updateModel(array $array, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($array);

        return $this->model->fresh();
    }

    public function deleteModel(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
