<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Union;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    public function getUnionsByUpazila(Request $request)
    {
        $upazilaId = $request->input('upazila_id');
        
        $unions = Union::where('upazila_id', $upazilaId)->get();
        
        return response()->json($unions);
    }
}
