@extends('backend.admin.layouts.app')
@section('title', 'BOQ Versions')
@section('style')

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- left column -->
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h2 class="card-title ">BOQ Versions</h2>
                <div class="card-tools">
                    <a href="{{ route('admin.boq_versions.create') }}" class="btn btn btn-secondary"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>
            <div class="card-body ">

                <form method="get">
                    <div class="row">

                        <div class="col-md-6 ">

                            <div class="form-group row">
                                <label for="package_id" class="col-sm-2 col-form-label">Package</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="package_id" placeholder="Package" name="package_id">
                                        <option value="">Select Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" @if(Request::get('package_id')==$package->id) selected @endif>{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search_text" id="search_text" placeholder="Type your keywords here" value="{{ Request::has('search_text') ? Request::get('search_text') : '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{ route('admin.boq_versions.index') }}" class="btn btn-default">
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
                    <table class="table table-bordered table-hover table-head-fixed" id="boq-version-table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Package</th>
                                <th>Name</th>
                                <th>Version Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boq_versions as $boq_version)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $boq_version->package?$boq_version->package->name : '' }}</td>
                                <td>{{ $boq_version->name }}</td>
                                <td>{{ $boq_version->version_date }}</td>

                                <td>
                                    @if ($boq_version->is_active == 1)
                                    <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                    @else
                                    <span class="badge bg-danger" style="font-size: 100%">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.boq_versions.edit', $boq_version->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.boq_versions.destroy', $boq_version->id) }}"><i class="fas fa-trash"></i></a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix" style="background: #00000000">
                {{ $boq_versions->links() }}
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

<script type="text/javascript">
    $(".delete_record").click(function() {
        var url = $(this).data('url');

        debugger;
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
                        debugger;
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

                        debugger;
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



    $('#boq-version-table').DataTable({
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