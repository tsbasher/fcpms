<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'district_id',
        'name',
        'bn_name',
        'url',
    ];
    public function unions()
    {
        return $this->hasMany(Union::class, 'upazila_id', 'id');
    }
}
