<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class LaborRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'rateable_id' => $this->rateable_id,
            'rateable_type' => $this->rateable_type,
            'rateable' => $this->rateable,
            'code' => $this->code,
            'name' => $this->name,
            'amount' => $this->amount,
        ];
    }
}
