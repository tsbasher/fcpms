<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admins = Admin::with(['projects', 'role']);
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $admins->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }
        $admins = $admins->paginate();
        $projects = Project::orderBy('name')->get();
        return view('backend.admin.admin_users.index', compact('admins', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', 1)->orderBy('name')->get();
        return view('backend.admin.admin_users.create', compact('roles'));
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
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'email', 'phone', 'is_active', 'role_id']);
        $data['password'] = Hash::make($request->password);
        $data['must_change_password'] = $request->boolean('must_change_password');
        Admin::create($data);
        return redirect()->route('admin.admin_users.index')->with('success', 'Admin User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::with('projects')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('backend.admin.admin_users.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'email', 'phone', 'is_active', 'role_id']);
        $data['must_change_password'] = $request->boolean('must_change_password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        return redirect()->route('admin.admin_users.index')->with('success', 'Admin User updated successfully.');
    }

    /**
     * Assign projects to an admin user. Only a Super Admin may do this.
     */
    public function assignProject(Request $request, string $id)
    {
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            abort(403, 'Only a Super Admin can assign projects to an admin user.');
        }

        $admin = Admin::findOrFail($id);

        $v = Validator::make($request->all(), [
            'project_ids' => 'required|array|min:1',
            'project_ids.*' => 'exists:projects,id',
        ], [
            'project_ids.required' => 'Please select at least one project.',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $project_ids = $request->input('project_ids', []);
        $admin->projects()->sync($project_ids);

        $firstProject = Project::find($project_ids[0] ?? null);
        if ($firstProject) {
            $admin->project_id = $firstProject->id;
            $admin->project_code = $firstProject->code;
            $admin->save();
        }

        return redirect()->route('admin.admin_users.index')->with('success', 'Projects assigned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            if ($admin->id === Auth::guard('admin')->id()) {
                $data = new stdClass();
                $data->status = 0;
                $data->message = 'You cannot delete your own account.';
                return response()->json($data);
            }
            $admin->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Admin User deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting admin user: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Admin User.';
            return response()->json($data);
        }
    }

    public function toggleActive(string $id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }
        $admin->is_active = $admin->is_active == 1 ? 0 : 1;
        $admin->save();
        return redirect()->back()->with('success', $admin->is_active == 1 ? 'Admin User activated successfully.' : 'Admin User deactivated successfully.');
    }

    public function unlock(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->is_locked = 0;
        $admin->login_attempts = 0;
        $admin->locked_until = null;
        $admin->save();
        return redirect()->back()->with('success', 'Admin User unlocked successfully.');
    }
}