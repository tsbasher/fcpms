<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\BillScheme;
use App\Models\ContractorPackage;
use App\Models\Measurement;
use App\Models\Package;
use App\Models\Scheme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserHomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        $contractorId = $user->contractor_id;
        $projectId = $user->project_id;
        $packageId = $user->package_id;

        $totalBills = Bill::where('contractor_id', $contractorId)
            ->where('project_id', $projectId)
            ->where('package_id', $packageId)
            ->count();

        $draftBills = Bill::where('contractor_id', $contractorId)
            ->where('project_id', $projectId)
            ->where('package_id', $packageId)
            ->where('status', 'Draft')
            ->count();

        $totalBilledAmount = Bill::where('bills.contractor_id', $contractorId)
            ->where('bills.project_id', $projectId)
            ->where('bills.package_id', $packageId)
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->sum('bill_details.this_bill_amount');

        $schemesCovered = BillScheme::whereHas('bill', function ($q) use ($contractorId, $projectId, $packageId) {
                $q->where('contractor_id', $contractorId)
                  ->where('project_id', $projectId)
                  ->where('package_id', $packageId);
            })
            ->distinct('scheme_id')
            ->count('scheme_id');

        $billsByMonth = Bill::where('contractor_id', $contractorId)
            ->where('project_id', $projectId)
            ->where('package_id', $packageId)
            ->where('bill_date', '<=', Carbon::now()->startOfMonth())
            ->select(
                DB::raw("to_char(bill_date, 'YYYY-MM') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $billAmountBreakdown = Bill::where('bills.contractor_id', $contractorId)
            ->where('bills.project_id', $projectId)
            ->where('bills.package_id', $packageId)
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->select('bills.name', DB::raw('sum(bill_details.this_bill_amount) as total_amount'))
            ->groupBy('bills.id', 'bills.name')
            ->orderByDesc('total_amount')
            ->limit(6)
            ->get();

        $recentBills = Bill::where('contractor_id', $contractorId)
            ->where('project_id', $projectId)
            ->where('package_id', $packageId)
            ->withCount('bill_scheme')
            ->orderByDesc('bill_date')
            ->limit(5)
            ->get();

        $billsNeedingMeasurements = Bill::where('contractor_id', $contractorId)
            ->where('project_id', $projectId)
            ->where('package_id', $packageId)
            ->whereDoesntHave('bill_details', function ($q) {
                $q->whereHas('measurements');
            })
            ->withCount('bill_scheme')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('backend.user.home', compact(
            'totalBills',
            'draftBills',
            'totalBilledAmount',
            'schemesCovered',
            'billsByMonth',
            'billAmountBreakdown',
            'recentBills',
            'billsNeedingMeasurements'
        ));
    }

    public function getSchemebyUpazila($upazila_id)
    {
        $schemes = Scheme::where('package_id', Auth::guard('web')->user()->package_id)
        ->where('upazila_id', $upazila_id)->orderby('code')->get();

        return response()->json($schemes);
    }

    public function getPermittedPackages()
    {
        $package_ids=ContractorPackage::where('contractor_id',Auth::guard('web')->user()->contractor_id)->pluck('package_id');
        $packages = Package::whereIn('id', $package_ids)->get();
        return view('backend.user.layouts.partials._select_package', compact('packages'));
    }

    public function selectPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $user = User::find( Auth::guard('web')->user()->id);
        $user->package_id = $request->input('package_id');
        $user->save();

        return redirect()->route('user.home')->with('success', 'Package selected successfully.');
    }
}
