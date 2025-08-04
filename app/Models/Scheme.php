<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use App\Helper\PermittedPackage;
use App\Helper\PermittedScheme;
use Illuminate\Database\Eloquent\Model;

class Scheme extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;   
    protected $fillable=[
        'id',
        'name',
        'code',
        'alias',
        'project_id',
        'package_id',
        'description',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'village_name',
        'external_code',
        'latitude',
        'longitude',
        'scheme_option_id',
        'signing_date',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'planned_budget',
        'actual_budget',
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
    public function schemeOption()
    {
        return $this->belongsTo(SchemeOption::class, 'scheme_option_id', 'id');
    }
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id', 'id');
    }
    public function union()
    {
        return $this->belongsTo(Union::class, 'union_id', 'id');
    }
    
    public function scopePermitted($query)
    {
        $permittedSchemes = PermittedScheme::getAdminPermittedSchemes();
        return $query->whereIn('id', $permittedSchemes);
    }
}
