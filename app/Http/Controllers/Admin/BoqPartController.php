<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoqPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class BoqPartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch boq parts based on search criteria if provided
        $boq_parts = BoqPart::where('project_id', Auth::guard('admin')->user()->project_id);
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $boq_parts->where('name', 'ilike', "%{$search}%");
        }
        // Get all boq parts
        $boq_parts = $boq_parts->paginate();
        return view('backend.admin.boq_parts.index', compact('boq_parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.boq_parts.create');  
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
            'code' => 'required|string|max:50|unique:boq_parts,code',
            'description' => 'nullable|string|max:1000',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only(['name', 'code', 'description', 'is_active']);
        $data['project_id'] = Auth::guard('admin')->user()->project_id; // Assuming the user is authenticated
        BoqPart::create($data);

        return redirect()->route('admin.boq_parts.index')->with('success', 'BOQ Part created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoqPart $boqPart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoqPart $boqPart)
    {
        $boqPart= BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqPart->id); // Fetch the boq part by ID
        return view('backend.admin.boq_parts.edit', compact('boqPart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoqPart $boqPart)
    {        
        $boqPart= BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqPart->id); // Fetch the boq part by ID

        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:boq_parts,code,' . $boqPart->id,
            'description' => 'nullable|string|max:1000',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'code', 'description', 'is_active']);
        $boqPart->update($data);

        return redirect()->route('admin.boq_parts.index')->with('success', 'BOQ Part updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoqPart $boqPart)
    {
        
         try {
            // Check if the project is associated with any other records
        $boqPart= BoqPart::where('project_id', Auth::guard('admin')->user()->project_id)->findOrFail($boqPart->id); // Fetch the boq part by ID

            $boqPart->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'BOQ Part deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting boq part: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting BOQ Part.';
            return response()->json($data);
        }
    }
}
