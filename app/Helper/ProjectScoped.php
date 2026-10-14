<?php

namespace App\Helper;

use Illuminate\Support\Facades\Auth;

trait ProjectScoped
{
    /**
     * Constrain the query to the currently logged-in admin's active project.
     *
     * When the admin has no active project (e.g. a Super Admin who logged in
     * without selecting one), no constraint is applied and all rows are
     * returned.
     */
    public function scopeForCurrentProject($query, string $column = 'project_id')
    {
        $projectId = Auth::guard('admin')->user()->project_id ?? null;

        if ($projectId) {
            $query->where($query->getModel()->getTable().'.'.$column, $projectId);
        }

        return $query;
    }
}
