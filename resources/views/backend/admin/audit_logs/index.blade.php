@extends('backend.admin.layouts.app')
@section('title', 'Audit Logs')
@section('style')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Audit Logs</h2>
                    <div class="card-tools">
                        <a href="{{ route('admin.audit_logs.export', request()->query()) }}" class="btn btn btn-success"><i class="fas fa-file-csv"></i> Export CSV</a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <form method="get" class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="user_type" class="form-control">
                                            <option value="">All</option>
                                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="guest" {{ request('user_type') == 'guest' ? 'selected' : '' }}>Guest</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Module</label>
                                        <input type="text" name="module" class="form-control" placeholder="e.g. bills" value="{{ request('module') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Method</label>
                                        <select name="method" class="form-control">
                                            <option value="">All</option>
                                            @foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $m)
                                                <option value="{{ $m }}" {{ request('method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status Code</label>
                                        <input type="text" name="status_code" class="form-control" placeholder="e.g. 200" value="{{ request('status_code') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-0 mt-auto">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="search" placeholder="Search path, route name or IP address" value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                                <a href="{{ route('admin.audit_logs.index') }}" class="btn btn-default"><i class="fas fa-sync-alt"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-dismissible">{{ $message }}</div>
                            @endif
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible">{{ $message }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-head-fixed" id="audit-log-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Date/Time</th>
                                    <th>User</th>
                                    <th>Module</th>
                                    <th>Method</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                    <th>Duration</th>
                                    <th>IP Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @if ($log->user_type == 'admin')
                                                <span class="badge bg-primary" >Admin</span>
                                            @elseif ($log->user_type == 'user')
                                                <span class="badge bg-success" >User</span>
                                            @else
                                                <span class="badge bg-secondary" >Guest</span>
                                            @endif
                                            {{ $log->user_name ?? $log->user_id ?? '' }}
                                        </td>
                                        <td>{{ $log->module }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->method == 'GET' ? 'info' : ($log->method == 'DELETE' ? 'danger' : 'warning') }}">{{ $log->method }}</span>
                                        </td>
                                        <td class="text-wrap" style="max-width: 250px;">
                                            <small>{{ $log->route_name }}</small><br>
                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($log->path, 60) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $log->status_code < 400 ? 'success' : 'danger' }}">{{ $log->status_code }}</span>
                                        </td>
                                        <td>{{ $log->duration_ms ? number_format($log->duration_ms, 2) . ' ms' : '-' }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info view_log_detail" data-url="{{ route('admin.audit_logs.show', $log->id) }}"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No audit logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" style="background: #00000000">
                    {{ $logs->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </section>

    <!-- View Log Modal -->
    <div class="modal fade" id="modal-view-log">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Audit Log Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl class="row" id="log-detail"></dl>
                    <h5>Payload</h5>
                    <pre id="log-payload" class="bg-light p-2" style="max-height: 300px; overflow: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script type="text/javascript">
        $('#audit-log-table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        $(".view_log_detail").click(function() {
            var url = $(this).data('url');
            $.get(url, function(log) {
                var html = '';
                var fields = {
                    'user_type': 'User Type',
                    'user_name': 'User',
                    'method': 'Method',
                    'path': 'Path',
                    'route_name': 'Route Name',
                    'module': 'Module',
                    'status_code': 'Status Code',
                    'duration_ms': 'Duration (ms)',
                    'ip_address': 'IP Address',
                    'user_agent': 'User Agent',
                    'created_at': 'Date/Time'
                };
                $.each(fields, function(key, label) {
                    var value = log[key] !== null && log[key] !== undefined ? log[key] : '-';
                    html += '<dt class="col-sm-3">' + label + '</dt>';
                    html += '<dd class="col-sm-9 text-wrap">' + $('<div>').text(value).html() + '</dd>';
                });
                $('#log-detail').html(html);
                $('#log-payload').text(log.payload ? JSON.stringify(log.payload, null, 2) : 'No payload');
                $('#modal-view-log').modal('show');
            });
        });
    </script>
@endsection