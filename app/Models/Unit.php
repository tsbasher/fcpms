<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class Unit extends ExtendedModel
{
    //
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'fields',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
