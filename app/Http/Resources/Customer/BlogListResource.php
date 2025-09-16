<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
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
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at->format('d-m-y'),
            'thumbnail_image' => optional($this->blogImages->first())->image_url,
        ];
    }
}
