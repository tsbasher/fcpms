<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Package;
use App\Models\Scheme;
use App\Models\SchemeOption;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schemes = Scheme::permitted();
        if ($request->has('search_text') && !empty($request->search_text)) {

            // Get schemes based on search criteria
            $schemes->where(function ($query) use ($request) {
                $query->where('name', 'ilike', "%{$request->search_text}%")
                    ->orWhere('code', 'ilike', "%{$request->search_text}%")
                    ->orWhere('alias', 'ilike', "%{$request->search_text}%");
            });
        }
        if ($request->has('division_id') && !empty($request->division_id)) {
            $schemes->where('division_id', $request->division_id);

            $districts = District::where('division_id', $request->division_id)->get();
        } else {
            $districts =  collect();
        }
        if ($request->has('district_id') && !empty($request->district_id)) {
            $schemes->where('district_id', $request->district_id);
            $upazilas = Upazila::where('district_id', $request->district_id)->get();
        } else {
            $upazilas =  collect();
        }
        if ($request->has('upazila_id') && !empty($request->upazila_id)) {
            $schemes->where('upazila_id', $request->upazila_id);
            $unions = Union::where('upazila_id', $request->upazila_id)->get();
        } else {
            $unions = collect();
        }
        if ($request->has('union_id') && !empty($request->union_id)) {
            $schemes->where('union_id', $request->union_id);
        }
        if ($request->has('package_id') && !empty($request->package_id)) {
            $schemes->where('package_id', $request->package_id);
        }

        // Get all schemes
        $schemes = $schemes->paginate();
        $divisions = Division::all();
        $packages = Package::permitted()->get();
        return view('backend.admin.schemes.index', compact('schemes', 'divisions', 'districts', 'upazilas', 'unions', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();
        $districts = [];
        $upazilas = [];
        $unions = [];
        $packages = Package::permitted()->get();
        $schemeOptions = SchemeOption::where('is_active', true)->get();
        return view('backend.admin.schemes.create', compact('divisions', 'districts', 'upazilas', 'unions', 'packages', 'schemeOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => false]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schemes,code',
            'alias' => 'nullable|string|max:50',
            'package_id' => 'required|exists:packages,id',
            'description' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'village_name' => 'nullable|string|max:100',
            'external_code' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'scheme_option_id' => 'nullable|exists:scheme_options,id',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'planned_budget' => 'nullable|numeric',
            'actual_budget' => 'nullable|numeric',
            'signing_date' => 'nullable|date',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $scheme = Scheme::create([
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'project_id' => Auth::guard('admin')->user()->project_id,
            'package_id' => $request->package_id,
            'description' => $request->description,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazila_id,
            'union_id' => $request->union_id,
            'village_name' => $request->village_name,
            'external_code' => $request->external_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'scheme_option_id' => $request->scheme_option_id,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'actual_start_date' => $request->actual_start_date,
            'actual_end_date' => $request->actual_end_date,
            'planned_budget' => $request->planned_budget,
            'actual_budget' => $request->actual_budget,
            'is_active' => $request->is_active,
        ]);
        return redirect()->route('admin.schemes.index')->with('success', 'Scheme created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scheme $scheme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scheme $scheme)
    {
        $divisions = Division::all();
        $districts = District::where('division_id',$scheme->division_id)->get();
        $upazilas = Upazila::where('district_id',$scheme->district->id)->get();
        $unions = Union::where('upazila_id',$scheme->upazila_id)->get();
        $packages = Package::permitted()->get();
        $schemeOptions = SchemeOption::where('is_active', true)->get();
        return view('backend.admin.schemes.edit', compact('scheme','divisions', 'districts', 'upazilas', 'unions', 'packages', 'schemeOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scheme $scheme)
    {if (!$request->has('is_active')) {
            $request->merge(['is_active' => false]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schemes,code,' . $scheme->id,
            'alias' => 'nullable|string|max:50',
            'package_id' => 'required|exists:packages,id',
            'description' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'village_name' => 'nullable|string|max:100',
            'external_code' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'scheme_option_id' => 'nullable|exists:scheme_options,id',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'planned_budget' => 'nullable|numeric',
            'actual_budget' => 'nullable|numeric',
            'signing_date' => 'nullable|date',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data =[
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'package_id' => $request->package_id,
            'description' => $request->description,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazila_id,
            'union_id' => $request->union_id,
            'village_name' => $request->village_name,
            'external_code' => $request->external_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'scheme_option_id' => $request->scheme_option_id,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'actual_start_date' => $request->actual_start_date,
            'actual_end_date' => $request->actual_end_date,
            'planned_budget' => $request->planned_budget,
            'actual_budget' => $request->actual_budget,
            'is_active' => $request->is_active,
        ];
        $scheme->update($data);
        return redirect()->route('admin.schemes.index')->with('success', 'Scheme created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scheme $scheme)
    {
        try {
            // Check if the scheme is associated with any other records
            $scheme->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Scheme deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting scheme: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Scheme.';
            return response()->json($data);
        }
    }
}
