<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class BoqPart extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'boq_part_id',
        'boq_item_id',
        'name',
        'code',
        'description',
        'is_active',
        'project_id',
        'created_by',
        'updated_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function boq_part()
    {
        return $this->belongsTo(BoqPart::class, 'boq_part_id', 'id');
    }
    public function boq_item()
    {
        return $this->belongsTo(BoqItem::class, 'boq_item_id', 'id');
    }
    
}
