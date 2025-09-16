<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'department',
        'phone_number',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
