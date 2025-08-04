<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [ 
        'id',
        'upazila_id',
        'name',
        'bn_name',
        'url',
    ];
}
