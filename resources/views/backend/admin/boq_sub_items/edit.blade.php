@extends('backend.admin.layouts.app')
@section('title', 'BOQ Sub Items')
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
                <h3 class="card-title">Edit BOQ Sub Item</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.boq_sub_items.update', $boq_sub_item->id) }}" enctype="multipart/form-data">
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
                                    <option value="{{ $boq_part->id }}" @if (old('boq_part_id', $boq_sub_item->boq_part_id)==$boq_part->id) selected @endif>{{ $boq_part->code }}.{{ $boq_part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="boq_item_id">BOQ Item</label>
                                <select required class="form-control select2" name="boq_item_id" id="boq_item_id" placeholder="Enter BOQ Item" required>
                                    <option value="">Select BOQ Item</option>
                                    @foreach ($boq_items as $boq_item)
                                    <option value="{{ $boq_item->id }}" @if (old('boq_item_id', $boq_sub_item->boq_item_id)==$boq_item->id) selected @endif>{{ $boq_item->code }}.{{ $boq_item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">BOQ Sub Item Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter BOQ Sub Item Name" value="{{ old('name', $boq_sub_item->name) }}">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="code">BOQ Item Code</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter BOQ Item Code" value="{{ old('code', $boq_sub_item->code) }}">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="specification_no">BOQ Sub Item Specification No</label>
                                <input type="text" class="form-control" name="specification_no" id="specification_no" placeholder="Enter BOQ Sub Item Specification No" value="{{ old('specification_no', $boq_sub_item->specification_no) }}">
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <select class="form-control select2" name="unit_id" id="unit_id" placeholder="Select Unit" required>
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @if (old('unit_id',$boq_sub_item->unit_id)==$unit->id) selected @endif>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $boq_sub_item->description) }}</textarea>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $boq_sub_item->is_active)==1) checked @endif>
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
    let get_boq_item_by_boq_part_url = "{{route('common.get_boq_items_by_part','*')}}";
    $(document).ready(function() {
        debugger;
        $('#description').summernote();
        $('.select2').select2();
    });
</script>

<script src="{{asset('backend/dist/js/fcpms/boq_item.js')}}"></script>
@endsection