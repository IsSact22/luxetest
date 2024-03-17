<?php

namespace App\Repositories;

use App\Contracts\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(protected ?Project $model)
    {
    }

    #[\Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), function ($query, string $search) {
                $query->where('name', 'like', $search.'%');
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    #[\Override]
    public function getById(int $id): ?Model
    {
        return $this->model
            ->findOrFail($id);
    }

    #[\Override]
    public function save(array $data): ?Model
    {
        return $this->model->create($data);
    }

    #[\Override]
    public function update(array $data, int $id): ?Model
    {
        $model = $this->model->findOrFail($id);

        if (! $model->update($data)) {
            return null;
        }

        return $model->refresh();
    }

    #[\Override]
    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
