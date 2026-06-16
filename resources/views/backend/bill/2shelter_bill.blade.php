@extends('backend.user.layouts.app')
@section('title', 'Bill')
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
                <h2 class="card-title ">Shelter Wise Bill </h2>
                <div class="card-tools">
                    
                </div>
            </div>
            <div class="card-body ">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-head-fixed" id="boq-version-table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>BOQ Version</th>
                                <th>Bill Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bills as $bill)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $bill->name }}</td>
                                <td>{{$bill->boq_version?$bill->boq_version->name : ''}}</td>
                                <td>{{ $bill->bill_date }}</td>

                                <td><span class="badge bg-secondary" style="font-size: 100%">{{$bill->status}}</span></td>
                                <td>
                                    <a href="{{ route('user.bills.edit', $bill->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('user.bills.destroy', $bill->id) }}"><i class="fas fa-trash"></i></a>
                                    <a href="{{ route('user.bills.show', $bill->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix" style="background: #00000000">
                
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