<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_name',
        'description',
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

    public function companyProjects(){
        return $this->hasMany(CompanyProject::class);
    }
}
