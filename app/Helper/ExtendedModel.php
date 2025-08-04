<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ExtendedModel extends Model
{
    protected $perPage = Constants::PAGINATION_PER_PAGE;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            // dd(array_search('created_by2', $model->getFillable()));
            if (array_search('created_by', $model->getFillable()) && empty($model->created_by)) {
                $model->created_by = Auth::guard('admin')->user()->id ?? null;
            }
        });
        static::updating(function ($model) {
            if (array_key_exists('updated_by', $model->getFillable()) && empty($model->updated_by)) {
                $model->updated_by = Auth::guard('admin')->user()->id ?? null;
            }
        });
    }
}