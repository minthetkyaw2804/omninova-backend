<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_feature_id',
        'image_name',
        'image_url',
    ];
    
    public function projectFeature(){
        return $this->belongsTo(ProjectFeature::class);
    }
}
