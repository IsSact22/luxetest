<?php

namespace App\Repositories;

use App\Contracts\CamoRateRepositoryInterface;
use App\Models\CamoRate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class CamoRateRepository implements CamoRateRepositoryInterface
{
    public function __construct(protected ?CamoRate $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('code', $search)
                    ->orWhere('name', 'like', $search.'%')
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
    public function newCamoRate(array $data): ?Model
    {
        return $this->model->create($data);
    }

    #[Override]
    public function updateCamoRate(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteCamoRate(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
