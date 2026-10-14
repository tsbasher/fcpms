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
            $projectId = Auth::guard('admin')->user()->project_id;

            $packageQuery = $admin->packages()->where('is_active', 1);
            if ($projectId) {
                $packageQuery->where('packages.project_id', $projectId);
            }
            $packageIds = $packageQuery->get()->pluck('id')->toArray();

            $schemeQuery = Scheme::whereIn('package_id', $packageIds);
            if ($projectId) {
                $schemeQuery->where('schemes.project_id', $projectId);
            }
            return $schemeQuery->get()->pluck('id')->toArray();
        }
        return [];
    }
}
