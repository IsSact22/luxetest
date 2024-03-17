<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Override;

use function App\Helpers\CarbonParse;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_client' => $this->is_client,
            'client_data' => $this->when(
                ($this->clients()->exists() && ! Route::is('clients.*')),
                $this->clients
            ),
            'email' => $this->email,
            'email_verified_at' => CarbonParse($this->email_verified_at),
            'role' => $this->getRoleNames()->first(),
            'permissions' => PermissionResource::collection($this->getPermissionsViaRoles()),
            'created_at' => CarbonParse($this->created_at),
            'updated_at' => CarbonParse($this->updated_at),
            'route' => null,
        ];
    }
}
