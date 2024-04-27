<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CamoRateRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newCamoRate(array $data): ?Model;

    public function updateCamoRate(array $data, int $id): ?Model;

    public function deleteCamoRate(int $id): bool;
}
