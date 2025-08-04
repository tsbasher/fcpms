<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class Division extends ExtendedModel
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'bn_name',
        'url',
    ];

    public function district()
    {
        return $this->hasMany(District::class, 'division_id', 'id');
    }
}
