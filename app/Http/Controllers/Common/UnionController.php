<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Union;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    public function getUnionsByUpazila($upazila_id)
    {
        if (!$upazila_id) {
            return response()->json([]);
        }

        $unions = Union::where('upazila_id', $upazila_id)->get();
        return response()->json($unions);
    }
}
