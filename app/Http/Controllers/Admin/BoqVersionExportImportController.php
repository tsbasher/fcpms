<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoqVersionExportImportController extends Controller
{
    public function index()
    {
        
        return view('backend.admin.boq_version_export_import.index');
    }

    public function export($version_id)
    {
        // Implement export functionality
    }
}
