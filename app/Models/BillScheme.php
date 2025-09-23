<?php

namespace App\Models;

use App\Helper\ExtendedModelUser;
use Illuminate\Database\Eloquent\Model;

class BillScheme extends ExtendedModelUser

{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'bill_id',
        'scheme_id',
    ];
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id', 'id');
    }
}
