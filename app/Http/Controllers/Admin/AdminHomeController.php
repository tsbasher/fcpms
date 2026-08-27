<?php

namespace App\Http\Controllers\Admin;

use App\Helper\PermittedPackage;
use App\Helper\PermittedScheme;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\BillScheme;
use App\Models\BoqVersion;
use App\Models\Contractor;
use App\Models\Package;
use App\Models\Project;
use App\Models\Scheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{
    public function index()
    {
        $projectId = Auth::guard('admin')->user()->project_id;
        $permittedPackageIds = PermittedPackage::getAdminPermittedPackages();
        $permittedSchemeIds = PermittedScheme::getAdminPermittedSchemes();

        $totalProjects = Project::count();
        $activeProjects = Project::where('is_active', 1)->count();

        $totalPackages = Package::whereIn('id', $permittedPackageIds)->count();
        $activePackages = Package::whereIn('id', $permittedPackageIds)->where('is_active', 1)->count();

        $totalSchemes = Scheme::whereIn('id', $permittedSchemeIds)->count();
        $activeSchemes = Scheme::whereIn('id', $permittedSchemeIds)->where('is_active', 1)->count();

        $totalContractors = Contractor::count();
        $activeContractors = Contractor::where('is_active', 1)->count();

        $totalBills = Bill::whereIn('package_id', $permittedPackageIds)->count();
        $currentMonthBills = Bill::whereIn('package_id', $permittedPackageIds)
            ->whereMonth('bill_date', Carbon::now()->month)
            ->whereYear('bill_date', Carbon::now()->year)
            ->count();

        $totalBoqVersions = BoqVersion::where('project_id', $projectId)
            ->whereIn('package_id', $permittedPackageIds)
            ->count();

        $totalBillAmount = Bill::whereIn('package_id', $permittedPackageIds)
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->sum('bill_details.this_bill_amount');

        $billedSchemeCount = BillScheme::join('bills', 'bill_schemes.bill_id', '=', 'bills.id')
            ->whereIn('bills.package_id', $permittedPackageIds)
            ->distinct('bill_schemes.scheme_id')
            ->count('bill_schemes.scheme_id');

        $billsByStatus = Bill::whereIn('package_id', $permittedPackageIds)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $monthlyBillTrend = Bill::whereIn('package_id', $permittedPackageIds)
            ->where('bill_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("to_char(bill_date, 'YYYY-MM') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyBillAmountTrend = Bill::whereIn('bills.package_id', $permittedPackageIds)
            ->where('bills.bill_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->select(
                DB::raw("to_char(bills.bill_date, 'YYYY-MM') as month"),
                DB::raw('COALESCE(sum(bill_details.this_bill_amount), 0) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $packageSchemeStats = Package::whereIn('packages.id', $permittedPackageIds)
            ->where('packages.is_active', 1)
            ->leftJoin('schemes', 'schemes.package_id', '=', 'packages.id')
            ->leftJoin('bill_schemes', 'bill_schemes.scheme_id', '=', 'schemes.id')
            ->leftJoin('bills', function ($join) {
                $join->on('bills.id', '=', 'bill_schemes.bill_id')
                     ->whereColumn('bills.package_id', '=', 'packages.id');
            })
            ->select(
                'packages.name',
                DB::raw('count(distinct schemes.id) as total_schemes'),
                DB::raw('count(distinct bill_schemes.scheme_id) as billed_schemes')
            )
            ->groupBy('packages.id', 'packages.name')
            ->orderByDesc('total_schemes')
            ->limit(5)
            ->get();

        $packageBillCounts = Package::whereIn('packages.id', $permittedPackageIds)
            ->where('packages.is_active', 1)
            ->leftJoin('bills', 'bills.package_id', '=', 'packages.id')
            ->select('packages.name', DB::raw('count(bills.id) as total'))
            ->groupBy('packages.id', 'packages.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentBills = Bill::whereIn('package_id', $permittedPackageIds)
            ->with('contractor')
            ->orderByDesc('bill_date')
            ->limit(10)
            ->get();

        $topContractors = Bill::whereIn('package_id', $permittedPackageIds)
            ->join('contractors', 'bills.contractor_id', '=', 'contractors.id')
            ->select(
                'contractors.company_name',
                DB::raw('count(distinct bills.id) as bill_count'),
                DB::raw('sum(bill_details.this_bill_amount) as total_amount')
            )
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->groupBy('contractors.id', 'contractors.company_name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return view('backend.admin.home', compact(
            'totalProjects', 'activeProjects',
            'totalPackages', 'activePackages',
            'totalSchemes', 'activeSchemes',
            'totalContractors', 'activeContractors',
            'totalBills', 'currentMonthBills',
            'totalBoqVersions',
            'totalBillAmount',
            'billedSchemeCount',
            'billsByStatus',
            'monthlyBillTrend',
            'monthlyBillAmountTrend',
            'packageSchemeStats',
            'packageBillCounts',
            'recentBills',
            'topContractors'
        ));
    }
}
