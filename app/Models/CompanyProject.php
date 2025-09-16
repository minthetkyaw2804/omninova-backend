<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'project_type_id',
        'description',
        'demo_url',
        'thumbnail_url',
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

    public function projectType(){
        return $this->belongsTo(ProjectType::class);
    }

    public function projectFeatures(){
        return $this->hasMany(ProjectFeature::class, 'project_id');
    }
}
