<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class BoqItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch boq items based on search criteria if provided
        $boq_items = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->with('boq_parts', 'unit');
        if ($request->has('boq_part_id') && !empty($request->boq_part_id)) {
            $boq_items->where('boq_part_id', $request->boq_part_id);
        }
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $boq_items->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%");
            });
        }
        // Get all boq items
        $boq_items = $boq_items->paginate();
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get(); // Fetch boq parts for the filter dropdown
        return view('backend.admin.boq_items.index', compact('boq_items', 'boq_parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get(); // Assuming you have a BoqPart model to fetch parts
        $units = Unit::all(); // Fetch all units for the dropdown
        // Return the view for creating a new boq item
        return view('backend.admin.boq_items.create', compact('boq_parts','units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        if (!$request->has('has_sub_items')) {
            $request->merge(['has_sub_items' => 0]);
        }
        $v = Validator::make($request->all(), [
            'boq_part_id' => 'required|exists:boq_parts,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'specification_no' => 'nullable|string|max:100',
            'unit_id' => 'nullable|uuid|exists:units,id', // Assuming you have a Unit model for units
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['boq_part_id', 'name', 'code', 'description', 'is_active', 'specification_no', 'has_sub_items', 'unit_id']);
        $data['project_id'] = Auth::guard('admin')->user()->project_id; // Assuming the user is authenticated
        BoqItem::create($data);
        return redirect()->route('admin.boq_items.index')->with('success', 'BOQ Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqItem $boqItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoqItem $boqItem)
    {
        $boq_item = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqItem->id); // Fetch the boq item by ID
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get();
        $units = Unit::all(); // Fetch all units for the dropdown
        return view('backend.admin.boq_items.edit', compact('boq_item', 'boq_parts','units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoqItem $boqItem)
    {
        $boq_item = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqItem->id); // Fetch the boq item by ID

        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        if (!$request->has('has_sub_items')) {
            $request->merge(['has_sub_items' => 0]);
        }
        $v = Validator::make($request->all(), [
            'boq_part_id' => 'required|exists:boq_parts,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'specification_no' => 'nullable|string|max:100',
            'unit_id' => 'nullable|uuid|exists:units,id', // Assuming you have a Unit model for units
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $data = $request->only(['boq_part_id', 'name', 'code', 'description', 'is_active', 'specification_no', 'has_sub_items', 'unit_id']);
        $boq_item->update($data);
        return redirect()->route('admin.boq_items.index')->with('success', 'BOQ Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqItem $boqItem)
    {

        try {
            // Check if the project is associated with any other records
            $boqItem = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqItem->id); // Fetch the boq part by ID

            $boqItem->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Item deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting boq item: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting BOQ Item.';
            return response()->json($data);
        }
    }

    public function getBoqItemsByPart(Request $request)
    {
        $boq_part_id = $request->input('boq_part_id');

        $boq_items = BoqItem::where('boq_part_id', $boq_part_id)
            ->where('project_id', Auth::guard('admin')->user()->project_id)
            ->get();
        return response()->json($boq_items);
    }
}
