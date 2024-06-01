<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface LaborRateRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newModel(array $data): ?Model;

    public function updateModel(array $data, int $id): ?Model;

    public function deleteModel(int $id): bool;
}
