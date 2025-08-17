<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function getDistrictsByDivision($division_id)
    {
        if (!$division_id) {
            return response()->json([]);
        }

        $districts = District::where('division_id', $division_id)->get();
        return response()->json($districts);
    }
    
}
