<?php

namespace App\Repositories;

use App\Contracts\AircraftRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\Aircraft;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class AircraftRepository implements AircraftRepositoryInterface
{
    public function __construct(protected Aircraft $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('register', 'like', $search.'%')
                    ->orWhere('serial', 'like', $search.'%')
                    ->orWhereHas('modelAircraft', function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    });
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
            // Verifica si existe un registro borrado lógicamente
            $deletedAircraft = $this->model::onlyTrashed()
                ->where('model_aircraft_id', $data['model_aircraft_id'])
                ->where('register', $data['register'])
                ->where('serial', $data['serial'])
                ->first();

            if ($deletedAircraft) {
                $deletedAircraft->restore();

                return $deletedAircraft;
            }

            return $this->model::query()->create($data);
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
            $aircraft = $this->model::query()->findOrFail($id);
            if ($aircraft) {
                $aircraft->update($data);
            }

            return $aircraft->fresh();
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
