<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class BoqItem extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'boq_part_id',
        'project_id',
        'unit_id',
        'specification_no',
        'name',
        'code',
        'description',
        'is_active',
        'has_sub_items',
    ];

    public function boq_parts()
    {
        return $this->belongsTo(BoqPart::class, 'boq_part_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

}
