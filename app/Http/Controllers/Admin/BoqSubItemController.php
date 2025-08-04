<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\BoqPart;
use App\Models\BoqSubItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class BoqSubItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch boq sub items based on search criteria if provided
        $boq_sub_items = BoqSubItem::where('project_id', Auth::guard('admin')->user()->project_id)->with('boq_part', 'boq_item', 'unit');
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $boq_sub_items->where(function($query)use($search){
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%");
            });
        }
        if ($request->has('boq_item_id') && !empty($request->boq_item_id)) {
            $boq_sub_items->where('boq_item_id', $request->boq_item_id);
        }
        if($request->has('boq_part_id') && !empty($request->boq_part_id)) {
            $boq_sub_items->where('boq_part_id', $request->boq_part_id);
        }
        // dd($boq_sub_items->toSql());

        // Get all boq sub items
        $boq_sub_items = $boq_sub_items->paginate();
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get(); // Fetch boq parts for the filter dropdown            
        $boq_items = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->where('boq_part_id', $request->boq_part_id)->get(); // Fetch boq items for the filter dropdown

        return view('backend.admin.boq_sub_items.index', compact('boq_sub_items', 'boq_parts', 'boq_items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get(); // Fetch boq parts for the dropdown
        $boq_items = [];
        $units = Unit::all(); // Fetch all units for the dropdown
        return view('backend.admin.boq_sub_items.create', compact('boq_parts', 'boq_items','units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'boq_part_id' => 'required|exists:boq_parts,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'specification_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'unit_id' => 'required|uuid|exists:units,id', // Assuming you have a Unit model for units
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only(['boq_part_id', 'boq_item_id', 'name', 'code', 'specification_no', 'description', 'is_active', 'unit_id']);
        $data['project_id']=Auth::guard('admin')->user()->project_id;
        BoqSubItem::create($data);
        return redirect()->route('admin.boq_sub_items.index')->with('success', 'BOQ Sub Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqSubItem $boqSubItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoqSubItem $boqSubItem)
    {
        $boq_sub_item=BoqSubItem::where('project_id',Auth::guard('admin')->user()->project_id)->findorfail($boqSubItem->id);
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->get(); // Fetch boq parts for the dropdown
        $boq_items = BoqItem::where('project_id', Auth::guard('admin')->user()->project_id)->where('boq_part_id',$boq_sub_item->boq_part_id)->get(); // Fetch boq items for the dropdown
        $units = Unit::all(); // Fetch all units for the dropdown
        return view('backend.admin.boq_sub_items.edit', compact('boq_sub_item', 'boq_parts', 'boq_items','units'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoqSubItem $boqSubItem)
    {
        
        $boq_sub_item=BoqSubItem::where('project_id',Auth::guard('admin')->user()->project_id)->findorfail($boqSubItem->id);
        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'boq_part_id' => 'required|exists:boq_parts,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'specification_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only(['boq_part_id', 'boq_item_id', 'name', 'code', 'specification_no', 'description', 'is_active', 'unit_id']);
        $boq_sub_item->update($data);
        return redirect()->route('admin.boq_sub_items.index')->with('success', 'BOQ Sub Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqSubItem $boqSubItem)
    {
        try {
            // Check if the project is associated with any other records
        $boq_sub_item=BoqSubItem::where('project_id',Auth::guard('admin')->user()->project_id)->findorfail($boqSubItem->id);

            $boq_sub_item->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Sub Item deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting boq sub item: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting BOQ Sub Item.';
            return response()->json($data);
        }
    }
}
