<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementDetails extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'project_id',
        'bill_id',
        'bill_detail_id',
        'scheme_id',
        'boq_part_id',
        'boq_item_id',
        'boq_subitem_id',
        'description',
        'unit_id',
        'measurement_id',
        'dia',
        'spacing',
        'rebar_nos',
        'rebar_length',
        'unit_weight',
        'quantity_per_pile',
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }
    
    
}
