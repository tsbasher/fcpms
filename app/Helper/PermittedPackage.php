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
            $query = $admin->packages()->where('is_active', 1);
            $projectId = Auth::guard('admin')->user()->project_id;
            if ($projectId) {
                $query->where('packages.project_id', $projectId);
            }
            return $query->get()->pluck('id')->toArray();
        }
        return [];
    }
}
