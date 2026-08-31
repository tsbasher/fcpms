@extends('backend.admin.layouts.app')
@section('title', 'Document Types')
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Edit Document Type</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.document_types.update', $document_type->id) }}">
                @csrf
                @method('PUT')
                <div class="card-body">

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
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="name">Document Type Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="e.g. Work Order" value="{{ old('name', $document_type->name) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $document_type->description) }}</textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $document_type->is_active) == 1) checked @endif>
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</section>


@endsection

@section('script')
    <script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#description').summernote();
        });
    </script>
@endsection
