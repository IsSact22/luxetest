<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getCams(Request $request): LengthAwarePaginator;

    public function getOwners(Request $request): LengthAwarePaginator;

    public function getCrew(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newUser(array $data): ?Model;

    public function updateUser(array $data, int $id): ?Model;

    public function deleteUser(int $id): bool;
}
