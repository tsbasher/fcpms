<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContractorPackage;
use App\Models\Package;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    //
    public function index()
    {
        // dd(Auth::guard('web')->user()->package);
        return view('backend.user.home');
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

        return redirect()->back();
    }
}
