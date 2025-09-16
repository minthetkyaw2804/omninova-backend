<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySocialMedia extends Model
{   
    use SoftDeletes;
    protected $table = 'company_social_medias';
    protected $fillable = [
        'company_id',
        'platform_name',
        'page_url',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
