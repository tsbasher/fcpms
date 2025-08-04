@extends('backend.admin.layouts.app')
@section('title', 'Scheme')
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
                <h2 class="card-title ">Scheme</h2>
                <div class="card-tools">
                    <a href="{{ route('admin.schemes.create') }}" class="btn btn btn-secondary"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>
            <div class="card-body ">

                <form method="get">
                    <div class="row">
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="division_id">Division</label>
                                <select class="form-control select2" name="division_id" id="division_id">
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}" {{ Request::get('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="district_id">District</label>
                                <select class="form-control select2" name="district_id" id="district_id">
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" {{ Request::get('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="upazila_id">Upazila</label>
                                <select class="form-control select2" name="upazila_id" id="upazila_id">
                                    <option value="">Select Upazila</option>
                                    @foreach ($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" {{ Request::get('upazila_id') == $upazila->id ? 'selected' : '' }}>{{ $upazila->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="union_id">Union</label>
                                <select class="form-control select2" name="union_id" id="union_id">
                                    <option value="">Select Union</option>
                                    @foreach ($unions as $union)
                                    <option value="{{ $union->id }}" {{ Request::get('union_id') == $union->id ? 'selected' : '' }}>{{ $union->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="package_id">Package</label>
                                <select class="form-control select2" name="package_id" id="package_id">
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" {{ Request::get('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" class="form-control form-control-lg" name="search_text" id="search_text" placeholder="Type your keywords here" value="{{ Request::has('search_text') ? Request::get('search_text') : '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{ route('admin.schemes.index') }}" class="btn btn-lg btn-default">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                        <table class="table table-bordered table-hover table-head-fixed" id="packages-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Package</th>
                                    <th>District</th>
                                    <th>Upazila</th>
                                    <th>Union</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schemes as $scheme)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $scheme->name }}</td>
                                    <td>{{ $scheme->code }}</td>
                                    <td>{{ $scheme->package->name ?? 'N/A' }}</td>
                                    <td>{{ $scheme->district->name ?? 'N/A' }}</td>
                                    <td>{{ $scheme->upazila->name ?? 'N/A' }}</td>
                                    <td>{{ $scheme->union->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($scheme->is_active == 1)
                                        <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                        @else
                                        <span class="badge bg-danger" style="font-size: 100%">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.schemes.edit', $scheme->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.schemes.destroy', $scheme->id) }}"><i class="fas fa-trash"></i></a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix" style="background: #00000000">
                {{ $schemes->links() }}
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
    
        let district_url = "{{ route('common.get_districts_by_division','*') }}";
        let upazila_url = "{{ route('common.get_upazilas_by_district','*') }}";
        let union_url = "{{ route('common.get_unions_by_upazila','*') }}";

    $(document).ready(function() {
        $('.select2').select2();
    });
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

    $('#packages-table').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>


<script src="{{asset('backend/dist/js/fcpms/district.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/upazila.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/union.js')}}"></script>
@endsection