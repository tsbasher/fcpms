@extends('backend.admin.layouts.app')
@section('title', 'BOQ Versions Export/Import')
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
                <h2 class="card-title ">BOQ Version Export/Import</h2>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body ">

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