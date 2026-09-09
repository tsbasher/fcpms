<?php

namespace App\Models;

use App\Helper\ExtendedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contractor extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_website',
        'company_reg_code',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'bank_account_no',
        'bank_name',
        'branch_name',
        'routing_number',
        'bank_address',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'contractor_packages', 'contractor_id', 'package_id')

            ->withPivot('project_id')
            ->with('project')
            ->wherePivot('project_id', Auth::guard('admin')->user()->project_id);
    }
}
