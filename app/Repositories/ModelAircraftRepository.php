<?php

namespace App\Repositories;

use App\Contracts\ModelAircraftRepositoryInterface;
use App\Models\ModelAircraft;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class ModelAircraftRepository implements ModelAircraftRepositoryInterface
{
    public function __construct(protected ModelAircraft $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search.'%')
                    ->orWhereHas('brandAircraft', static function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    })
                    ->orWhereHas('engineType', static function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    });
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
    public function newModelAircraft(array $data): ?Model
    {
        return $this->model->create($data);
    }

    #[Override]
    public function updateModelAircraft(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteModelAircraft(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
