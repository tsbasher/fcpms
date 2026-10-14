@extends('backend.admin.layouts.app')
@section('title', 'Roles')
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
                    <h2 class="card-title ">Roles &amp; Permissions</h2>
                    <div class="card-tools">
                        @if ($permAdmin && $permAdmin->hasPermission('roles.create'))
                        <a href="{{ route('admin.roles.create') }}" class="btn btn btn-success"><i class="fa fa-plus"></i> Add</a>
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
                                            <a href="{{ route('admin.roles.index') }}" class="btn btn-lg btn-default">
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
                        <table class="table table-bordered table-hover" id="role-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Admin Users</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            {{ $role->name }}
                                            @if ($role->is_super_admin)
                                                <span class="badge bg-danger">Super Admin</span>
                                            @endif
                                        </td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            @if ($role->is_super_admin)
                                                <span class="badge bg-info">All</span>
                                            @else
                                                <span class="badge bg-info">{{ $role->permissions_count }}</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $role->admins_count }}</span></td>
                                        <td>
                                            @if ($role->is_active)
                                                <span class="badge bg-success" style="font-size: 100%">Active</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 100%">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($permAdmin && $permAdmin->hasPermission('roles.edit'))
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if ($permAdmin && $permAdmin->hasPermission('roles.delete') && !$role->is_super_admin)
                                                <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.roles.destroy', $role->id) }}"><i class="fas fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix" style="background: #00000000">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

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

        $('#role-table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    </script>
@endsection