<?php

namespace App\Repositories;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\BrandAircraft;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class BrandAircraftRepository implements BrandAircraftRepositoryInterface
{
    public function __construct(protected BrandAircraft $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model->orderBy('name', 'asc')
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search.'%');
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
    public function newBrandAircraft(array $data): ?Model
    {
        try {
            // Verifica si existe un registro borrado lógicamente
            $deletedBrandAircraft = $this->model::onlyTrashed()->where('name', $data['name'])->first();

            if ($deletedBrandAircraft) {
                // Sí existe, restaurar el modelo
                $deletedBrandAircraft->restore();

                return $deletedBrandAircraft; // Devuelve el modelo restaurado
            }

            // Si no existe un registro borrado, crear uno nuevo
            return $this->model::query()->create($data);
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateBrandAircraft(array $data, int $id): ?Model
    {
        try {
            $this->model->findOrFail($id)->update($data);

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
    public function deleteBrandAircraft(int $id): bool
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
