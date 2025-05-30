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
        return [
            'id' => $this->id,
            'camo_id' => $this->camo_id,
            'camo' => $this->camo,
            'labor_rate_id' => $this->labor_rate_id,
            'labor_rate_value_id' => $this->labor_rate_value_id,
            'required' => $this->required,
            'date' => $this->date ? $this->date->format('Y-m-d') : null,
            'name' => $this->name,
            'description' => $this->description,
            'estimate_time' => $this->estimate_time,
            'started_at' => $this->started_at ? $this->started_at->format('Y-m-d H:i') : null,
            'completed_at' => $this->completed_at ? $this->completed_at->format('Y-m-d H:i') : null,
            'status' => $this->status,
            'comments' => $this->comments,
            'special_rate' => $this->get_special_rate,
            'labor_mount' => $this->labor_mount,
            'material_mount' => $this->material_mount,
            'material_information' => $this->material_information,
            'awr' => $this->awr,
            'approval_status' => $this->approval_status,
            'priority' => $this->priority,
            'missing_rate_value' => $this->missing_rate_value,
            'missing_rate_name' => $this->when($this->missing_rate_value, $this->missing_rate_name),
            'images' => $this->images,
        ];
    }
}
