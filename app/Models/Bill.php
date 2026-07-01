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
        'measurement_from_date',
        'measurement_to_date',
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
        return $this->belongsToMany(Scheme::class, 'bill_schemes', 'bill_id', 'scheme_id')->with('package','district','upazila','union');
    }
    
    public function bill_scheme()
    {
        return $this->hasMany(BillScheme::class, 'bill_id', 'id')->with('scheme');
    }
    public function bill_parts()
    {
        return $this->hasMany(BillPart::class, 'bill_id', 'id')->with('scheme', 'boq_part');
    }
    public function bill_items()
    {
        return $this->hasMany(BillItem::class, 'bill_id', 'id')->with('scheme', 'boq_part','boq_item');
    }
    public function bill_subitems()
    {
        return $this->hasMany(BillSubItem::class, 'bill_id', 'id')->with('scheme', 'boq_part','boq_item','boq_subitem');
    }
    public function bill_details()
    {
        return $this->hasMany(BillDetail::class,'bill_id','id');
    }
}
