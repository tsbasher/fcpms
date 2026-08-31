<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $document_types = DocumentType::where('project_id', Auth::guard('admin')->user()->project_id);
        if ($request->has('search_text') && !empty($request->search_text)) {
            $search = $request->input('search_text');
            $document_types->where('name', 'ilike', "%{$search}%");
        }
        $document_types = $document_types->paginate();
        return view('backend.admin.document_types.index', compact('document_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.document_types.create');
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
            'is_active' => 'required|boolean',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'description', 'is_active']);
        $data['project_id'] = Auth::guard('admin')->user()->project_id;
        DocumentType::create($data);
        return redirect()->route('admin.document_types.index')->with('success', 'Document Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $document_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $document_type)
    {
        return view('backend.admin.document_types.edit', compact('document_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $document_type)
    {
        if (!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = $request->only(['name', 'description', 'is_active']);
        $document_type->update($data);
        return redirect()->route('admin.document_types.index')->with('success', 'Document Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $document_type)
    {
        try {
            if ($document_type->bill_documents()->count() > 0) {
                $data = new stdClass();
                $data->status = 0;
                $data->message = 'Cannot delete: this Document Type is used by existing documents.';
                return response()->json($data);
            }
            $document_type->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Document Type deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting document type: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Document Type.';
            return response()->json($data);
        }
    }
}
