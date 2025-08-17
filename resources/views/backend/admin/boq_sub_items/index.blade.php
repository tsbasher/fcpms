@extends('backend.admin.layouts.app')
@section('title', 'BOQ Sub Items')
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
                <h2 class="card-title ">BOQ Sub Items</h2>
                <div class="card-tools">
                    <a href="{{ route('admin.boq_sub_items.create') }}" class="btn btn btn-secondary"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>
            <div class="card-body ">

                <form method="get">
                    <div class="row">

                        <div class="col-md-6 ">

                            <div class="form-group row">
                                <label for="boq_part_id" class="col-sm-2 col-form-label">BOQ Part</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="boq_part_id" placeholder="BOQ Part" name="boq_part_id">
                                        <option value="">Select BOQ Part</option>
                                        @foreach ($boq_parts as $part)
                                        <option value="{{ $part->id }}" @if(Request::get('boq_part_id')==$part->id) selected @endif>{{$part->code}}.{{ $part->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">

                            <div class="form-group row">
                                <label for="boq_item_id" class="col-sm-2 col-form-label">BOQ Item</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" id="boq_item_id" placeholder="BOQ Item" name="boq_item_id">
                                        <option value="">Select BOQ Item</option>
                                        @foreach ($boq_items as $item)
                                        <option value="{{ $item->id }}" @if(Request::get('boq_item_id')==$item->id) selected @endif>{{$item->code}}.{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search_text" id="search_text" placeholder="Type your keywords here" value="{{ Request::has('search_text') ? Request::get('search_text') : '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{ route('admin.boq_sub_items.index') }}" class="btn btn-default">
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
                    <table class="table table-bordered table-hover table-head-fixed" id="boq-sub-item-table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>BOQ Part</th>
                                <th>BOQ Item</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Spec. No</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boq_sub_items as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->boq_part?$item->boq_part->code.'.'.$item->boq_part->name : '' }}</td>
                                <td>{{ $item->boq_item?$item->boq_item->code.'.'.$item->boq_item->name : '' }}</td>
                                <td>{{$item->code}}.{{ $item->name }}</td>
                                <td>{{$item->unit?$item->unit->code:''}}</td>
                                <td>{{ $item->specification_no }}</td>
                                <td>
                                    @if ($item->is_active == 1)
                                    <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                    @else
                                    <span class="badge bg-danger" style="font-size: 100%">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.boq_sub_items.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.boq_sub_items.destroy', $item->id) }}"><i class="fas fa-trash"></i></a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix" style="background: #00000000">
                {{ $boq_sub_items->links() }}
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
    let get_boq_item_by_boq_part_url="{{route('common.get_boq_items_by_part','*')}}";
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
<script src="{{asset('backend/dist/js/fcpms/boq_item.js')}}"></script>
@endsection