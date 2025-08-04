<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class AdminPackage extends ExtendedModel
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'admin_id',
        'package_id',
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
}
