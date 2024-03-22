<?php

namespace App\Repositories;

use App\Contracts\CamoRepositoryInterface;
use App\Models\Camo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class CamoRepository implements CamoRepositoryInterface
{
    public function __construct(protected ?Camo $model)
    {
    }

    #[Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->when($request->get('search'), function ($query, string $search) {
                $query->where('customer', 'like', $search.'%')
                    ->orWhere('contract', 'like', $search.'%')
                    ->orWhere('aircraft', 'like', $search.'%')
                    ->orWhere('location', 'like', $search.'%')
                    ->orWhereHas('owner', function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    })
                    ->orWhereHas('cam', function (Builder $query) use ($search) {
                        $query->where('name', 'like', $search.'%');
                    });
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
    public function newCamo(array $data): ?Model
    {
        return $this->model->create([
            'customer' => $data['customer'],
            'owner_id' => $data['owner_id'],
            'contract' => $data['contract'],
            'cam_id' => $data['cam_id'],
            'aircraft' => $data['aircraft'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'finish_date' => $data['finish_date'],
            'location' => $data['location'],
        ]);
    }

    #[Override]
    public function updateCamo(array $data, int $id): ?Model
    {
        $this->model->findOrFail($id)->update($data);

        return $this->model->fresh();
    }

    #[Override]
    public function deleteCamo(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
