<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CamoActivityRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newActivity(array $data): ?Model;

    public function updateActivity(array $data, int $id): ?Model;

    public function deleteActivity(int $id): bool;
}
