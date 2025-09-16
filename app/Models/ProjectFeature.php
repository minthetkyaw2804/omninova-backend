<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFeature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
    ];

    public function featureImages(){
        return $this->hasMany(FeatureImage::class);
    }
    public function companyProject(){
        return $this->belongsTo(CompanyProject::class);
    }
}
