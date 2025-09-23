<?php

namespace App\Models;

use App\Helper\ExtendedModelUser;
use Illuminate\Database\Eloquent\Model;

class Bill extends ExtendedModelUser
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'contractor_id',
        'project_id',
        'boq_version_id',
        'bill_no',
        'bill_date',
        'reference_code',
        'name',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function boq_version()
    {
        return $this->belongsTo(BoqVersion::class, 'boq_version_id', 'id');
    }
    public function schemes()
    {
        return $this->belongsToMany(Scheme::class, 'bill_schemes', 'bill_id', 'scheme_id');
    }
    public function bill_parts()
    {
        return $this->belongsToMany(BoqPart::class, 'bill_parts', 'bill_id', 'boq_part_id')->with('scheme');
    }
}
