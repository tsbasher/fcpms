<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function getDistrictsByDivision(Request $request)
    {
        $divisionId = $request->input('division_id');
        $districts = District::where('division_id', $divisionId)->get();
        return response()->json($districts);
    }
}
