<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BoqVersion;
use App\Models\Scheme;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bills = Bill::query();

        if ($request->filled('search_text')) {
            $bills->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_text . '%')
                    ->orWhere('reference_code', 'like', '%' . $request->search_text . '%');
            });
        }

        $bills = $bills->paginate();

        return view('backend.user.bill.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boq_versions = BoqVersion::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.create', compact(['boq_versions', 'schemes']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator($request->all(), [
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'reference_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'boq_version_id' => 'required|exists:boq_versions,id',
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        // dd(Auth::guard('web')->user());
        DB::transaction(function () use ($request) {
            $bill = new Bill();
            $bill->bill_no = $request->bill_no;
            $bill->bill_date = $request->bill_date;
            $bill->reference_code = $request->reference_code;
            $bill->name = $request->name;
            $bill->remarks = $request->remarks;
            $bill->boq_version_id = $request->boq_version_id;
            $bill->contractor_id = Auth::guard('web')->user()->contractor_id;
            $bill->project_id = Auth::guard('web')->user()->project_id;
            $bill->package_id = Auth::guard('web')->user()->package_id;
            $bill->created_by = Auth::guard('web')->user()->id;
            $bill->save();
            $bill->schemes()->attach($request->schemes);
        });
        return redirect()->route('user.bills.index')->with('success', 'Bill created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        $bill->load('contractor', 'project', 'boq_version', 'schemes', 'bill_parts');
        return view('backend.user.bill.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        $boq_versions = BoqVersion::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.edit', compact(['boq_versions', 'schemes', 'bill']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $v = Validator($request->all(), [
            'bill_no' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'reference_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'boq_version_id' => 'required|exists:boq_versions,id',
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        DB::transaction(function () use ($request, $bill) {
            $bill->bill_no = $request->bill_no;
            $bill->bill_date = $request->bill_date;
            $bill->reference_code = $request->reference_code;
            $bill->name = $request->name;
            $bill->remarks = $request->remarks;
            $bill->boq_version_id = $request->boq_version_id;
            $bill->contractor_id = Auth::guard('web')->user()->contractor_id;
            $bill->project_id = Auth::guard('web')->user()->project_id;
            $bill->package_id = Auth::guard('web')->user()->package_id;
            $bill->updated_by = Auth::guard('web')->user()->id;
            $bill->save();
            $bill->schemes()->sync($request->schemes);
        });
        return redirect()->route('user.bills.index')->with('success', 'Bill updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        try {
            $bill->schemes()->detach();
            $bill->delete();
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Bill deleted successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill.' . $e->getMessage();
            return response()->json($data);
        }
    }
    public function removeScheme($scheme_id, $bill_id)
    {
        try {
            $bill = Bill::findOrFail($bill_id);
            $bill->schemes()->detach($scheme_id);
            $data = new stdClass();
            $data->status = 1;
            $data->message = 'Scheme removed from bill successfully.';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error deleting bill scheme: ' . $e->getMessage());
            $data = new stdClass();
            $data->status = 0;
            $data->message = 'An error occurred while deleting bill scheme.' . $e->getMessage();
            return response()->json($data);
        }
    }
    public function addScheme($bill_id)
    {
        $bill = Bill::findOrFail($bill_id);
        $schemes = Scheme::where('project_id', Auth::guard('web')->user()->project_id)
            ->where('package_id', Auth::guard('web')->user()->package_id)->get();
        return view('backend.user.bill.add_scheme', compact(['bill', 'schemes']));
    }
    public function storeScheme(Request $request, $bill_id)
    {
        $v = Validator($request->all(), [
            'schemes' => 'required|array',
            'schemes.*' => 'exists:schemes,id',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $bill = Bill::findOrFail($bill_id);
        $bill->schemes()->sync($request->schemes);
        return redirect()->route('user.bills.show', $bill_id)->with('success', 'Schemes added to bill successfully.');
    }
}
