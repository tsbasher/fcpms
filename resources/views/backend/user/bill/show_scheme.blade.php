@extends('backend.user.layouts.app')
@section('title', 'Bill')
@section('style')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">

                    <div class="card-body box-profile">

                        <h3 class="profile-username text-center">{{ $bill->name }}</h3>

                        <p class="text-muted text-center m-0">{{ $bill->bill_date }}</p>
                        <p class="text-muted text-center">{{ $bill->boq_version->name }}</p>


                        <a href="{{ route('user.bills.edit', $bill->id) }}" class="btn btn-warning btn-block"><b>Edit
                                Bill</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Bill No</strong>

                        <p class="text-muted">
                            {{ $bill->bill_no }}
                        </p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Bill Reference No</strong>

                        <p class="text-muted">
                            {{ $bill->bill_reference_no }}
                        </p>

                        <hr>
                        <!--
                      <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                      <p class="text-muted">
                        <span class="tag tag-danger">UI Design</span>
                        <span class="tag tag-success">Coding</span>
                        <span class="tag tag-info">Javascript</span>
                        <span class="tag tag-warning">PHP</span>
                        <span class="tag tag-primary">Node.js</span>
                      </p>

                      <hr>

                      <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                      <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active click_tab" href="{{ route('user.bills.details.scheme',[$bill->id]) }}" data-toggle="tab">Scheme</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.boq_part',[$bill->id]) }}" data-toggle="tab">BOQ Parts</a></li>
                            {{-- <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.boq_item',[$bill->id]) }}" data-toggle="tab">BOQ Items</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.boq_subitem',[$bill->id]) }}" data-toggle="tab">BOQ Sub
                                    Items</a></li> --}}
                            <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.measurement',[$bill->id]) }}" data-toggle="tab">Measurements</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body" style="min-height: 80vh;">
                        <div class="tab-content">
                            <div class="active tab-pane" id="scheme">
                                <div class="row">
                    <div class="col-md-12">
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible">{{ $message }}</div>
                        @endif
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible">{{ $message }}</div>
                        @endif

                    </div>
                    <div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                                <div class="mb-2">
                                    <form role="form" method="POST" action="{{ route('user.bills.store_scheme', $bill->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="scheme_id">Scheme</label>
                                                <select class="form-control" multiple="multiple" name="schemes[]" id="schemes" placeholder="Select Schemes">
                                                    
                                                    @foreach ($schemes as $scheme)
                                                        <option value="{{ $scheme->id }}" @if (old('schemes', $bill->schemes->pluck('id')->toArray()) && in_array($scheme->id, old('schemes', $bill->schemes->pluck('id')->toArray()))) selected @endif>{{ $scheme->code }} -{{ $scheme->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Save Scheme</button>
                                        </div>
                                    </form>
                                </div>
                                {{-- 
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bill->schemes as $scheme)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $scheme->name }}</td>
                                                    <td>{{ $scheme->code }}</td>
                                                    <td>{{ $scheme->package->name ?? 'N/A' }}</td>
                                                    <td>{{ $scheme->district->name ?? 'N/A' }}</td>
                                                    <td>{{ $scheme->upazila->name ?? 'N/A' }}</td>
                                                    <td>{{ $scheme->union->name ?? 'N/A' }}</td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

 --}}
                            </div>


                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>


    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="add-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>

    <script type="text/javascript">
    
$(document).ready(function() {

            var dual = $('select[name="schemes[]"]').bootstrapDualListbox({
                 nonSelectedListLabel: 'Non-selected',
                 selectedListLabel: 'Selected',
                // preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
            });

            //     dual.bootstrapDualListbox('refresh', true);
            //     $('form').on('submit', function() {
            //         dual.bootstrapDualListbox('refresh', true);
            // });
        });


        $(".rrrrremove").click(function() {
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
                        type: 'Get',
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


        $("#add_scheme").click(function(e) {
            e.preventDefault();
            var url = $(this).attr("href");
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $("#add-body").html(data);
                    $('.select2').select2();
                }
            });
            $("#modal-add").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });
$(".click_tab").click(function(e)
{
    e.preventDefault();
    var url=$(this).attr("href")
    
    window.location=url;
}
);
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
