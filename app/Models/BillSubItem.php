<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillSubItem extends Model
{
    protected $fillable = [
        'bill_id',
        'scheme_id',
        'boq_part_id',
        'boq_item_id',
        'boq_subitem_id',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
    public function boq_part()
    {
        return $this->belongsTo(BoqPart::class, 'boq_part_id', 'id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id', 'id');  
    }
    public function boq_item()
    {
        return $this->belongsTo(BoqItem::class, 'boq_item_id', 'id');
    }
    public function boq_subitem()
    {
        return $this->belongsTo(BoqSubItem::class, 'boq_subitem_id', 'id');
    }
}
