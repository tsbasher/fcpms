<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function getUpazilasByDistrict(Request $request)
    {
        $districtId = $request->input('district_id');
       
        $upazilas = Upazila::where('district_id', $districtId)->get();
        
        return response()->json($upazilas);
    }
}
