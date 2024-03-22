<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
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
            'owner' => $this->owner->name,
            'contract' => $this->contract,
            'cam' => $this->cam->name,
            'aircraft' => $this->aircraft,
            'activities' => Route::is('camos.show') ? $this->activities : $this->activities->count(),
            'description' => $this->description,
            'start_date' => $this->start_date->format('Y-m-d'),
            'finish_date' => $this->finish_date->format('Y-m-d'),
            'location' => $this->location,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
