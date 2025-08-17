<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contractor::query();

        if ($request->filled('search_text')) {
            $query->where('company_name', 'like', '%' . $request->search_text . '%')
            ->orWhere('company_email', 'like', '%' . $request->search_text . '%')
            ->orWhere('company_phone', 'like', '%' . $request->search_text . '%')
            ->orWhere('company_address', 'like', '%' . $request->search_text . '%')
            ->orWhere('company_website', 'like', '%' . $request->search_text . '%')
            ->orWhere('company_reg_code', 'like', '%' . $request->search_text . '%')
            ->orWhere('contact_person_name', 'like', '%' . $request->search_text . '%')
            ->orWhere('contact_person_email', 'like', '%' . $request->search_text . '%')
            ->orWhere('contact_person_phone', 'like', '%' . $request->search_text . '%');
        }

        $contractors = $query->paginate();

        return view('backend.admin.contractors.index', compact('contractors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('backend.admin.contractors.create');
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
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_reg_code' => 'nullable|string|max:100',
            'company_website' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data=$request->only([
            'company_name',
            'company_email',
            'company_phone',
            'company_reg_code',
            'company_website',
            'contact_person_name',
            'contact_person_email',
            'contact_person_phone',
            'company_address',
            'is_active',
        ]);
        Contractor::create($data);

        return redirect()->route('admin.contractors.index')->with('success', 'Contractor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contractor $contractor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contractor $contractor)
    {
        return view('backend.admin.contractors.edit', compact('contractor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contractor $contractor)
    {
        if(!$request->has('is_active')) {
            $request->merge(['is_active' => 0]);
        }
        $v=Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_reg_code' => 'nullable|string|max:100',
            'company_website' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $data = $request->only([
            'company_name',
            'company_email',
            'company_phone',
            'company_reg_code',
            'company_website',
            'contact_person_name',
            'contact_person_email',
            'contact_person_phone',
            'company_address',
            'is_active',
        ]);

        $contractor->update($data);

        return redirect()->route('admin.contractors.index')->with('success', 'Contractor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contractor $contractor)
    {
        
        try {
            // Check if the contractor is associated with any other records
            $contractor->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Contractor deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting contractor: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting Contractor.';
            return response()->json($data);
        }
    }
    public function add_package($contractor_id)
    {
        $contractor = Contractor::findOrFail($contractor_id);
        $packages=Package::where('project_id',Auth::guard('admin')->user()->project_id)->Permitted()->get();
        return view('backend.admin.contractors.add_package', compact('contractor','packages'));
    }
    public function store_package(Request $request, $contractor_id)
    {
        $v = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $contractor = Contractor::findOrFail($contractor_id);
        $contractor->packages()->sync([$request->package_id => ['project_id' => Auth::guard('admin')->user()->project_id]]);

        return redirect()->route('admin.contractors.index')->with('success', 'Package added to contractor successfully.');
    }
}
