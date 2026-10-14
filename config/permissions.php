<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules
    |--------------------------------------------------------------------------
    | Each module gets the actions listed below (view/create/edit/delete)
    | as permissions in the form "{module}.{action}".
    */

    'modules' => [
        'projects' => 'Projects',
        'regions' => 'Regions',
        'packages' => 'Packages',
        'scheme_options' => 'Scheme Options',
        'document_types' => 'Document Types',
        'schemes' => 'Schemes',
        'units' => 'Units',
        'boq_parts' => 'BOQ Parts',
        'boq_items' => 'BOQ Items',
        'boq_sub_items' => 'BOQ Sub Items',
        'boq_versions' => 'BOQ Versions',
        'boq_version_details' => 'BOQ Versions Details',
        'contractors' => 'Contractors',
        'contractor_users' => 'Contractor Users',
        'admin_users' => 'Admin Users',
        'bills' => 'Bills',
        'audit_logs' => 'Audit Logs',
        'roles' => 'Roles & Permissions',
    ],

    'actions' => [
        'view' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    | Slug of the role that bypasses every permission check.
    */

    'super_admin_slug' => 'super-admin',

    /*
    |--------------------------------------------------------------------------
    | Always Allowed Routes
    |--------------------------------------------------------------------------
    | Route names that are never blocked by the permission middleware.
    */

    'always_allowed' => [
        'admin.home',
        'admin.profile.index',
        'admin.profile.update',
        'admin.logout',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Map
    |--------------------------------------------------------------------------
    | Explicit required-permission for routes whose name does not resolve
    | automatically to "{module}.{action}".
    */

    'route_map' => [
        'admin.check_existing_boq_version_details' => 'boq_version_details.view',
        'admin.boq_version_details.copy' => 'boq_version_details.create',
        'admin.boq_version_export_import.index' => 'boq_versions.view',
        'admin.boq_version_export_import.export' => 'boq_versions.view',
        'admin.boq_versions.export' => 'boq_versions.view',
        'admin.boq_versions.export_data' => 'boq_versions.view',
        'admin.contractors.add_package' => 'contractors.edit',
        'admin.contractors.store_package' => 'contractors.edit',
        'admin.bills.get_bills_by_package' => 'bills.view',
        'admin.bills.shelter_wise_view' => 'bills.view',
        'admin.bills.documents' => 'bills.view',
        'admin.get_scheme_bu_upazila' => 'schemes.view',
        'admin.admin_users.unlock' => 'admin_users.edit',
        'admin.admin_users.toggle_active' => 'admin_users.edit',
        'admin.admin_users.assign_project' => 'admin_users.edit',
        'admin.contractor_users.unlock' => 'contractor_users.edit',
        'admin.contractor_users.toggle_active' => 'contractor_users.edit',
    ],

];
