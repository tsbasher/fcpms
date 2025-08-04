<?php

namespace App\Helper;

use App\Models\Admin;
use App\Models\Scheme;
use Illuminate\Support\Facades\Auth;

class PermittedScheme
{
    /**
     * Get the permitted schemes for the authenticated admin.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAdminPermittedSchemes()
    {
        $admin = Admin::with('packages')->where('id', Auth::guard('admin')->user()->id)->first();
        if ($admin) {
            return Scheme::whereIn('package_id', $admin->packages()->where('is_active', 1)->get()->pluck('id')->toArray())->get()->pluck('id')->toArray();
        }
        return [];
    }
}
