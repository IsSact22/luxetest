<?php

namespace App\Repositories;

use App\Contracts\AdminRateRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\AdminRate;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class AdminRateRepository implements AdminRateRepositoryInterface
{
    public function __construct(protected AdminRate $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search . '%')
                    ->orWhere('description', 'like', $search . '%');
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function getById(int $id): ?Model
    {
        try {
            return $this->model::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        }

    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function newModel(array $attributes): ?Model
    {
        try {
            return $this->model::query()->create($attributes);
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }

    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateModel(array $attributes, int $id): ?Model
    {
        try {
            $this->model->findOrFail($id)->update($attributes);

            return $this->model->fresh();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }

    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function deleteModel(int $id): ?bool
    {
        try {
            return $this->model->findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }
}
