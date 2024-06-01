<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CamoActivityRateRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newModel(array $array): ?Model;

    public function updateModel(array $array, int $id): ?Model;

    public function deleteModel(int $id): bool;
}
