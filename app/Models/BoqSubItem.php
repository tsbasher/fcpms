<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class BoqSubItem extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'boq_part_id',
        'boq_item_id',
        'project_id',
        'unit_id',
        'name',
        'code',
        'specification_no',
        'description',
        'is_active',
    ];

    public function boq_part()
    {
        return $this->belongsTo(BoqPart::class, 'boq_part_id', 'id');
    }

    public function boq_item()
    {
        return $this->belongsTo(BoqItem::class, 'boq_item_id', 'id');
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
