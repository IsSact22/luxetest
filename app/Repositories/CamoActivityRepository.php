<?php

namespace App\Repositories;

use App\Contracts\CamoActivityRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\CamoActivity;
use App\Models\LaborRate;
use App\Models\SpecialRate;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

class CamoActivityRepository implements CamoActivityRepositoryInterface
{
    public function __construct(protected ?CamoActivity $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('camo_id'), static function ($query, int $camoId) {
                $query->where('camo_id', $camoId);
            })
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
    public function newModel(array $data): CamoActivity
    {
        try {
            return $this->model::query()->create($data);
        } catch (Exception $e) {
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateModel(array $data, int $id): ?CamoActivity
    {
        //dd($data);
        try {
            $this->model->findOrFail($id)->update($data);
            // tarifa especial
            if (! is_null($data['special_rate']) && $data['special_rate'] > 0) {
                SpecialRate::create([
                    'camo_activity_id' => $id,
                    'name' => $this->getLaborRateName($data['labor_rate_id']),
                    'description' => 'Se aplica tarifa especial',
                    'amount' => $data['special_rate'],
                    'is_used' => true,
                ]);
            }

            return $this->model->fresh();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 404, $e->getCode());
        } catch (Exception|Throwable $e) {
            Log::error('Error inesperado en updateModel: '.$e->getMessage());
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    private function getLaborRateName($id)
    {
        $laborRate = LaborRate::find($id);

        return $laborRate->name ?? null;
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function deleteModel(int $id): bool
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
