<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface BrandAircraftRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newBrandAircraft(array $data): ?Model;

    public function updateBrandAircraft(array $data, int $id): ?Model;

    public function deleteBrandAircraft(int $id): bool;
}
