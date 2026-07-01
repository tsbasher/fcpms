@extends('backend.admin.layouts.app')
@section('title', 'BOQ Version Export')
@section('style')

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
                    <h2 class="card-title ">BOQ Version Export</h2>
                    <div class="card-tools">

                    </div>
                </div>
                <form method="get" action="{{ route('admin.boq_versions.export_data') }}" target="_blank"
                    enctype="multipart/form-data">
                    <div class="card-body ">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group row">
                                    <label for="package_id" class="col-sm-2 col-form-label">Package</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="package_id" placeholder="Package"
                                            name="package_id" >
                                            <option value="">Select Package</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}"
                                                    @if (Request::get('package_id') == $package->id) selected @endif>
                                                    {{ $package->code }}.{{ $package->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="boq_version_id" class="col-sm-2 col-form-label">BOQ Version</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" id="boq_version_id" placeholder="BOQ Version"
                                            name="boq_version_id">
                                            <option value="">Select BOQ Version</option>
                                            @foreach ($boq_versions as $version)
                                                <option value="{{ $version->id }}"
                                                    @if (Request::get('boq_version_id') == $version->id) selected @endif>
                                                    {{ $version->name }}({{ $version->version_date }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer clearfix" style="background: #00000000">
                        <button type="submit" class="btn btn-primary">Export</button>

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
    {{-- <script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script> --}}
    <script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>

    <script type="text/javascript">
        {{-- let get_boq_item_by_boq_part_url="{{route('common.get_boq_items_by_part','*')}}";
    let get_boq_sub_item_by_boq_item_url="{{route('common.get_boq_sub_items_by_boq_item','*')}}";--}}
    let get_boq_versions_by_package_url = "{{ route('common.get_boq_versions_by_package', '*') }}"; 
        $(".select2").select2();
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



        $('#boq-sub-item-table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    </script>
<script src="{{asset('backend/dist/js/fcpms/boq_version.js')}}"></script>
    {{-- <script src="{{asset('backend/dist/js/fcpms/boq_sub_item.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/boq_item.js')}}"></script> --}}
@endsection
