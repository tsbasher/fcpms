<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = config('permissions.modules', []);
        $actions = config('permissions.actions', []);

        foreach ($modules as $module => $label) {
            foreach ($actions as $action => $actionLabel) {
                Permission::firstOrCreate(
                    ['slug' => "{$module}.{$action}"],
                    [
                        'name' => "{$actionLabel} {$label}",
                        'module' => $module,
                        'action' => $action,
                    ]
                );
            }
        }

        $role = Role::firstOrCreate(
            ['slug' => config('permissions.super_admin_slug', 'super-admin')],
            [
                'name' => 'Super Admin',
                'description' => 'Has full access to every module.',
                'is_super_admin' => true,
                'is_active' => true,
            ]
        );

        $role->permissions()->sync(Permission::pluck('id')->all());

        \App\Models\Admin::whereNull('role_id')->update(['role_id' => $role->id]);
    }
}