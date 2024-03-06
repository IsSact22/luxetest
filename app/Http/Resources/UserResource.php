<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function App\Helpers\CarbonParse;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => CarbonParse($this->email_verified_at),
            'role' => null,
            'created_at' => CarbonParse($this->created_at),
            'updated_at' => CarbonParse($this->updated_at),
        ];
    }
}
