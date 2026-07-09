<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BillGenerator;
use App\Helper\PermittedPackage;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillScheme;
use App\Models\Package;
use App\Models\Scheme;
use App\Models\Upazila;
use Illuminate\Http\Request;

class AdminBillController extends Controller
{
    //
    public function index(Request $request)
    {
        $packages = Package::whereIn('id', PermittedPackage::getAdminPermittedPackages())
            ->where('is_active', 1)
            ->get();
        $package = Package::find($request->package_id);
        if ($package)
            $upazilas = Upazila::where('district_id', $package->district_id)->get();
        else
            $upazilas = [];

        $bills = Bill::where('package_id', $request->package_id)
            ->get();

        // dd($bills);
        return view('backend.admin.bill.index', compact('bills', 'packages', 'upazilas', 'bills'));
    }
    public function bill_show(Request $request)
    {
        // $bill = Bill::with('schemes')->findOrFail($request->bill_id);

        $this_bill = Bill::findOrFail($request->bill_id);
        $project_id = $this_bill->project_id;
        $package_id = $this_bill->boq_version->package_id;

        $previous_bill_ids = Bill::where('id', '!=', $this_bill->id)
            ->where('serial', '<', $this_bill->serial)
            ->where('project_id', $this_bill->project_id)
            ->wherehas('boq_version', function ($query) use ($project_id, $package_id) {
                $query->where('project_id', $project_id)
                    ->where('package_id', $package_id);
            })
            ->orderBy('serial', 'desc')
            ->get()->pluck('id')->toArray();

        $upazila_scheme_ids = [];
        if ($request->report_type == "UPZ_DTL") {
            $upazila_scheme_ids = Scheme::where('upazila_id', $request->upazila_id)
                ->get()
                ->pluck('id')
                ->toArray();
            $scheme_ids = BillScheme::wherein('scheme_id', $upazila_scheme_ids)->where(function ($query) use ($this_bill, $previous_bill_ids) {
                $query->where('bill_id', $this_bill->id)
                    ->orwhereIn('bill_id', $previous_bill_ids);
            })->get()->pluck('scheme_id')->toArray();

            return BillGenerator::shelterWiseView($this_bill, $previous_bill_ids, $project_id, $package_id, $scheme_ids);
        }



        // return view('backend.admin.bill.shelter_wise_details', compact('bill', 'scheme_ids', 'project_id', 'package_id'));
    }

    public function getBillsByPackage($package_id)
    {
        $bills = Bill::where('package_id', $package_id)->get();
        return response()->json($bills);
    }
}
