<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'description',
        'vision',
        'goal',
        'logo_url',
        'founded_date',
        'address',
        'creator_user_id',
        'updated_user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function companySocialMedias()
    {
        return $this->hasMany(CompanySocialMedia::class, 'company_id');
    }
    public function companyContacts()
    {
        return $this->hasMany(CompanyContact::class);
    }
}
