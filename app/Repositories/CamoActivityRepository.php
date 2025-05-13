<?php

namespace App\Repositories;

use App\ActivityStatus;
use App\ApprovalStatus;
use App\Contracts\CamoActivityRepositoryInterface;
use App\DTOs\CamoActivityDTO;
use App\Exceptions\RepositoryException;
use App\Models\CamoActivity;
use App\Models\LaborRate;
use App\Models\SpecialRate;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

class CamoActivityRepository implements CamoActivityRepositoryInterface
{
    public function __construct(protected CamoActivity $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('camo_id'), static function ($query, int $camoId) {
                $query->where('camo_id', $camoId);
            })
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search . '%');
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
            $status = ActivityStatus::from($data['status']);
            $approvalStatus = ApprovalStatus::from($data['approval_status']);
            $dto = new CamoActivityDTO(
                null,
                $data['camo_id'],
                $data['labor_rate_id'],
                $data['labor_rate_value_id'],
                $data['required'],
                $data['date'],
                $data['name'],
                $data['description'] ?? null,
                $data['estimate_time'],
                $data['started_at'] ?? null,
                $data['completed_at'] ?? null,
                $status,
                $data['comments'] ?? null,
                (float)$data['labor_mount'],
                $data['material_mount'] ?? 0,
                $data['material_information'] ?? null,
                $data['awr'] ?? null,
                $approvalStatus
            );

            //dd($dto);
            return DB::transaction(function () use ($dto) {
                return $this->model::query()->create([
                    'camo_id' => $dto->camoId,
                    'labor_rate_id' => $dto->laborRateId,
                    'labor_rate_value_id' => $dto->laborRateValueId,
                    'required' => $dto->required,
                    'date' => $dto->date,
                    'name' => $dto->name,
                    'description' => $dto->description,
                    'estimate_time' => $dto->estimateTime,
                    'started_at' => $dto->startedAt,
                    'completed_at' => $dto->completedAt,
                    'status' => $dto->status->value, // Asumiendo que status es un enum
                    'comments' => $dto->comments,
                    'labor_mount' => $dto->laborMount,
                    'material_mount' => $dto->materialMount,
                    'material_information' => $dto->materialInformation,
                    'awr' => $dto->awr,
                    'approval_status' => $dto->approvalStatus->value,
                ]);
            });

        } catch (Exception|Throwable $e) {
            Log::error('Error al crear la camo activity', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw new RepositoryException($e->getMessage(), 500, $e->getCode());
        }
    }

    /**
     * @throws RepositoryException
     */
    #[Override]
    public function updateModel(array $data, int $id): CamoActivity
    {
        try {

            $camoActivity = $this->model->findOrFail($id);

            $status = ActivityStatus::from($data['status']);
            $approvalStatus = ApprovalStatus::from($data['approval_status']);

            $dto = new CamoActivityDTO(
                $id,
                $data['camo_id'],
                $data['labor_rate_id'],
                $data['labor_rate_value_id'],
                $data['required'],
                $data['date'],
                $data['name'],
                $data['description'] ?? null,
                $data['estimate_time'],
                $data['started_at'] ?? null,
                $data['completed_at'] ?? null,
                $status,
                $data['comments'] ?? null,
                (float)$data['labor_mount'],
                $data['material_mount'] ?? 0,
                $data['material_information'] ?? null,
                $data['awr'] ?? null,
                $approvalStatus
            );

            $result = $camoActivity->update([
                'id' => $id,
                'camo_id' => $dto->camoId,
                'labor_rate_id' => $dto->laborRateId,
                'labor_rate_value_id' => $dto->laborRateValueId,
                'required' => $dto->required,
                'date' => $dto->date,
                'name' => $dto->name,
                'description' => $dto->description,
                'estimate_time' => $dto->estimateTime,
                'started_at' => $dto->startedAt,
                'completed_at' => $dto->completedAt,
                'status' => $dto->status->value, // Asumiendo que status es un enum
                'comments' => $dto->comments,
                'labor_mount' => $dto->laborMount,
                'material_mount' => $dto->materialMount,
                'material_information' => $dto->materialInformation,
                'awr' => $dto->awr,
                'approval_status' => $dto->approvalStatus->value,
            ]);
            Log::info('La camo activity actualizada', [
                'result' => $result,
            ]);
            // tarifa especial
            if (!is_null($data['special_rate']) && $data['special_rate'] > 0) {
                SpecialRate::create([
                    'camo_activity_id' => $id,
                    'name' => $this->getLaborRateName($data['labor_rate_id']),
                    'description' => 'Se aplica tarifa especial',
                    'amount' => $data['special_rate'],
                    'is_used' => true,
                ]);
            }

            return $camoActivity;

        } catch (ModelNotFoundException $e) {
            Log::error('Modelo no encontrado: ' . $e->getMessage(), ['id' => $id]);
            throw new RepositoryException('CamoActivity no encontrado', 404);

        } catch (QueryException $e) {
            Log::error('Error de base de datos: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'id' => $id,
            ]);
            throw new RepositoryException('Error al actualizar el modelo', 500);

        } catch (Exception|Throwable $e) {
            Log::error('Error general en el repositorio: ' . $e->getMessage(), [
                'trace' => $e->getTrace(),
            ]);
            throw new RepositoryException('Error desconocido al actualizar el modelo', 500);
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
