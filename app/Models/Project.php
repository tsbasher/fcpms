<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Project extends ExtendedModel
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'code',
        'short_name',
        'description',
        'approval_date',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'budget',
        'funded_by',
        'pd_name',
        'pd_contact_no',
        'pd_email',
        'ministry',
        'executing_agency',
        'consulting_agency',
        'is_active',
        'created_by',
        'updated_by'
    ];


    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_project', 'project_id', 'admin_id');
    }
}
