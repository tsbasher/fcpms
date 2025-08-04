@extends('backend.admin.layouts.app')
@section('title', 'BOQ Items')
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
                <h3 class="card-title">Edit BOQ Item</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.boq_items.update', $boq_item->id) }}" enctype="multipart/form-data">
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
                                <label for="boq_part_id">BOQ Part</label>
                                <select required class="form-control select2" name="boq_part_id" id="boq_part_id" placeholder="Enter BOQ Part" required>
                                    <option value="">Select BOQ Part</option>
                                    @foreach ($boq_parts as $boq_part)
                                        <option value="{{ $boq_part->id }}" @if (old('boq_part_id', $boq_item->boq_part_id) == $boq_part->id) selected @endif>{{$boq_part->code}}.{{ $boq_part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">BOQ Item Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter BOQ Item Name" value="{{ old('name', $boq_item->name) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="code">BOQ Item Code</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter BOQ Item Code" value="{{ old('code', $boq_item->code) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="specification_no">BOQ Item Specification No</label>
                                <input type="text" class="form-control" name="specification_no" id="specification_no" placeholder="Enter BOQ Item Specification No" value="{{ old('specification_no', $boq_item->specification_no) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unit_id">Unit</label>
                        <select class="form-control select2" name="unit_id" id="unit_id" placeholder="Select Unit">
                            <option value="">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" @if (old('unit_id',$boq_item->unit_id) == $unit->id) selected @endif>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $boq_item->description) }}</textarea>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $boq_item->is_active)==1) checked @endif>
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="has_sub_items" name="has_sub_items" value="1" @if (old('has_sub_items', $boq_item->has_sub_items)==1) checked @endif>
                        <label class="form-check-label" for="has_sub_items">Has Sub Items</label>
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