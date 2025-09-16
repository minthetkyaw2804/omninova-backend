<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'description' => $this->description,
            'vision' => $this->vision,
            'goal' => $this->goal,
            'logo_url' => $this->logo_url,
            'founded_date' => $this->founded_date,
            'address' => $this->address,
            'updated_user_id' => $this->updated_user_id,
            'updated_user_name' => $this->updater->name,
            'updated_at' => $this->updated_at->format('d-m-y'),
            'social_media' => $this->companySocialMedias->map(function($social){
                return [
                    'id' => $social->id,
                    'platform_name' => $social->platform_name,
                    'page_url' => $social->page_url,
                    'created_at' => $social->created_at->format('d-m-y'),
                    'updated_at' => $social->updated_at->format('d-m-y'),
                ];
            }),
            'contacts' => $this->companyContacts->map(function($contact){
                return [
                    'id' => $contact->id,
                    'department' => $contact->department,
                    'phone_number' => $contact->phone_number,
                    'created_at' => $contact->created_at->format('d-m-y'),
                    'updated_at' => $contact->updated_at->format('d-m-y'),
                ];
            }),
        ];
    }
}
