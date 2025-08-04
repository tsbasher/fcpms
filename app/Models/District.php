<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'division_id',
        'name',
        'bn_name',
        'url',
    ];

    public function upazilas()
    {
        return $this->hasMany(Upazila::class, 'district_id', 'id');
    }
}
