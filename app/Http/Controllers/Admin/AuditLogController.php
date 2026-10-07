<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display a filtered listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = $this->baseQuery($request);

        $logs = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        return view('backend.admin.audit_logs.index', compact('logs'));
    }

    /**
     * Show a single audit log as JSON.
     */
    public function show(Request $request, string $id)
    {
        $log = AuditLog::findOrFail($id);

        return response()->json($log);
    }

    /**
     * Export filtered audit logs as CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $logs = $this->baseQuery($request)
            ->orderByDesc('created_at')
            ->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Date/Time',
                'User Type',
                'User',
                'User ID',
                'Method',
                'Path',
                'Route Name',
                'Module',
                'Status',
                'Duration (ms)',
                'IP Address',
                'User Agent',
                'Payload',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $log->user_type,
                    $log->user_name,
                    $log->user_id,
                    $log->method,
                    $log->path,
                    $log->route_name,
                    $log->module,
                    $log->status_code,
                    $log->duration_ms,
                    $log->ip_address,
                    $log->user_agent,
                    $log->payload !== null ? json_encode($log->payload, JSON_UNESCAPED_UNICODE) : '',
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    /**
     * Build the filtered query from request inputs.
     */
    protected function baseQuery(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        if ($request->filled('module')) {
            $query->where('module', 'ilike', '%' . $request->input('module') . '%');
        }

        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->filled('status_code')) {
            $query->where('status_code', $request->input('status_code'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('path', 'ilike', '%' . $search . '%')
                    ->orWhere('route_name', 'ilike', '%' . $search . '%')
                    ->orWhere('ip_address', 'ilike', '%' . $search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        return $query;
    }
}