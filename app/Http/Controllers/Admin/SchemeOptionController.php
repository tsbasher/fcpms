<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchemeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Str;

class SchemeOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch scheme options based on search criteria if provided
        $scheme_options = SchemeOption::where('project_id', Auth::guard('admin')->user()->project_id);
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $scheme_options->where('name', 'ilike', "%{$search}%");
        }
        // Get all scheme options
        $scheme_options = $scheme_options->paginate();
        return view('backend.admin.scheme_options.index', compact('scheme_options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view for creating a new scheme option
        return view('backend.admin.scheme_options.create');
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
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB max
            'is_active' => 'required|boolean',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'description', 'is_active']);
        $data['project_id'] = Auth::guard('admin')->user()->project_id;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->move('uploads/scheme_options', $imageName);
            $data['image_url'] = $imagePath;
        }
        // Create a new scheme option
        SchemeOption::create($data);
        return redirect()->route('admin.scheme_options.index')->with('success', 'Scheme Option created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchemeOption $scheme_options)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchemeOption $scheme_option)
    {
        // Return the view for editing the scheme option
        return view('backend.admin.scheme_options.edit', compact('scheme_option'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchemeOption $scheme_option)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB max
            'is_active' => 'required|boolean',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'description', 'is_active']);
        $data['project_id'] = Auth::guard('admin')->user()->project_id;
        if($request->remove_image) {
            // If the remove image checkbox is checked, delete the existing image
            if ($scheme_option->image_url) {
                File::delete(public_path($scheme_option->image_url));
                $data['image_url'] = null; // Set image_url to null
            }
        }
        if ($request->hasFile('image_url')) {
            if ($scheme_option->image_url)
                File::delete(public_path($scheme_option->image_url)); // Delete old image if exists
            $image = $request->file('image_url');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->move('uploads/scheme_options', $imageName);
            $data['image_url'] = $imagePath;
        }
        // Update the scheme option
        $scheme_option->update($data);
        return redirect()->route('admin.scheme_options.index')->with('success', 'Scheme Option updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchemeOption $scheme_option)
    {
        try {
            // Check if the project is associated with any other records
            if ($scheme_option->image_url) {
                // Delete the image file from the server
                File::delete(public_path($scheme_option->image_url));
            }
            $scheme_option->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Scheme Option deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting scheme option: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Scheme Option.';
            return response()->json($data);
        }
    }
}
