<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BillDocumentController extends Controller
{
    public function store(Request $request, $bill_id)
    {
        $bill = Bill::findOrFail($bill_id);

        $v = Validator::make($request->all(), [
            'document_type_id' => 'required|exists:document_types,id',
            'title' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480', // 20MB max
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $documentType = DocumentType::findOrFail($request->document_type_id);
        if (!$documentType->is_active) {
            return redirect()->back()->with('error', 'This Document Type is deactivated.');
        }

        $file = $request->file('file');
        $projectCode = $bill->project ? $bill->project->code : 'unknown-project';
        $packageName = $bill->package ? $bill->package->name : 'unknown-package';
        $billNo = $bill->bill_no ?: 'unknown-bill';
        $path = $file->store("bill_documents/$projectCode/$packageName/$billNo", 'public');

        BillDocument::create([
            'bill_id' => $bill->id,
            'project_id' => $bill->project_id,
            'document_type_id' => $documentType->id,
            'title' => $request->title,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy($document_id)
    {
        $document = BillDocument::findOrFail($document_id);

        $bill = Bill::find($document->bill_id);
        if (!$bill) {
            return response()->json(['success' => false, 'message' => 'Bill not found.'], 404);
        }

        $user = auth()->user();
        $isOwner = $user &&
            $bill->contractor_id == $user->contractor_id &&
            $bill->project_id == $user->project_id &&
            $bill->package_id == $user->package_id;

        if (!$isOwner) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to delete this document.'], 403);
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
    }
}
