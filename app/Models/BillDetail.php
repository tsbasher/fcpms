<?php

namespace App\Models;

use App\Helper\ExtendedModelUser;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends ExtendedModelUser
{
    //
    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'project_id',
        'bill_id',
        'scheme_id',
        'scheme_option_id',
        'boq_part_id',
        'boq_item_id',
        'boq_subitem_id',
        'quantity',
        'previous_quantity',
        'boq_quantity',
        'held_up_quantity',
        'this_bill_quantity',
        'this_bill_amount',
        'rate',
        'amount'

    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
    public function scheme_option()
    {
        return $this->belongsTo(SchemeOption::class);
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

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

}
