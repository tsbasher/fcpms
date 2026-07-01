<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\BoqSubItem;
use App\Models\BoqVersion;
use App\Models\BoqVersionDetails;
use App\Models\Package;
use App\Models\SchemeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $versions = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)
            ->get();
        return view('backend.admin.boq_versions.create', compact('packages', 'versions'));
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
        DB::transaction(function () use ($request) {
            $data = $request->only([
                'name',
                'version_date',
                'package_id',
                'description',
                'is_active',
            ]);
            $data['project_id'] = Auth::guard('admin')->user()->project_id;
            $new_version = BoqVersion::create($data);

            if ($request->has('boq_version_id') && !empty($request->boq_version_id)) {

                $boq_version_details = BoqVersionDetails::where('boq_version_id', $request->boq_version_id)->get();
                foreach ($boq_version_details as $detail) {
                    $d = $detail->toArray();
                    $d['id'] = null;
                    $d['boq_version_id'] = $new_version->id;
                    $d['package_id'] = $new_version->package_id;
                    BoqVersionDetails::create($d);
                }


            }
        });
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

    public function getBoqVersionsByPackage($package_id)
    {
        if ($package_id) {
            $boqVersions = BoqVersion::where('package_id', $package_id)
                ->where('project_id', Auth::guard('admin')->user()->project_id)
                ->get();
            return response()->json($boqVersions);
        }
        return response()->json([]);
    }

    public function export(Request $request)
    {
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();
        $boq_versions = [];
        return view('backend.admin.boq_versions.export', compact('packages', 'boq_versions'));
    }
    
    public function export_data(Request $request)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'boq_version_id' => 'required|exists:boq_versions,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }


        $boq_version = BoqVersion::find($request->get('boq_version_id'));
        $part_list = BoqVersionDetails::where('boq_version_id', $request->get('boq_version_id'))
            ->where('package_id', $request->get('package_id'))
            ->distinct('boq_part_id')
            ->get()->pluck('boq_part_id')->toArray();
        $boq_parts = BoqPart::whereIn('id', $part_list)->orderby('code')->get();
        $package = Package::find($request->get('package_id'));
        $details = [];
        // dd($boq_parts);
        foreach ($boq_parts as $boq_part) {
            $part_data = new stdClass();
            $part_data->boq_part = $boq_part;
            $part_data->items = [];
            $item_list = BoqVersionDetails::where('boq_version_id', $request->get('boq_version_id'))
                ->where('package_id', $request->get('package_id'))
                ->where('boq_part_id', $boq_part->id)
                ->distinct('boq_item_id')
                ->get()->pluck('boq_item_id')->toArray();
            $boq_items = BoqItem::whereIn('id', $item_list)->orderByRaw("
                            regexp_replace(code, '[0-9.]+', '', 'g') ASC,
                            COALESCE(NULLIF(regexp_replace(boq_items.code, '[^0-9]', '', 'g'), ''), '0')::int ASC")->get();

            foreach ($boq_items as $boq_item) {
                $item_data = new stdClass();
                $item_data->boq_item = $boq_item;
                if ($boq_item->has_sub_items) {

                    $sub_item_list = BoqVersionDetails::where('boq_version_id', $request->get('boq_version_id'))
                        ->where('package_id', $request->get('package_id'))
                        ->where('boq_part_id', $boq_part->id)
                        ->where('boq_item_id', $boq_item->id)
                        ->distinct('boq_sub_item_id')
                        ->get()->pluck('boq_sub_item_id')->toArray();
                    $boq_sub_items = BoqSubItem::whereIn('id', $sub_item_list)->with('unit')
                        ->orderByRaw("
        regexp_replace(code, '[0-9]+', '', 'g') ASC,
        COALESCE(NULLIF(regexp_replace(code, '[^0-9]', '', 'g'), ''),'0')::int ASC")
                        ->get();
                    $item_data->boq_sub_items = $boq_sub_items;
                }
                array_push($part_data->items, $item_data);
            }



            // $part_data->boq_version_details = $data;
            array_push($details, $part_data);
        }

        $boq_version_details = BoqVersionDetails::where('boq_version_id', $request->get('boq_version_id'))
            ->where('package_id', $request->get('package_id'))->get();
        $options = SchemeOption::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        // dd($details);
        $project = Auth::guard('admin')->user()->project;
        return view('backend.admin.boq_versions.export_data', compact('boq_version', 'boq_version_details', 'details', 'package', 'options', 'project'));
    }
}
