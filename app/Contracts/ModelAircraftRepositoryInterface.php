<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ModelAircraftRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newModelAircraft(array $data): ?Model;

    public function updateModelAircraft(array $data, int $id): ?Model;

    public function deleteModelAircraft(int $id): bool;
}
