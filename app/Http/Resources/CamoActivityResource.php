<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class CamoActivityResource extends JsonResource
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
            'camo_id' => $this->camo_id,
            'camo' => $this->camo,
            'labor_rate_id' => $this->labor_rate_id,
            'required' => $this->required,
            'date' => $this->date->format('Y-m-d'),
            'name' => $this->name,
            'description' => $this->description,
            'estimate_time' => $this->estimate_time,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'status' => $this->status,
            'comments' => $this->comments,
            'labor_mount' => $this->labor_mount,
            'material_mount' => $this->material_mount,
            'material_information' => $this->material_information,
            'awr' => $this->awr,
            'approval_status' => $this->approval_status,
            'priority' => $this->priority,
            'images' => $this->images,
        ];
    }
}
