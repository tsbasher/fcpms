<?php

namespace App\Http\Controllers\Admin;

use App\Helper\PermittedPackage;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Package;
use App\Models\Region;
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
            $packages->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%")
                    ->orWhere('alias', 'ilike', "%{$search}%");
            });
        }
        if ($request->has('division_id') && !empty($request->division_id)) {
            $packages->where('division_id', $request->division_id);

            $districts = District::where('division_id', $request->division_id)->get();
        } else
            $districts = [];
        if ($request->has('region_id') && !empty($request->region_id)) {
            $packages->where('region_id', $request->region_id);
        }
        if ($request->has('district_id') && !empty($request->district_id)) {
            $packages->where('district_id', $request->district_id);
        }
        // Get all packages
        $packages = $packages->paginate();
        $divisions = Division::all();
        $regions =  Region::all();
        // dd($packages);
        return view('backend.admin.packages.index', compact('packages', 'divisions', 'regions', 'districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = [];
        $divisions = Division::all();
        $regions =  Region::all();
        return view('backend.admin.packages.create', compact('districts', 'divisions', 'regions'));
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
            'code' => 'required|string|max:50|unique:packages,code',
            'alias' => 'nullable|string|max:50',
            'division_id' => 'nullable|uuid|exists:divisions,id',
            'region_id' => 'nullable|uuid|exists:regions,id',
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
        $package = Package::create([
            'project_id' => Auth::guard('admin')->user()->project_id,
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'district_id' => $request->district_id,
            'division_id' => $request->division_id,
            'region_id' => $request->region_id,
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
        // Attach the current admin to the package
        $package->admins()->attach(Auth::guard('admin')->user()->id);
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
        $divisions = Division::all();
        $regions =  Region::all();
        $districts = District::where('division_id', $package->division_id)->get();
        $package = Package::permitted()->findOrFail($package->id);
        return view('backend.admin.packages.edit', compact('package', 'districts', 'divisions', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:packages,code,' . $package->id,
            'alias' => 'nullable|string|max:50',
            'division_id' => 'nullable|uuid|exists:divisions,id',
            'region_id' => 'nullable|uuid|exists:regions,id',
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
        $package = Package::permitted()->findOrFail($package->id);
        $package->update([
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'division_id' => $request->division_id,
            'region_id' => $request->region_id,
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
