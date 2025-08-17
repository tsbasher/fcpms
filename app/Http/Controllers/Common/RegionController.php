<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $regions = Region::query();

        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $regions->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%");
        }

        $regions = $regions->paginate();

        return view('backend.admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.regions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only(['name']);
        Region::create($data);
        return redirect()->route('admin.regions.index')->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        return view('backend.admin.regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $v=Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only(['name']);
        $region->update($data);
        return redirect()->route('admin.regions.index')->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        try {
            // Check if the region is associated with any other records
            $region->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Region deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting region: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Region.';
            return response()->json($data);
        }
    }
}
