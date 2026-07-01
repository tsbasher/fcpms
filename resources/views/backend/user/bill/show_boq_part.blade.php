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
                            <li class="nav-item"><a class="nav-link  click_tab"
                                    href="{{ route('user.bills.details.scheme', [$bill->id]) }}"
                                    data-toggle="tab">Scheme</a></li>
                            <li class="nav-item"><a class="nav-link active click_tab"
                                    href="{{ route('user.bills.details.boq_part', [$bill->id]) }}" data-toggle="tab">BOQ
                                    Parts</a></li>
                            {{-- <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.boq_item', [$bill->id]) }}" data-toggle="tab">BOQ Items</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.boq_subitem', [$bill->id]) }}" data-toggle="tab">BOQ Sub
                                    Items</a></li> --}}
                            <li class="nav-item"><a class="nav-link click_tab"
                                    href="{{ route('user.bills.details.measurement', [$bill->id]) }}"
                                    data-toggle="tab">Measurements</a>
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
                                    <form role="form" method="POST"
                                        action="{{ route('user.bills.store_boq_parts', $bill->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="schemes">Scheme</label>
                                                <select class="form-control select2" name="schemes" id="schemes"
                                                    placeholder="Select Schemes" required>
                                                    <option value="">Select Schemes</option>
                                                    <option value="All" @if (Request::get('schemes') == "All") selected @endif>All Schemes</option>
                                                    @foreach ($schemes as $scheme)
                                                        <option value="{{ $scheme->id }}"
                                                            @if (Request::get('schemes') == $scheme->id) selected @endif>
                                                            {{ $scheme->code }} - {{ $scheme->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="boq_part_id">BOQ Part</label>
                                                <select class="form-control" multiple="multiple" name="boq_parts[]"
                                                    id="boq_parts" placeholder="Select BOQ Parts" required>

                                                    @foreach ($boq_parts as $boq_part)
                                                        <option value="{{ $boq_part->id }}"
                                                            @if (Request::has('schemes') &&
                                                                    Request::get('schemes') != null &&
                                                                    in_array($boq_part->id, $bill->bill_parts->pluck('boq_part_id')->toarray())) selected @endif>
                                                            {{ $boq_part->code }} - {{ $boq_part->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-head-fixed" id="boq_part-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Scheme</th>
                                                <th>BOQ Part</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bill->bill_parts as $part)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $part->scheme->code ?? 'N/A' }} - {{ $part->scheme->name ?? 'N/A' }}</td>
                                                    <td>{{ $part->boq_part->code ?? 'N/A' }} -
                                                        {{ $part->boq_part->name ?? '' }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-danger remove"
                                                            data-url="{{ route('user.bills.remove_boq_part', ['id' => $part->id, 'bill_id' => $bill->id]) }}">X</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


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

            var dual = $('select[name="boq_parts[]"]').bootstrapDualListbox({
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
        $(".rrrrrremove").click(function() {
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
                        type: 'Get',
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

        $('#schemes').change(function(e) {
            debugger;
            var scheme = $(this).val();
            var url = window.location.href
            var param = {
                'schemes': scheme
            }
            window.location.href = generateurl(param);

        });

        function generateurl(params) {
            url = "{{ route('user.bills.details.boq_part', $bill->id) }}";
            let searchParams = new URLSearchParams();
            for (const key in params) {
                searchParams.append(key, params[key]);
            }
            // var hash_part = '';
            // if (document.location.toString().match('#')) {
            //     hash_part = document.location.toString().split('#')[1];
            // }

            let newUrl = `${url}?${searchParams.toString()}`;
            return newUrl;
        }
        $(".click_tab").click(function(e) {
            e.preventDefault();
            var url = $(this).attr("href")
            debugger;
            window.location = url;
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
