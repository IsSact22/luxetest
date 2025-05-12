<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'is_owner' => $this->is_owner,
            'is_crew' => $this->is_crew,
            'is_super' => $this->is_super,
            'email' => $this->email,
            'email_verified_at' => CarbonParse($this->email_verified_at),
            'locale' => $this->locale,
            'role' => $this->getRoleNames()->first(),
            'permissions' => PermissionResource::collection($this->getPermissionsViaRoles()),
            'avatar' => $this->getAvatarUrl(),
            'created_at' => CarbonParse($this->created_at),
            'updated_at' => CarbonParse($this->updated_at),
            'deleted_at' => $this->deleted_at ? CarbonParse($this->deleted_at) : null,
        ];
    }
}
