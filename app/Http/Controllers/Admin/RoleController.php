<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::withCount(['permissions', 'admins']);
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $roles->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }
        $roles = $roles->orderByDesc('is_super_admin')->paginate();
        return view('backend.admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        return view('backend.admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'is_super_admin' => false,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        return view('backend.admin.roles.edit', compact('role', 'permissions', 'assignedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (!$role->is_super_admin) {
            $role->permissions()->sync($request->input('permissions', []));
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::withCount('admins')->findOrFail($id);

            if ($role->is_super_admin) {
                $data = new stdClass();
                $data->status = 0;
                $data->message = 'The Super Admin role cannot be deleted.';
                return response()->json($data);
            }

            if ($role->admins_count > 0) {
                $data = new stdClass();
                $data->status = 0;
                $data->message = 'This role is assigned to one or more admin users and cannot be deleted.';
                return response()->json($data);
            }

            $role->permissions()->detach();
            $role->delete();

            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Role deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting the role.';
            return response()->json($data);
        }
    }

    /**
     * Build a unique slug from the given name.
     */
    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Role::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }
}