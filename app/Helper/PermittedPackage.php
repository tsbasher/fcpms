<?php

namespace App\Helper;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class PermittedPackage
{
    /**
     * Get the permitted packages for the authenticated admin.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAdminPermittedPackages()
    {
        $admin = Admin::with('packages')->where('id', Auth::guard('admin')->user()->id)->first();
        if ($admin) {
            return $admin->packages()->where('is_active', 1)->get()->pluck('id')->toArray();
        }
        return [];
    }
}
