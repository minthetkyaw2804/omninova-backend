<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_type_id' => $this->project_type_id,
            'project_type' => $this->projectType->type_name,
            'description' => $this->description,
            'demo_url' => $this->demo_url,
            'thumbnail_url' => $this->thumbnail_url,
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at->format('d-m-y'),
            'updated_by' => $this->updater->name,
            'updated_at' => $this->updated_at->format('d-m-y'),
            'project_features' => $this->projectFeatures->count() > 0 ? $this->projectFeatures->map(function($feature){
                return [
                    'id' => $feature->id,
                    'title' => $feature->title,
                    'description' => $feature->description,
                    'images' => $feature->featureImages->count() > 0 ? $feature->featureImages->map(function($image){
                        return [
                            'id' => $image->id,
                            'image_name' => $image->image_name,
                            'image_url' => $image->image_url,
                        ];
                    }) : [],
                ];
            }) : [],
        ];
    }
}
