<?php

namespace App\Models;

use App\Helper\ExtendedModel;

class BoqVersion extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'project_id',
        'package_id',
        'name',
        'version_date',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function boq_version_details()
    {
        return $this->hasMany(BoqVersionDetails::class, 'boq_version_id', 'id');
    }
}
