@extends('backend.admin.layouts.app')
@section('title', 'Contractor Users')
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
            <!-- left column -->
            <!-- general form elements -->
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Contractor Users</h2>
                    <div class="card-tools">
                        <a href="{{ route('admin.contractor_users.create') }}" class="btn btn btn-success"><i class="fa fa-plus"></i> Add</a>
                    </div>
                </div>
                <div class="card-body ">

                            <form method="get">
                    <div class="row">
                        
                        <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control select2" name="contractor_id" id="contractor_id">
                                        <option value="">-- Select Contractor --</option>
                                        @foreach ($contractors as $contractor)
                                            <option value="{{ $contractor->id }}" {{ Request::has('contractor_id') && Request::get('contractor_id') == $contractor->id ? 'selected' : '' }}>{{ $contractor->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="search" class="form-control" name="search_text" id="search_text" placeholder="Type your keywords here" value="{{ Request::has('search_text') ? Request::get('search_text') : '' }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.boq_parts.index') }}" class="btn btn-default">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                    </div>
                            </form>
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
                        <table class="table table-bordered table-hover table-head-fixed" id="user-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Contractor</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->contractor ? $user->contractor->company_name : '' }}</td>

                                        <td>
                                            @if ($user->is_active == 1)
                                                <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 100%">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.contractor_users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.contractor_users.destroy', $user->id) }}"><i class="fas fa-trash"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" style="background: #00000000">
                    {{ $users->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </section>


@endsection

@section('script')
    <script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>

    {{-- <script src="{{ asset('backend/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <!-- DataTables  & Plugins -->
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
<script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>

    <script type="text/javascript">
        $(".select2").select2();
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
                        data: {
                            "_token": token,
                        },
                        success: function(data) {
                            
                            // var data = JSON.parse(response);
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
                // else if (result.dismiss === Swal.DismissReason.cancel) {
                //     Swal.fire({
                //         timer: 1500,
                //         title: 'Cancelled',
                //         text: 'Your Record is safe',
                //         icon: 'error'
                //     });
                // }
            });
        });

        

        $('#user-table').DataTable({
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
