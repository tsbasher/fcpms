<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class AdminProject extends ExtendedModel
{
    //
    protected $keyType = 'string';
    public $incrementing = false;   
    protected $fillable = [
        
        'id',
        'admin_id',
        'project_id',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
