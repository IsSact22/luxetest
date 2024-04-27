<?php

namespace App\Repositories;

use App\Contracts\CamoActivityRepositoryInterface;
use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class CamoActivityRepository implements CamoActivityRepositoryInterface
{
    public function __construct(protected ?CamoActivity $model)
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
                $query->where('name', 'like', $search.'%');
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    #[Override]
    public function getById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    #[Override]
    public function newActivity(array $data): ?Model
    {
        return $this->model->create([
            'camo_id' => $data['camo_id'],
            'required' => $data['required'],
            'date' => $data['date'],
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => $data['status'],
            'comments' => $data['comments'],
            'labor_mount' => $data['labor_mount'],
            'material_mount' => $data['material_mount'],
            'material_information' => $data['material_information'],
            'awr' => $data['awr'],
            'approval_status' => $data['approval_status'],
        ]);
    }

    #[Override]
    public function updateActivity(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteActivity(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
