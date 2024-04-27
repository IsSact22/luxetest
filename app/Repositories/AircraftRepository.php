<?php

namespace App\Repositories;

use App\Contracts\AircraftRepositoryInterface;
use App\Models\Aircraft;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AircraftRepository implements AircraftRepositoryInterface
{
    public function __construct(protected Aircraft $model)
    {
    }

    public function getAll(Request $request): LengthAwarePaginator
    {
        // TODO: Implement getAll() method.
    }

    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function newAircraft(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function updateAircraft(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    public function deleteAircraft(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
