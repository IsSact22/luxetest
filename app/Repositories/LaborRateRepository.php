<?php

namespace App\Repositories;

use App\Contracts\LaborRateRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\LaborRate;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class LaborRateRepository implements LaborRateRepositoryInterface
{
    public function __construct(protected ?LaborRate $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('code', 'like', $search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
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
    public function newModel(array $data): ?Model
    {
        try {
            $laborRate = $this->model::query()->create($data);
            $amount = $data['amount'] ?? null;
            if ($amount !== null) {
                $laborRate->values()->create([
                    'amount' => $amount,
                ]);
            }

            return $laborRate;
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateModel(array $data, int $id): ?Model
    {
        try {
            $laborRate = $this->model::query()->findOrFail($id);

            if ($laborRate) {
                $laborRate->update($data);
                $amount = $data['amount'] ?? null;

                if ($amount !== null) {
                    $laborRate->values()->create([
                        'amount' => $amount,
                    ]);
                }
            }

            return $laborRate->fresh();
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
    public function deleteModel(int $id): bool
    {
        try {
            return $this->model::query()->findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }
}
