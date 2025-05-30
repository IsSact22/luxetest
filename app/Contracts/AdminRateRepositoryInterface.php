<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdminRateRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function getById(int $id): ?Model;

    public function newModel(array $attributes): ?Model;

    public function updateModel(array $attributes, int $id): ?Model;

    public function deleteModel(int $id): ?bool;
}
