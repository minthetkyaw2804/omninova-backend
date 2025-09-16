<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectTypeResource extends JsonResource
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
            'type_name' => $this->type_name,
            'description' => $this->description,
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at->format('d-m-y'),
            'updated_by' => $this->updater->name,
            'updated_at' => $this->updated_at->format('d-m-y'),
        ];
    }
}
