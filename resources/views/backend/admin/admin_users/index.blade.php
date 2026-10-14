@extends('backend.admin.layouts.app')
@section('title', 'Admin Users')
@section('style')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Admin Users</h2>
                    <div class="card-tools">
                        @if ($permAdmin && $permAdmin->hasPermission('admin_users.create'))
                        <a href="{{ route('admin.admin_users.create') }}" class="btn btn btn-success"><i class="fa fa-plus"></i> Add</a>
                        @endif
                    </div>
                </div>
                <div class="card-body ">

                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <form method="get">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="search" class="form-control form-control-lg" name="search_text" id="search_text" placeholder="Type your keywords here" value="{{ Request::has('search_text') ? Request::get('search_text') : '' }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-lg btn-default">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.admin_users.index') }}" class="btn btn-lg btn-default">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
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
                        <table class="table table-bordered table-hover table-head-fixed" id="admin-user-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Projects</th>
                                    <th>Online</th>
                                    <th>Status</th>
                                    <th>Locked</th>
                                    <th>Login Attempts</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->phone }}</td>
                                        <td>
                                            @if ($admin->role)
                                            @if($admin->role->name == 'Super Admin')
                                                <span class="badge bg-danger">{{ $admin->role->name }}</span>
                                                @else
                                                <span class="badge bg-warning">{{ $admin->role->name }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse ($admin->projects as $project)
                                                <span class="badge bg-info">{{ $project->code }}</span>
                                            @empty
                                                <span class="badge bg-secondary">None</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            @if ($admin->is_online)
                                                <span class="badge bg-success" ><i class="fas fa-circle" style="font-size: 8px"></i> Online</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-circle" style="font-size: 8px"></i> Offline</span>
                                            @endif
                                            @if ($admin->last_seen_at)
                                                <br><small class="text-muted">Last seen {{ $admin->last_seen_at->diffForHumans(['short' => true]) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($admin->is_active == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger" >Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($admin->is_locked == 1)
                                                <span class="badge bg-warning" style="font-size: 100%">Yes</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size: 100%">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $admin->login_attempts ?? 0 }}</td>
                                        <td>
                                            @if ($permAdmin && $permAdmin->hasPermission('admin_users.edit'))
                                            <form action="{{ route('admin.admin_users.toggle_active', $admin->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $admin->is_active == 1 ? 'danger' : 'success' }}" title="{{ $admin->is_active == 1 ? 'Deactivate' : 'Activate' }}" {{ $admin->id == Auth::guard('admin')->id() ? 'disabled' : '' }}>
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            @if ($admin->is_locked == 1)
                                                <form action="{{ route('admin.admin_users.unlock', $admin->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info" title="Unlock">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($permAdmin && $permAdmin->isSuperAdmin())
                                            <button type="button" class="btn btn-sm btn-primary assign-project" data-toggle="modal" data-target="#assignProjectModal" data-id="{{ $admin->id }}" data-name="{{ $admin->name }}" data-projects="{{ $admin->projects->pluck('id')->toJson() }}" title="Assign Project">
                                                <i class="fas fa-project-diagram"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('admin.admin_users.edit', $admin->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if ($permAdmin && $permAdmin->hasPermission('admin_users.delete'))
                                            <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.admin_users.destroy', $admin->id) }}" {{ $admin->id == Auth::guard('admin')->id() ? 'disabled' : '' }}><i class="fas fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix" style="background: #00000000">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </section>

    @if ($permAdmin && $permAdmin->isSuperAdmin())
    <div class="modal fade" id="assignProjectModal" tabindex="-1" role="dialog" aria-labelledby="assignProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="assignProjectForm" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignProjectModalLabel"><i class="fas fa-project-diagram mr-1"></i> Assign Projects</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Admin User: <strong id="assignProjectAdminName"></strong></p>
                        <div class="form-group">
                            <label for="assign_project_ids">Projects <span class="text-danger">*</span></label>
                            <select required class="form-control" name="project_ids[]" id="assign_project_ids" multiple style="width: 100%">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->code }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select at least one project this admin user can access.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
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
        $(".delete_record").click(function() {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { "_token": token },
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    title: 'Success',
                                    text: data.message,
                                    icon: 'success'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    timer: 1500,
                                    title: 'ERROR',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(ex) {
                            Swal.fire({
                                timer: 1500,
                                title: 'ERROR',
                                text: 'Something Went Wrong',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        $('#admin-user-table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        @if ($permAdmin && $permAdmin->isSuperAdmin())
        var assignProjectUrl = "{{ route('admin.admin_users.assign_project', ['id' => '__ID__']) }}";
        $('#assign_project_ids').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#assignProjectModal')
        });
        $('.assign-project').on('click', function() {
            var projects = $(this).data('projects') || [];
            $('#assignProjectForm').attr('action', assignProjectUrl.replace('__ID__', $(this).data('id')));
            $('#assignProjectAdminName').text($(this).data('name'));
            $('#assign_project_ids').val(projects).trigger('change');
        });
        @endif
    </script>
@endsection