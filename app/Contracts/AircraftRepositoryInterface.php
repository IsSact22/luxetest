<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface AircraftRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newAircraft(array $data): ?Model;

    public function updateAircraft(array $data, int $id): ?Model;

    public function deleteAircraft(int $id): bool;
}
