<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use App\Helper\ExtendedModelUser;
use Illuminate\Database\Eloquent\Model;

class Measurement extends ExtendedModelUser
{
    //
    
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
        'unit_id',
        'description',
        'nos',
        'length',
        'width',
        'height',
        'weight',
        'quantity',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
    public function bill_detail()
    {
        return $this->belongsTo(BillDetail::class);
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
    public function boq_part()
    {
        return $this->belongsTo(BoqPart::class);
    }
    public function boq_item()
    {
        return $this->belongsTo(BoqItem::class);
    }
    public function boq_subitem()
    {
        return $this->belongsTo(BoqSubItem::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
