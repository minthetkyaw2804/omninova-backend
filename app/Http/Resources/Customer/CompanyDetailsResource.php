<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'about_us' => $this->description,
            'vision' => $this->vision,
            'goal' => $this->goal,
            'logo_url' => $this->logo_url,
            'founded_date' => $this->founded_date,
            'address' => $this->address,
            'social_media' => $this->companySocialMedias->map(function ($social){
                return [
                    'platform_name' => $social->platform_name,
                    'page_url' => $social->page_url, 
                ];
            }),
            'contacts' => $this->companyContacts->map(function($contact){
                return [
                    'department' => $contact->department,
                    'phone_number' => $contact->phone_number
                ];
            }),
        ];
    }
}
