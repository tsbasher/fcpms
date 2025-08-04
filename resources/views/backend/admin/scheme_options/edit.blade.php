@extends('backend.admin.layouts.app')
@section('title', 'Scheme Options')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.theme.min.css')}}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Edit Scheme Option</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.scheme_options.update',$scheme_option->id) }}" enctype="multipart/form-data">
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
                                <label for="name">Scheme Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Project Name" value="{{ old('name', $scheme_option->name) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $scheme_option->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image_url">Image</label>
                                <input type="file" class="form-control" id="image_url" name="image_url">
                            </div>
                        </div>
                        @if($scheme_option->image_url)
                        <div class="col-md-3">
                            <img src="{{ asset($scheme_option->image_url) }}" id="display_image_url" alt="Scheme Option Image" class="img-thumbnail" style="max-width: 100%; max-height: 100px;">
                            <a href="#" class="btn btn-danger" id="btn_remove_image">
                                Remove Image
                            </a>
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                        </div>
                        @endif
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $scheme_option->is_active) == 1) checked @endif>
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
<script src="{{asset('backend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        debugger;
        $('#description').summernote();
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
        $("#btn_remove_image").click(function(e) {
            e.preventDefault();


            debugger;
            Swal.fire({
                title: 'Are you sure?',
                text: "You Want to delete this image!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {


                    $("#remove_image").val(1);
                    $("#display_image_url").attr("src", "{{asset(\App\Helper\Constants::BLANK_IMAGE)}}");
                    $("#btn_remove_image").hide();

                    Swal.fire({
                        title: 'Success',
                        text: data.message,
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection