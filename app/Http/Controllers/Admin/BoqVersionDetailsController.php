<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\BoqSubItem;
use App\Models\BoqVersion;
use App\Models\BoqVersionDetails;
use App\Models\Package;
use App\Models\Scheme;
use App\Models\SchemeOption;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class BoqVersionDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $boq_version_details = BoqVersionDetails::where('project_id', Auth::guard('admin')->user()->project_id)
            ->with(['boq_version', 'boq_part', 'boq_item', 'boq_sub_item', 'scheme_option', 'unit', 'package']);
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        if ($request->has('package_id') && !empty($request->get('package_id'))) {
            $boq_version_details->where('package_id', $request->get('package_id'));
            $boq_versions = BoqVersion::where('package_id', $request->get('package_id'))
                ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        } else {
            $boq_versions = [];
        }

        if ($request->has('boq_version_id') && !empty($request->get('boq_version_id'))) {
            $boq_version_details->where('boq_version_id', $request->get('boq_version_id'));
        }
        if ($request->has('boq_part_id') && !empty($request->get('boq_part_id'))) {
            $boq_version_details->where('boq_part_id', $request->get('boq_part_id'));
            $boq_items = BoqItem::where('boq_part_id', $request->get('boq_part_id'))
                ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        } else {
            $boq_items = [];
        }
        if ($request->has('boq_item_id') && !empty($request->get('boq_item_id'))) {
            $boq_version_details->where('boq_item_id', $request->get('boq_item_id'));
            $boq_sub_items = BoqSubItem::where('boq_item_id', $request->get('boq_item_id'))
                ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        } else {
            $boq_sub_items = [];
        }

        $boq_version_details = $boq_version_details->paginate();

        return view('backend.admin.boq_version_details.index', compact('boq_version_details', 'packages', 'boq_versions', 'boq_parts', 'boq_items', 'boq_sub_items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $boq_version = BoqVersion::find($request->get('boq_version_id'));
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();

$boq_version_detail=[];
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get();

        $boq_items = [];
        $boq_sub_items = [];
        $boq_versions = [];
        $scheme_options = SchemeOption::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $units = Unit::get();
        return view('backend.admin.boq_version_details.create', compact('boq_version', 'boq_parts', 'packages', 'boq_items', 'boq_sub_items', 'boq_versions', 'scheme_options', 'units',"boq_version_detail"));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'boq_version_id' => 'required|exists:boq_versions,id',
            'package_id' => 'required|exists:packages,id',
            'boq_part_id' => 'required|exists:boq_parts,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'boq_sub_item_id' => 'nullable|exists:boq_sub_items,id',
            'scheme_option_id' => 'required|exists:scheme_options,id',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
        ]);
        if ($v->fails()) {
            return response()->json(['status' => 0, 'message' => $v->errors()->first()]);
        }

        $exist = $this->existingBoqVersionDetails($request);
        if ($exist->getData()->status == 1) {
            BoqVersionDetails::where('id', $exist->getData()->data[0]->id)->update([
                'boq_version_id' => $request->get('boq_version_id'),
                'package_id' => $request->get('package_id'),
                'boq_part_id' => $request->get('boq_part_id'),
                'boq_item_id' => $request->get('boq_item_id'),
                'boq_sub_item_id' => $request->get('boq_sub_item_id'),
                'scheme_option_id' => $request->get('scheme_option_id'),
                'unit_id' => $request->get('unit_id'),
                'quantity' => $request->get('quantity'),
                'rate' => $request->get('rate')
            ]);
            
        } else {
            $data = $request->only([
                'boq_version_id',
                'package_id',
                'boq_part_id',
                'boq_item_id',
                'boq_sub_item_id',
                'scheme_option_id',
                'unit_id',
                'quantity',
                'rate'

            ]);
            $data['project_id'] = Auth::guard('admin')->user()->project_id;

            $details=BoqVersionDetails::create($data);

        }
            return redirect()->route('admin.boq_version_details.index')->with('success', 'Boq Version Details Created Successfully.');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqVersionDetails $boqVersionDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $boq_version_detail = BoqVersionDetails::find($id);
        $boq_version = BoqVersion::find($boq_version_detail->boq_version_id);
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();

        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get();

        $boq_items = BoqItem::where('boq_part_id', $boq_version_detail->boq_part_id)
            ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $boq_sub_items = BoqSubItem::where('boq_item_id', $boq_version_detail->boq_item_id)
            ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $boq_versions = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)
        ->where('package_id', $boq_version_detail->package_id)->get();
        $scheme_options = SchemeOption::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $units = Unit::get();
        return view('backend.admin.boq_version_details.edit', compact('boq_version', 'boq_parts', 'packages', 'boq_items', 'boq_sub_items', 'boq_versions', 'scheme_options', 'units', "boq_version_detail"));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'boq_version_id' => 'required|exists:boq_versions,id',
            'package_id' => 'required|exists:packages,id',
            'boq_part_id' => 'required|exists:boq_parts,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'boq_sub_item_id' => 'nullable|exists:boq_sub_items,id',
            'scheme_option_id' => 'required|exists:scheme_options,id',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $boq_version_detail = BoqVersionDetails::find($id);
        
            $data = $request->only([
                'boq_version_id',
                'package_id',
                'boq_part_id',
                'boq_item_id',
                'boq_sub_item_id',
                'scheme_option_id',
                'unit_id',
                'quantity',
                'rate'

            ]);
        $boq_version_detail->update($data);

        return redirect()->route('admin.boq_version_details.index')->with('success', 'Boq Version Details Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        try {

            $boq_version_details = BoqVersionDetails::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($id);
            // Check if the BOQ Version is associated with any other records
            $boq_version_details->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Version Details deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting BOQ Version Details: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting BOQ Version Details.';
            return response()->json($data);
        }
    }
    public function existingBoqVersionDetails(Request $request)
    {
        $boq_version_details = BoqVersionDetails::where('project_id', Auth::guard('admin')->user()->project_id)
            ->where('boq_version_id', $request->get('boq_version_id'))
            ->where('package_id', $request->get('package_id'))
            ->where('boq_part_id', $request->get('boq_part_id'))
            ->where('boq_item_id', $request->get('boq_item_id'))
            ->where('scheme_option_id', $request->get('scheme_option_id'));
        if ($request->has('boq_sub_item_id') && !empty($request->get('boq_sub_item_id'))) {
            $boq_version_details->where('boq_sub_item_id', $request->get('boq_sub_item_id'));
        }
        if($request->has('id') && !empty($request->get('id'))) {
            $boq_version_details->where('id', '!=', $request->get('id'));
        }
        $boq_version_details = $boq_version_details->get();

        if (count($boq_version_details) > 0) {

            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Duplicate Entry Found';
            $data->data = $boq_version_details;
        } else {
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'This Details Does Not Exist.';
        }
        return response()->json($data);
    }

     public function copy($id)
    {
        $boq_version_detail = BoqVersionDetails::find($id);
        $boq_version = BoqVersion::find($boq_version_detail->boq_version_id);
        $packages = Package::where('project_id', Auth::guard('admin')->user()->project_id)->permitted()->get();

        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get();

        $boq_items = BoqItem::where('boq_part_id', $boq_version_detail->boq_part_id)
            ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $boq_sub_items = BoqSubItem::where('boq_item_id', $boq_version_detail->boq_item_id)
            ->where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $boq_versions = BoqVersion::where('project_id', Auth::guard('admin')->user()->project_id)
        ->where('package_id', $boq_version_detail->package_id)->get();
        $scheme_options = SchemeOption::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $units = Unit::get();
        return view('backend.admin.boq_version_details.copy', compact('boq_version', 'boq_parts', 'packages', 'boq_items', 'boq_sub_items', 'boq_versions', 'scheme_options', 'units', "boq_version_detail"));
    }

}
