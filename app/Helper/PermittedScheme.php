<?php

namespace App\Helper;

use App\Models\Scheme;

class PermittedScheme
{
    /**
     * Get the scheme ids for the current project.
     *
     * @return array
     */
    public static function getAdminPermittedSchemes()
    {
        return Scheme::forCurrentProject()
            ->pluck('id')
            ->toArray();
    }
}
