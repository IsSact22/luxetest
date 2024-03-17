<?php

namespace App\Repositories;

use App\Contracts\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(protected ?Client $model, protected ?User $user)
    {
    }

    #[\Override]
    public function getAll(Request $request): LengthAwarePaginator
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;

        return $this->model
            ->with('users')
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
            ->with('users')
            ->findOrFail($id);
    }

    #[\Override]
    public function save(array $data): ?Model
    {
        $user['name'] = $data['name'];
        $user['email'] = $data['email'];
        $user['password'] = $data['password'];
        $this->user->create($user);

        $client['customer_name'] = $data['customer_name'];
        $client['phone'] = $data['phone'];
        $client = $this->user->clients()->create($client);

        return $this->model = Client::find($client->id);
    }

    #[\Override]
    public function update(array $data, int $id): ?Model
    {
        $user = $this->user->find($id);
        $user->nombre = $data['name'];
        $user->password = $data['password'];
        $user->save();

        $client['customer_name'] = $data['customer_name'];
        $client['phone'] = $data['phone'];

        $model = $this->model->findOrFail($id);

        if (! $model->update($client)) {
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
