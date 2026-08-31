@extends('backend.admin.layouts.app')
@section('title', 'Bill')
@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        #modal-admin-view-document .modal-content,
        #modal-admin-view-document .modal-body {
            background: rgba(0, 0, 0, 0);
            box-shadow: none;
            border: none;
        }
        #modal-admin-view-document .modal-body {
            overflow: auto;
            height: 87vh;
        }
        #modal-admin-view-document .modal-title {
            color: #fff;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <!-- left column -->
            <!-- general form elements -->
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Bill</h2>
                    <div class="card-tools">
                        {{-- <a href="{{ route('admin.boq_versions.create') }}" class="btn btn btn-success"><i class="fa fa-plus"></i> Add</a>                     --}}
                        {{-- <a href="{{ route('admin.boq_versions.export') }}" class="btn btn btn-success"><i class="fa fa-file-export"></i> Export</a> --}}
                    </div>
                </div>
                <form method="get" target="_blank" action="{{ route('admin.bills.shelter_wise_view') }}">
                    <div class="card-body ">

                        <div class="row">

                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="package_id" class="col-sm-2 col-form-label">Package</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="package_id" placeholder="Package"
                                            name="package_id">
                                            <option value="">Select Package</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}"
                                                    data-district_id="{{ $package->district_id }}"
                                                    @if (Request::get('package_id') == $package->id) selected @endif>{{ $package->code }}
                                                    -
                                                    {{ $package->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="upazila_id" class="col-sm-2 col-form-label">Upazila</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="upazila_id" placeholder="Upazila"
                                            name="upazila_id">
                                            <option value="">Select Upazila</option>
                                            {{-- @foreach ($upazilas as $upazila)
                                                <option value="{{ $upazila->id }}"
                                                    @if (Request::get('upazila_id') == $upazila->id) selected @endif>
                                                    {{ $upazila->name }}
                                                </option>
                                            @endforeach --}}

                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <label for="bill_id" class="col-sm-2 col-form-label">Bill</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="bill_id" placeholder="Bill"
                                            name="bill_id">
                                            <option value="">Select Bill</option>
                                            {{-- @foreach ($bills as $bill)
                                                <option value="{{ $bill->id }}"
                                                    @if (Request::get('bill_id') == $bill->id) selected @endif>
                                                    {{ $bill->name }}
                                                </option>
                                            @endforeach --}}

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="report_type" class="col-sm-2 col-form-label">Report Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="report_type" placeholder="Report Type"
                                            name="report_type">
                                            <option value="">Select Report Type</option>
                                            <option value="PKG_SUM" @if (Request::get('report_type') == 'PKG_SUM') selected @endif>
                                                Package Summary</option>
                                            <option value="UPZ_DTL" @if (Request::get('report_type') == 'UPZ_DTL') selected @endif>
                                                Upazila Details</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer clearfix text-center" style="background: #00000000">
                        <div class="row">
                            <div class="col-md-6 text-right">

                                <input type="submit" class="btn btn-success" value="View Bill">
                            </div>
                            <div class="col-md-6 text-left">

                                <div id="heldup">

                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="documents-section"></div>
            </div>
        </div>
    </section>

    <!-- View Document Modal -->
    <div class="modal fade" id="modal-admin-view-document" style="background: rgba(0, 0, 0, 0.8);">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="admin-view-document-title">Document</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="admin-view-document-body" style="text-align: center;">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
    <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
        $('.select2').select2();

        $(document).ready(function() {
            $('#package_id').on('change', function() {
                var packageId = $(this).val();
                var districtId = $(this).find(':selected').data('district_id');
                $('#bill_id').empty();
                $('#bill_id').append('<option value="">Select Bill</option>');
                $('#upazila_id').empty();
                $('#upazila_id').append('<option value="">Select Upazila</option>');
                $('#documents-section').html('');
                // if (packageId) {
                $.ajax({
                    url: ("{{ route('common.get_upazilas_by_district', '*') }}").replace('*',
                        districtId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#upazila_id').empty();
                        $('#upazila_id').append('<option value="">Select Upazila</option>');
                        $.each(data, function(key, value) {
                            $('#upazila_id').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('.select2').select2();
                    }
                });

                $.ajax({
                    url: ("{{ route('admin.bills.get_bills_by_package', '*') }}").replace('*',
                        packageId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#bill_id').empty();
                        $('#bill_id').append('<option value="">Select Bill</option>');
                        $.each(data, function(key, value) {
                            $('#bill_id').append('<option value="' + value.id +
                                '" data-heldup="' + value.calculate_with_heldup +
                                '">' + value.name + ' (' + (value
                                    .calculate_with_heldup == 1 ? 'Not Verify BOQ' :
                                    'Verify BOQ') + ')' + '</option>');
                        });
                        $('.select2').select2(); // Reinitialize Select2 after updating options
                    }
                });
                // } else {
                //     $('#bill_id').empty();
                //     $('#bill_id').append('<option value="">Select Bill</option>');
                // }
            });

            $('#bill_id').on('change', function() {
                var calculateWithHeldup = $(this).find(':selected').data('heldup');
                var billId = $('#bill_id').val();
                if (!billId) {
                    $('#heldup').html('');
                    $('#documents-section').html('');
                    return; // Exit if no bill is selected
                }
                debugger;
                if (calculateWithHeldup == 0) {
                    $('#heldup').html(
                        '<a href="" id="change_status" class="btn btn-danger">Not Verify BOQ</a>'
                    );

                } else {
                    $('#heldup').html(
                        '<a href="" id="change_status" class="btn btn-warning">Verify BOQ</a>'
                    );

                }

                $.ajax({
                    url: ("{{ route('admin.bills.documents', '*') }}").replace('*', billId),
                    type: 'GET',
                    dataType: 'html',
                    success: function(html) {
                        $('#documents-section').html(html);
                    }
                });
            });
            $(document).on('click', '#change_status', function(e) {
                // $("#change_status").on('click', function(e) {
                e.preventDefault();
                var billId = $('#bill_id').val();
                if (billId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You Want to change the held-up status?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, change it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: ("{{ route('user.bills.held_up_status', '*') }}")
                                    .replace('*',
                                        billId),
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    debugger;
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(() => {
                                            $("#package_id").trigger('change'); // Trigger change event to refresh the bill list
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while updating the held-up status.',
                                    });
                                }
                            });
                        }
                    });
                } else {
                    $('#heldup').html('');
                }
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

        $(document).on('click', '.view_document', function() {
            var type = $(this).data('type');
            var url = $(this).data('url');
            var title = $(this).data('title');
            $('#admin-view-document-title').text(title);
            var body = $('#admin-view-document-body');
            body.html('');
            if (type === 'application/pdf') {
                body.html('<iframe src="' + url + '" style="width:100%;height:100%;min-height:75vh;border:none;"></iframe>');
            } else if (type.startsWith('image/')) {
                body.html('<img src="' + url + '" class="img-fluid" style="max-height:85vh;">');
            } else {
                body.html('<a href="' + url + '" target="_blank" class="btn btn-primary">Download ' + title + '</a>');
            }
            $('#modal-admin-view-document').modal('show');
        });
    </script>
@endsection
