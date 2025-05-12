<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface EngineTypeRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newEngineType(array $data): ?Model;

    public function updateEngineType(array $data, int $id): ?Model;

    public function deleteEngineType(int $id): bool;
}
