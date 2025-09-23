<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPart extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'bill_id',
        'scheme_id',
        'boq_part_id'
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
}
