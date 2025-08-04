<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqVersion;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class BoqVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $boq_versions = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)
            ->with('package');

        if ($request->has('search_text')) {
            $boq_versions->where('name', 'like', '%' . $request->get('search_text') . '%');
        }

        if ($request->has('package_id')) {
            $boq_versions->where('package_id', $request->get('package_id'));
        }

        $boq_versions = $boq_versions->paginate(10);
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();

        return view('backend.admin.boq_versions.index', compact('boq_versions', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();
        return view('backend.admin.boq_versions.create', compact('packages'));
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
            'version_date' => 'required|date',
            'package_id' => 'required|exists:packages,id',
            'description' => 'nullable|string|max:1000',
            'version_date' => 'nullable|date',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only([
            'name',
            'version_date',
            'package_id',
            'description',
            'is_active',
        ]);
        $data['project_id'] = Auth::guard('admin')->user()->project_id;
        BoqVersion::create($data);
        return redirect()->route('admin.boq_versions.index')->with('success', 'BOQ Version created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqVersion $boqVersion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoqVersion $boqVersion)
    {
        $boq_version = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqVersion->id);
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();
        return view('backend.admin.boq_versions.edit', compact('boq_version', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoqVersion $boqVersion)
    {
        $boq_version = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqVersion->id);

        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'description' => 'nullable|string|max:1000',
            'version_date' => 'nullable|date',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only([
            'name',
            'package_id',
            'description',
            'version_date',
            'is_active',
        ]);
        $boq_version->update($data);
        return redirect()->route('admin.boq_versions.index')->with('success', 'BOQ Version updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqVersion $boqVersion)
    {


        try {

            $boq_version = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqVersion->id);
            // Check if the BOQ Version is associated with any other records
            $boq_version->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Version deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting BOQ Version: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting BOQ Version.';
            return response()->json($data);
        }
    }
}
