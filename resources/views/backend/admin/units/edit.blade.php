@extends('backend.admin.layouts.app')
@section('title', 'Units')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Unit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.units.update', $unit->id) }}" enctype="multipart/form-data">
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
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">Unit Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Unit Name" value="{{ old('name', $unit->name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="code">Unit Code</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Unit Code" value="{{ old('code', $unit->code) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fields">Fields</label>
                        <select required class="form-control select2" name="fields[]" id="fields" placeholder="Select Field" multiple>

                            <option value="length" @if (in_array('length', old('fields',$unit->fields)) ) selected @endif>Length</option>
                            <option value="width" @if (in_array('width', old('fields',$unit->fields)) ) selected @endif>Width</option>
                            <option value="height" @if (in_array('height', old('fields',$unit->fields)) ) selected @endif>Height</option>
                            <option value="weight" @if (in_array('weight', old('fields',$unit->fields)) ) selected @endif>Weight</option>
                            <option value="piece" @if (in_array('piece', old('fields',$unit->fields)) ) selected @endif>Piece</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $unit->description) }}</textarea>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $unit->is_active)==1) checked @endif>
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
<script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        debugger;
        $('#description').summernote();
        $('.select2').select2();
    });
</script>
@endsection