<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function save(array $data): ?Model;

    public function update(array $data, int $id): ?Model;

    public function delete(int $id): bool;
}
