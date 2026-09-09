@extends('backend.user.layouts.app')
@section('title', 'Bill')
@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
        #modal-view-document .modal-content,
        #modal-view-document .modal-body {
            background: rgba(0, 0, 0, 0);
            box-shadow: none;
            border: none;
        }
        #modal-view-document .modal-dialog {
        }
        #modal-view-document .modal-body {
            overflow: auto;
            height: 87vh;
        }
        .modal-title
        {
            color: #fff;
            font-weight: bold;
        }
    </style>
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
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link  click_tab" href="{{ route('user.bills.details.scheme', [$bill->id]) }}" data-toggle="tab">Scheme</a></li>
                            <li class="nav-item"><a class="nav-link  click_tab" href="{{ route('user.bills.details.boq_part', [$bill->id]) }}" data-toggle="tab">BOQ Parts</a></li>
                            <li class="nav-item"><a class="nav-link click_tab" href="{{ route('user.bills.details.measurement', [$bill->id]) }}" data-toggle="tab">Measurements</a></li>
                            <li class="nav-item"><a class="nav-link active click_tab" href="{{ route('user.bills.details.documents', [$bill->id]) }}" data-toggle="tab">Documents</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body" style="min-height: 80vh;">
                        <div class="tab-content">
                            <div class="active tab-pane" id="documents">
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

                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Upload Document</h3>
                                    </div>
                                    <form role="form" method="POST" action="{{ route('user.bills.documents.store', $bill->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="document_type_id">Document Type</label>
                                                        <select class="form-control select2" name="document_type_id" id="document_type_id" required>
                                                            <option value="">Select Type</option>
                                                            @foreach ($document_types as $document_type)
                                                                <option value="{{ $document_type->id }}" @if (old('document_type_id') == $document_type->id) selected @endif>{{ $document_type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">Title (optional)</label>
                                                        <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file">File</label>
                                                        <input type="file" class="form-control" name="file" id="file" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks (optional)</label>
                                                        <input type="text" class="form-control" name="remarks" id="remarks" value="{{ old('remarks') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-muted">Allowed: pdf, jpg, png, doc, docx, xls, xlsx (max 20MB)</small>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Uploaded Documents</h3>
                                    </div>
                                    <div class="card-body">
                                        @if ($documents->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-head-fixed" id="document-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>Document Type</th>
                                                            <th>Title / File</th>
                                                            <th>Uploaded At</th>
                                                            <th>Action</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($documents as $document)
                                                            <tr>
                                                                <td>{{ $loop->index + 1 }}</td>
                                                                <td>{{ $document->document_type->name ?? 'N/A' }}</td>
                                                                <td>{{ $document->title ?: $document->original_name }}
                                                                    <small class="d-block text-muted">{{ $document->original_name }} ({{ round($document->file_size / 1024, 2) }} KB)</small>
                                                                </td>
                                                                <td>{{ $document->created_at ? $document->created_at->format('d M Y h:i A') : '-' }}</td>
                                                                <td>
                                                                    @php
                                                                        $document_url = Storage::url($document->file_path);
                                                                        $previewable = str_starts_with($document->mime_type, 'image/') || in_array($document->mime_type, ['application/pdf', 'application/x-pdf']);
                                                                    @endphp
                                                                    @if ($previewable)
                                                                        <button type="button" class="btn btn-sm btn-primary view_document" data-type="{{ $document->mime_type }}" data-url="{{ $document_url }}" data-title="{{ $document->title ?: $document->original_name }}"><i class="fa fa-eye"></i> View</button>
                                                                    @endif
                                                                    <a href="{{ $document_url }}" download="{{ $document->original_name }}" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>
                                                                </td>
                                                                <td>
                                                                    <form action="{{ route('user.bills.documents.destroy', $document->id) }}" method="POST" class="delete-document-form" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-document" data-url="{{ route('user.bills.documents.destroy', $document->id) }}"><i class="fa fa-trash"></i> Delete</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No documents uploaded yet.</p>
                                        @endif
                                    </div>
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

    <!-- View Document Modal -->
    <div class="modal fade" id="modal-view-document" style="background: rgba(0, 0, 0, 0.8);">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-view-document-title">Document</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view-document-body" style="text-align: center;">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>

    <script type="text/javascript">
        $(".select2").select2();

        $(".click_tab").click(function(e) {
            e.preventDefault();
            var url = $(this).attr("href")
            window.location = url;
        });

        $(document).on('click', '.btn-delete-document', function() {
            var url = $(this).data('url');
            var $btn = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this document?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $btn.closest('tr').remove();
                                Swal.fire({ icon: 'success', title: 'Success', text: response.message, timer: 1500, showConfirmButton: false });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                            }
                        },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting the document.' });
                        }
                    });
                }
            });
        });

        $(".view_document").click(function() {
            var url = $(this).data('url');
            var type = $(this).data('type');
            var title = $(this).data('title') || 'Document';
            var body = '';

            $("#modal-view-document-title").text(title);

            if (type.indexOf('image/') === 0) {
                body = '<img src="' + url + '" class="img-fluid" style="max-height: 98%;">';
            } else if (type === 'application/pdf' || type === 'application/x-pdf') {
                body = '<iframe src="' + url + '" style="width:100%;height:98%;border:none;"></iframe>';
            } else {
                body = '<p>This file type cannot be previewed in the browser.</p>' +
                       '<a href="' + url + '" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i> Download</a>';
            }

            $("#view-document-body").html(body);
            $('#modal-view-document').modal('show');
        });

        $('#document-table').DataTable({
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
