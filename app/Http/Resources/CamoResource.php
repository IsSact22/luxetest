<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class CamoResource extends JsonResource
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
            'customer' => $this->customer,
            'owner' => $this->owner ? $this->owner->name : null,
            'contract' => $this->contract,
            'cam' => $this->cam ? $this->cam->name : null,
            'aircraft' => $this->aircraft ? new AircraftResource($this->aircraft) : null,
            'description' => $this->description,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'estimate_finish_date' => $this->estimate_finish_date ? $this->estimate_finish_date->format('Y-m-d') : null,
            'finish_date' => $this->finish_date ? $this->finish_date->format('Y-m-d') : null,
            'location' => $this->location,
            'activities' => $this->camoActivity,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d') : null,
        ];
    }
}
