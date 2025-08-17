<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class ContractorPackage extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'contractor_id',
        'package_id',
        'project_id',
    ];
}
