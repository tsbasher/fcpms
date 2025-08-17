<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class BoqVersionDetails extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'project_id',
        'package_id',
        'boq_version_id',
        'boq_part_id',
        'boq_item_id',
        'boq_sub_item_id',
        'scheme_option_id',
        'unit_id',
        'quantity',
        'rate',
        'created_by',
        'updated_by',
    ];

    public function boq_version()
    {
        return $this->belongsTo(BoqVersion::class, 'boq_version_id', 'id');
    }
    public function boq_part()
    {
        return $this->belongsTo(BoqPart::class, 'boq_part_id', 'id');
    }
    public function boq_item()
    {
        return $this->belongsTo(BoqItem::class, 'boq_item_id', 'id');
    }
    public function boq_sub_item()
    {
        return $this->belongsTo(BoqSubItem::class, 'boq_sub_item_id', 'id');
    }
    public function scheme_option()
    {
        return $this->belongsTo(SchemeOption::class, 'scheme_option_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
