<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function getUpazilasByDistrict($district_id)
    {
        if(!$district_id) {
            return response()->json([]);
        }

        $upazilas = Upazila::where('district_id', $district_id)->get();

        return response()->json($upazilas);
    }
}
