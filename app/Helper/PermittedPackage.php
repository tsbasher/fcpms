<?php

namespace App\Helper;

use App\Models\Package;

class PermittedPackage
{
    /**
     * Get the active package ids for the current project.
     *
     * @return array
     */
    public static function getAdminPermittedPackages()
    {
        return Package::forCurrentProject()
            ->where('is_active', 1)
            ->pluck('id')
            ->toArray();
    }
}
