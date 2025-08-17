<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use App\Helper\PermittedPackage;
use App\Models\Scopes\PackageScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Package extends ExtendedModel
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'project_id',
        'name',
        'code',
        'alias',
        'division_id',
        'region_id',
        'district_id',
        'description',
        'bid_invitation_date',
        'bid_submission_date',
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

    public function scopePermitted($query)
    {
        $permittedPackages = PermittedPackage::getAdminPermittedPackages();
        return $query->whereIn('id', $permittedPackages);
    }
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_packages', 'package_id', 'admin_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
