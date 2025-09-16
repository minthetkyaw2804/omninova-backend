<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
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
            'project_type' => $this->projectType->type_name,
            'description' => $this->description,
            'demo_url' => $this->demo_url,
            'highlight_features' => $this->projectFeatures->map(function ($feature){
                return [
                    'id' => $feature->id,
                    'title' => $feature->title,
                    'description' => $feature->description,
                    'images' => $feature->featureImages->map(function($image){
                        return [
                            'image_name' => $image->image_name,
                            'image_url' => $image->image_url,
                        ];
                    }),
                ];
            }),
        ];
    }
}
