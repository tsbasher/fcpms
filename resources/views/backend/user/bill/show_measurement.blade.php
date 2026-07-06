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
                        <p class="text-muted text-center  m-0">{{ $bill->boq_version->name }}</p>
                        <p class="text-muted text-center  m-0">Bill No::{{ $bill->bill_no }}</p>
                        <p class="text-muted text-center">Bill Reference No::{{ $bill->bill_reference_no }}</p>


                        <a href="{{ route('user.bills.edit', $bill->id) }}" class="btn btn-warning btn-block"><b>Edit
                                Bill</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                @if ($boq_version_item)
                    <div class="card card-info">
                        <!-- /.card-header -->
                        <div class="card-header p-2">
                            Total Bill
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Quantity</strong>

                            <span class="text-muted float-right">
                                @if ($this_bill_details)
                                    {{ $this_bill_details->quantity }}
                                    ({{ number_format($boq_version_item->quantity, 3) }})
                                    {{ $boq_version_item->unit->code }}
                                @endif
                            </span>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Rate</strong>

                            <span class="text-muted float-right">
                                {{ number_format($boq_version_item->rate, 2) }}
                            </span>

                            <hr>
                            {{-- <i class="fas fa-dollar-sign"></i> --}}
                            <strong><i class="fas fa-dollar-sign mr-1"></i> Amount</strong>

                            <span class="text-muted float-right">
                                {{ number_format($boq_version_item->quantity * $boq_version_item->rate, 2) }}
                            </span>
                        </div>
                        <!-- /.card-body -->
                    </div>
                @endif

                @if ($old_bill_details && count($old_bill_details))
                    <div class="card card-warning">
                        <!-- /.card-header -->
                        <div class="card-header p-2">
                            Previous Bill Details
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Quantity</strong>

                            <span class="text-muted float-right">
                                {{ number_format($old_bill_details->sum('this_bill_quantity'), 3) }}
                                {{ $boq_version_item->unit->code }}
                            </span>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Rate</strong>

                            <span class="text-muted float-right">
                                {{ number_format($boq_version_item->rate, 2) }}
                            </span>
                            <hr>
                            {{-- <i class="fas fa-dollar-sign"></i> --}}
                            <strong><i class="fas fa-dollar-sign mr-1"></i> Amount</strong>

                            <span class="text-muted float-right">
                                {{ number_format($old_bill_details->sum('amount'), 2) }}
                            </span>

                        </div>
                        <!-- /.card-body -->
                    </div>
                @endif


                @if ($this_bill_details)
                    <div class="card card-success">
                        <!-- /.card-header -->
                        <div class="card-header p-2">
                            This Bill Details
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Quantity</strong>

                            <span class="text-muted float-right">
                                {{ $this_bill_details->quantity > 0 ? number_format($this_bill_details->quantity - $old_bill_details->sum('this_bill_quantity'), 3) : 0 }}
                                {{ $boq_version_item->unit->code }}
                            </span>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Rate</strong>

                            <span class="text-muted float-right">
                                {{ number_format($boq_version_item->rate, 2) }}
                            </span>

                            <hr>
                            <strong><i class="fas fa-dollar-sign mr-1"></i> Amount</strong>

                            <span class="text-muted float-right">
                                {{ $this_bill_details->quantity > 0 ? number_format(($this_bill_details->quantity - $old_bill_details->sum('this_bill_quantity')) * $boq_version_item->rate, 2) : 0 }}
                            </span>

                        </div>
                        <!-- /.card-body -->
                    </div>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link  click_tab"
                                    href="{{ route('user.bills.details.scheme', [$bill->id]) }}"
                                    data-toggle="tab">Scheme</a></li>
                            <li class="nav-item"><a class="nav-link  click_tab"
                                    href="{{ route('user.bills.details.boq_part', [$bill->id]) }}" data-toggle="tab">BOQ
                                    Parts</a></li>
                            {{-- <li class="nav-item"><a class="nav-link  click_tab" href="{{ route('user.bills.details.boq_item', [$bill->id]) }}" data-toggle="tab">BOQ Items</a></li>
                            <li class="nav-item"><a class="nav-link  click_tab" href="{{ route('user.bills.details.boq_subitem', [$bill->id]) }}" data-toggle="tab">BOQ Sub Items</a></li> --}}
                            <li class="nav-item"><a class="nav-link active click_tab"
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
                                        action="{{ route('user.bills.store_measurement', $bill->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="schemes">Scheme</label>
                                                        <select class="form-control select2" name="schemes" id="schemes"
                                                            placeholder="Select Schemes" required>
                                                            <option value="">Select Schemes</option>
                                                            @foreach ($schemes as $scheme)
                                                                <option value="{{ $scheme->id }}"
                                                                    @if (Request::get('schemes') == $scheme->id) selected @endif>
                                                                    {{ $scheme->code }} - {{ $scheme->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="boq_part_id">BOQ Part</label>
                                                        <select class="form-control select2" name="boq_part_id"
                                                            id="boq_part_id" placeholder="Select BOQ Parts" required>
                                                            <option value="">Select BOQ Parts</option>
                                                            @foreach ($bill_parts as $bill_part)
                                                                <option value="{{ $bill_part->boq_part->id }}"
                                                                    @if (Request::get('boq_part_id') == $bill_part->boq_part->id) selected @endif>
                                                                    {{ $bill_part->boq_part->code }} -
                                                                    {{ $bill_part->boq_part->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="boq_item_id">BOQ Item</label>
                                                        <select class="form-control select2" name="boq_item_id"
                                                            id="boq_item_id" placeholder="Select BOQ Items" required>
                                                            <option value="">Select BOQ Items</option>
                                                            @foreach ($boq_items as $bill_item)
                                                                <option value="{{ $bill_item->id }}"
                                                                    data-unit="{{ $bill_item->unit_id }}"
                                                                    data-hassub="{{ $bill_item->has_sub_items }}"
                                                                    @if (Request::get('boq_item_id') == $bill_item->id) selected @endif>
                                                                    {{ $bill_item->code }} - {{ $bill_item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="boq_subitem_id">BOQ Subitem</label>
                                                        <select class="form-control select2" name="boq_subitem_id"
                                                            id="boq_subitem_id" placeholder="Select BOQ Subitems">
                                                            <option value="">Select BOQ Subitems</option>
                                                            @foreach ($boq_subitems as $boq_subitem)
                                                                <option value="{{ $boq_subitem->id }}"
                                                                    data-unit="{{ $boq_subitem->unit_id }}"
                                                                    @if (Request::get('boq_subitem_id') == $boq_subitem->id) selected @endif>
                                                                    {{ $boq_subitem->code }} - {{ $boq_subitem->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>




                                            </hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! $measurement_view !!}
                                                </div>


                                            </div>
                                            <!-- /.card-body -->

                                            <div class="card-footer">
                                                <button type="submit" id="add-measurement" class="btn btn-primary">Add
                                                    Measurement</button>
                                            </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-head-fixed" id="boq_part-table">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Description</th>
                                                <th>Nos</th>
                                                <th>Length</th>
                                                <th>width</th>
                                                <th>Height</th>
                                                <th>Weight</th>
                                                <th>Quantity</th>
                                                <th style="width: 10px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($measurements as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->nos }} </td>
                                                    <td> {{ $item->length ?? '-' }}</td>
                                                    <td>{{ $item->width ?? '-' }}</td>
                                                    <td> {{ $item->height ?? '-' }}</td>
                                                    <td>{{ $item->weight ?? '-' }} </td>
                                                    <td> {{ $item->quantity ?? '' }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-danger remove"
                                                            data-url="{{ route('user.bills.remove_measurement', ['id' => $item->id, 'bill_id' => $bill->id]) }}">X</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-right">Total</td>
                                                <td colspan="2">{{ $measurements->sum('quantity') }}
                                            </tr>
                                        </tfoot>
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

    <script type="text/javascript">
        $(document).ready(function() {
            // $("#add-measurement").click(function(e) {
            //     e.preventDefault();
            //     debugger;
            // });
            // $("#boq_item_id option:selected").data('hassub');
            if ($("#boq_item_id option:selected").data('hassub') == 1) {
                $("#boq_subitem_id").prop('disabled', false);
            } else {
                $("#boq_subitem_id").prop('disabled', true);
            }
            $('.calculate').on('input', function() {
                debugger;
                var nos = parseFloat($('#nos').val()) || 0;
                var length = parseFloat($('#length').val()) || 1;
                var width = parseFloat($('#width').val()) || 1;
                var height = parseFloat($('#height').val()) || 1;
                var weight = parseFloat($('#weight').val()) || 1;

                var quantity = nos * length * width * height * weight;
                $('#quantity').val(quantity.toFixed(4));
            });
        });
        $(".select2").select2();


        $('#schemes').change(function(e) {
            debugger;
            var scheme = $(this).val();
            var url = window.location.href
            var param = {
                'schemes': scheme
            }
            window.location.href = generateurl(param);

        });
        $('#boq_part_id').change(function(e) {
            debugger;
            var boq_part_id = $(this).val();
            var scheme = $("#schemes").val();
            var url = window.location.href
            var param = {
                'boq_part_id': boq_part_id,
                'schemes': scheme
            }
            window.location.href = generateurl(param);

        });
        $('#boq_item_id').change(function(e) {
            debugger;
            // var hassub = $(this).data('hassub');
            // if (hassub == 1) {
            // Handle the case where the selected item has sub-items
            var boq_item_id = $(this).val();
            var boq_part_id = $("#boq_part_id").val();
            var scheme = $("#schemes").val();
            var url = window.location.href
            var param = {
                'boq_item_id': boq_item_id,
                'boq_part_id': boq_part_id,
                'schemes': scheme
            }
            window.location.href = generateurl(param);



        });
        $('#boq_subitem_id').change(function(e) {
            debugger;
            // var hassub = $(this).data('hassub');
            // if (hassub == 1) {
            // Handle the case where the selected item has sub-items
            var boq_subitem_id = $(this).val();
            var boq_item_id = $("#boq_item_id").val();
            var boq_part_id = $("#boq_part_id").val();
            var scheme = $("#schemes").val();
            var url = window.location.href
            var param = {
                'boq_subitem_id': boq_subitem_id,
                'boq_item_id': boq_item_id,
                'boq_part_id': boq_part_id,
                'schemes': scheme
            }
            window.location.href = generateurl(param);
        });


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

                                    title: 'ERROR',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(ex) {

                            debugger;
                            Swal.fire({

                                title: 'ERROR',
                                text: 'Something Went Wrong',
                                icon: 'error'
                            });
                        }
                    });
                }
                // else if (result.dismiss === Swal.DismissReason.cancel) {
                //     Swal.fire({
                //        
                //         title: 'Cancelled',
                //         text: 'Your Record is safe',
                //         icon: 'error'
                //     });
                // }
            });
        });





        function generateurl(params) {
            url = "{{ route('user.bills.details.measurement', $bill->id) }}";
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
