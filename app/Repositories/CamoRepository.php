<?php

namespace App\Repositories;

use App\Contracts\CamoRepositoryInterface;
use App\DTOs\CamoDTO;
use App\Exceptions\RepositoryException;
use App\Models\Camo;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

class CamoRepository implements CamoRepositoryInterface
{
    public function __construct(protected ?Camo $model) {}

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        $user = auth()->user();

        return $this->model
            ->when($request->get('owner_id'), static function ($query, int $ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->when($user && $user->isCam, static function ($query) use ($user) {
                $query->where(static function ($query) use ($user) {
                    $query->where('cam_id', $user->id);
                });
            })
            ->when($user && ($user->isOwner || $user->isCrew), static function ($query) use ($user) {
                $query->where(static function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhereHas('owner', static function ($query) use ($user) {
                            $query->where('owner_id', $user->id);
                        });
                });
            })
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('customer', 'like', $search.'%')
                    ->orWhere('contract', 'like', $search.'%')
                    ->orWhere('location', 'like', $search.'%')
                    ->orWhereHas('aircraft', static function (Builder $query) use ($search) {
                        $query->where('register', 'like', $search.'%');
                    })
                    ->orWhereHas('owner', static function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    })
                    ->orWhereHas('cam', static function (Builder $query) use ($search) {
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
            $dto = new CamoDTO(
                $data['customer'],
                $data['owner_id'],
                $data['contract'],
                $data['cam_id'],
                $data['aircraft_id'],
                $data['description'],
                $data['start_date'],
                $data['estimate_finish_date'],
                $data['finish_date'],
                $data['location'],
            );

            return DB::transaction(function () use ($dto) {
                return $this->model::query()->create([
                    'customer' => $dto->customer,
                    'owner_id' => $dto->ownerId,
                    'contract' => $dto->contract,
                    'cam_id' => $dto->camId,
                    'aircraft_id' => $dto->aircraftId,
                    'description' => $dto->description,
                    'start_date' => $dto->startDate,
                    'estimate_finish_date' => $dto->estimatedFinishDate,
                    'finish_date' => $dto->finishDate,
                    'location' => $dto->location,
                ]);
            });

        } catch (Throwable $e) {
            Log::error('Error al crear CAMO', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
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
