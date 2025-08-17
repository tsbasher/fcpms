<?php

namespace App\Http\Controllers;

use App\Models\BoqItem;
use App\Models\BoqSubItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = Unit::query();

        if ($request->has('search_text') && !empty($request->search_text)) {
            $units->where('name', 'ilike', '%' . $request->search_text . '%')
                  ->orWhere('code', 'ilike', '%' . $request->search_text . '%');
        }
        $units = $units->paginate();
        return view('backend.admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        } 
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'fields' => 'required|array',
            'fields.*' => 'string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $unit=Unit::create([
            'name' => $request->name,
            'code' => $request->code,
            'fields' => json_encode($request->fields),
            'is_active' => $request->is_active ? 1 : 0
        ]);

        return redirect()->route('admin.units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $unit->fields = json_decode($unit->fields, true);
        return view('backend.admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        } 
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'fields' => 'required|array',
            'fields.*' => 'string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $unit->update([
            'name' => $request->name,
            'code' => $request->code,
            'fields' => json_encode($request->fields),
            'is_active' => $request->is_active ? 1 : 0
        ]);

        return redirect()->route('admin.units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
         try {
            // Check if the project is associated with any other records

            $unit->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Unit deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting unit: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Unit.';
            return response()->json($data);
        }
    }

    public function getUnitByBoqItem($boq_item_id)
    {
        $unit = BoqItem::where('id', $boq_item_id)->with('unit')->first();
        return response()->json($unit->unit);
    }

    public function getUnitByBoqSubItem($boq_sub_item_id)
    {
        $unit = BoqSubItem::where('id', $boq_sub_item_id)->with('unit')->first();
        return response()->json($unit->unit);
    }
}
