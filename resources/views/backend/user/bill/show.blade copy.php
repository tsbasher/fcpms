@extends('backend.user.layouts.app')
@section('title', 'Bill')
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
                            <li class="nav-item"><a class="nav-link active click_tab" href="#scheme" data-toggle="tab">Scheme</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="#boq_parts" data-toggle="tab">BOQ Parts</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="#boq_items" data-toggle="tab">BOQ Items</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="#boq_sub_items" data-toggle="tab">BOQ Sub
                                    Items</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="#measurements" data-toggle="tab">Measurements</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body" style="min-height: 80vh;">
                        <div class="tab-content">
                            <div class="active tab-pane" id="scheme">
                                <div class="mb-2 text-right">
                                    <a href="{{ route('user.bills.add_scheme', $bill->id) }}" id="add_scheme"
                                        class="btn btn-success"><i class="fa fa-plus"></i> Add/Remove Scheme</a>
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
                                                <th>Remove</th>
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
                                                    <td>
                                                        <a class="btn btn-sm btn-danger remove"
                                                            data-url="{{ route('user.bills.remove_scheme', ['scheme_id' => $scheme->id, 'bill_id' => $bill->id]) }}">X</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="boq_parts">
                                <div class="mb-2 text-right">
                                    <a href="{{ route('user.bills.add_boq_parts', $bill->id) }}" id="add_boq_part"
                                        class="btn btn-success"><i class="fa fa-plus"></i> Add BOQ Part</a>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="part_scheme">Scheme</label>
                                            <select class="form-control" id="part_scheme" name="part_scheme"
                                                onchange="part_scheme_change()">
                                                <option value="">Select Scheme</option>
                                                @foreach ($bill->schemes as $scheme)
                                                    <option value="{{ $scheme->id }}"
                                                        {{ Request::get('part_scheme') == $scheme->id || Request::get('item_scheme') == $scheme->id? 'selected' : '' }}>
                                                        {{ $scheme->code }} - {{ $scheme->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                                    <td>{{ $part->scheme->name ?? 'N/A' }}</td>
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
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="boq_items">

                                <div class="mb-2 text-right">
                                    <a href="{{ route('user.bills.add_boq_parts', $bill->id) }}" id="add_boq_part"
                                        class="btn btn-success"><i class="fa fa-plus"></i> Add BOQ Part</a>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="item_scheme">Scheme</label>
                                            <select class="form-control" id="item_scheme" name="item_scheme"
                                                onchange="item_scheme_change()">
                                                <option value="">Select Scheme</option>
                                                @foreach ($bill->schemes as $scheme)
                                                    <option value="{{ $scheme->id }}"
                                                        {{ Request::get('item_scheme') == $scheme->id ? 'selected' : '' }}>
                                                        {{ $scheme->code }} - {{ $scheme->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="item_part">Part</label>
                                            <select class="form-control" id="item_part" name="item_part"
                                                onchange="item_part_change()">
                                                <option value="">Select Part</option>
                                                @if($bill->bill_parts)
                                                @foreach ($bill->bill_parts as $part)
                                                    <option value="{{ $part->id }}"
                                                        {{ Request::get('item_part') == $part->id ? 'selected' : '' }}>
                                                        {{ $part->boq_part->code ?? '' }} - {{ $part->boq_part->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-head-fixed" id="boq_part-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Scheme</th>
                                                <th>BOQ Part</th>
                                                <th>BOQ Item</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bill->bill_items as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item->scheme->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->boq_part->code ?? 'N/A' }} -
                                                        {{ $item->boq_part->name ?? '' }}</td>
                                                    <td>{{ $item->boq_item->code ?? 'N/A' }} -
                                                        {{ $item->boq_item->name ?? '' }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-danger remove"
                                                            data-url="{{ route('user.bills.remove_boq_item', ['id' => $item->id, 'bill_id' => $bill->id]) }}">X</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="boq_sub_items">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputName"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail"
                                                placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName2"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills"
                                                placeholder="Skills">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and
                                                        conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="measurements">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputName"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail"
                                                placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName2"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills"
                                                placeholder="Skills">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and
                                                        conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
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

    <script type="text/javascript">
        $(".remove").click(function() {
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


        $("#add_scheme").click(function(e) {
            e.preventDefault();
            var url = $(this).attr("href");
            debugger;
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

        $("#add_boq_part").click(function(e) {
            e.preventDefault();
            var url = $(this).attr("href");
            debugger;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    debugger;
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
        $(document).ready(function() {
            var hash = window.location.hash;
            if (hash) {
                $('a[href="' + hash + '"]').tab('show');
            }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var newTabId = $(e.target).attr('href'); // Get the ID of the new active tab
                history.pushState(null, null, newTabId); // Update the URL hash
            });

            // Optional: Handle initial page load to activate tab based on URL hash
            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="' + url.split('#')[1] + '"]').tab('show');
            }
        });

        $(".click_tab").click(function() {
            debugger;
            var url = $(this).attr("href");
            history.pushState(null, null, url);
        });
        function part_scheme_change() {
            debugger;
            var part_scheme_id = $('#part_scheme').val();
            var url=''
                param={
                    'part_scheme': part_scheme_id
                };
                url=generateurl(param);
            
            
            window.location.href = url;
        }

        function item_scheme_change() {
            debugger;
            var item_scheme_id = $('#item_scheme').val();
            var url=''
                param={
                    'item_scheme': item_scheme_id
                };
                url=generateurl(param);
            
            
            window.location.href = url;
        }
        function item_part_change() {
            debugger;
            var item_part_id = $('#item_part').val();
            var item_scheme_id = $('#item_scheme').val();
            var url=''
            if (item_part_id) {
                param={
                    'item_part': item_part_id,
                    'item_scheme': item_scheme_id
                };
                url=generateurl(param);
            }
            
            window.location.href = url;
        }
        function generateurl(params)
        {
          url = "{{ route('user.bills.show', $bill->id) }}";
          let searchParams = new URLSearchParams();
for (const key in params) {
    searchParams.append(key, params[key]);
}
var hash_part = '';
            if (document.location.toString().match('#')) {
                hash_part =  document.location.toString().split('#')[1];
            }

let newUrl = `${url}?${searchParams.toString()}#${hash_part}`;
return newUrl;
        }
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
