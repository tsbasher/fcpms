<?php

namespace App\Http\Controllers\Admin;

use App\Helper\PermittedPackage;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted();
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            // Get packages based on search criteria
            $packages->where('name', 'ilike', "%{$search}%")->orWhere('code', 'ilike', "%{$search}%")->orWhere('alias', 'ilike', "%{$search}%");
        }
        // Get all packages
        $packages = $packages->paginate();
        // dd($packages);
        return view('backend.admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::all();
        return view('backend.admin.packages.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:packages,code',
            'alias' => 'nullable|string|max:50',
            'district_id' => 'nullable|uuid|exists:districts,id',
            'description' => 'nullable|string',
            'bid_invitation_date' => 'nullable|date',
            'bid_submission_date' => 'nullable|date',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'planned_budget' => 'nullable|string|max:255',
            'actual_budget' => 'nullable|string|max:255',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $package=Package::create([
            'project_id'=>Auth::guard('admin')->user()->project_id,
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'district_id' => $request->district_id,
            'description' => $request->description,
            'bid_invitation_date' => $request->bid_invitation_date,
            'bid_submission_date' => $request->bid_submission_date,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'actual_start_date' => $request->actual_start_date,
            'actual_end_date' => $request->actual_end_date,
            'planned_budget' => $request->planned_budget,
            'actual_budget' => $request->actual_budget,
            'is_active' => $request->is_active,
        ]);
        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $districts = District::all();
        $package=Package::permitted()->findOrFail($package->id);
        return view('backend.admin.packages.edit', compact('package', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:packages,code,'.$package->id,
            'alias' => 'nullable|string|max:50',
            'district_id' => 'nullable|uuid|exists:districts,id',
            'description' => 'nullable|string',
            'bid_invitation_date' => 'nullable|date',
            'bid_submission_date' => 'nullable|date',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'planned_budget' => 'nullable|string|max:255',
            'actual_budget' => 'nullable|string|max:255',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $package->update([
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'district_id' => $request->district_id,
            'description' => $request->description,
            'bid_invitation_date' => $request->bid_invitation_date,
            'bid_submission_date' => $request->bid_submission_date,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'actual_start_date' => $request->actual_start_date,
            'actual_end_date' => $request->actual_end_date,
            'planned_budget' => $request->planned_budget,
            'actual_budget' => $request->actual_budget,
            'is_active' => $request->is_active,
        ]);
        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            // Check if the package is associated with any other records
            $package->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Package deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting package: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Package.';
            return response()->json($data);
        }
    }
}
