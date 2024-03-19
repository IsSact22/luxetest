<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CamoRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newCamo(array $data): ?Model;

    public function updateCamo(array $data, int $id): ?Model;

    public function deleteCamo(int $id): bool;
}
