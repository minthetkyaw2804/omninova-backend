<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'created_by' => $this->creator->name,
            'updated_by' => $this->updater->name,
            'created_at' => $this->created_at->format('d-m-y'),
            'updated_at' => $this->updated_at->format('d-m-y'),
            'images' => $this->blogImages->map(function($image){
                return [
                    'id' => $image->id,
                    'image_name' => $image->image_name,
                    'image_url' => $image->image_url,
                ];
            }),
        ];
    }
}
