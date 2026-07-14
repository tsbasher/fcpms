@extends('backend.user.layouts.app')
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
                <form method="get" target="_blank" action="{{ route('user.bills.shelter_wise_view') }}">
                    <div class="card-body ">

                        <div class="row">


                            <div class="col-md-4 ">
                                <div class="form-group row">
                                    <label for="upazila_id" class="col-sm-2 col-form-label">Upazila</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="upazila_id" placeholder="Upazila" required
                                            name="upazila_id">
                                            <option value="">Select Upazila</option>
                                            @foreach ($upazilas as $upazila)
                                                <option value="{{ $upazila->id }}"
                                                    @if (Request::get('upazila_id') == $upazila->id) selected @endif>
                                                    {{ $upazila->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group row">
                                    <label for="bill_id" class="col-sm-2 col-form-label">Bill</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="bill_id" placeholder="Bill" required   
                                            name="bill_id">
                                            <option value="">Select Bill</option>
                                            @foreach ($bills as $bill)
                                                <option value="{{ $bill->id }}"
                                                    @if (Request::get('bill_id') == $bill->id) selected @endif>
                                                    {{ $bill->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 ">
                                <div class="form-group row">
                                    <label for="report_type" class="col-sm-3 col-form-label">Report Type</label>
                                    <div class="col-sm-9">
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
                        <input type="submit" class="btn btn-success" value="View Bill">
                    </div>
                </form>
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
