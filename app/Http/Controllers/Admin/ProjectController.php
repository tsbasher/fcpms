<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::query();
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            // Get projects based on search criteria
            $projects->where('name', 'ilike', "%{$search}%")->orWhere('code', 'ilike', "%{$search}%")->orWhere('short_name', 'ilike', "%{$search}%");
        }
        // Get all projects
        $projects = $projects->paginate();
        return view('backend.admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.projects.create');
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
            'code' => 'required|string|max:50|unique:projects,code',
            'short_name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'approval_date' => 'nullable|date',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'funded_by' => 'nullable|string|max:255',
            'pd_name' => 'nullable|string|max:255',
            'pd_contact_no' => 'nullable|string|max:20',
            'pd_email' => 'nullable|email|max:255',
            'ministry' => 'nullable|string|max:255',
            'executing_agency' => 'nullable|string|max:255',
            'consulting_agency' => 'nullable|string|max:255',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        // Create a new project
        Project::create($request->all());

        // Redirect to the projects index with success message
        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('backend.admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects,code,' . $project->id,
            'short_name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'approval_date' => 'nullable|date',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'funded_by' => 'nullable|string|max:255',
            'pd_name' => 'nullable|string|max:255',
            'pd_contact_no' => 'nullable|string|max:20',
            'pd_email' => 'nullable|email|max:255',
            'ministry' => 'nullable|string|max:255',
            'executing_agency' => 'nullable|string|max:255',
            'consulting_agency' => 'nullable|string|max:255',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        // Update the project
        $project->update($request->all());

        // Redirect to the projects index with success message
        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            // Check if the project is associated with any other records
            $project->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Project deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Project.';
            return response()->json($data);
        }
    }
}
