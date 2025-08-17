@extends('backend.admin.layouts.app')
@section('title', 'BOQ Version Details')
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
                <h3 class="card-title">Add New BOQ Version Detail</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" id="boq_version_detail_form" action="{{ route('admin.boq_version_details.store') }}" enctype="multipart/form-data">
                @csrf
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
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible">{{ $message }}</div>
                        @endif
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible">{{ $message }}</div>
                        @endif

                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="package_id">Package</label>
                                <select class="form-control select2" id="package_id" placeholder="Package" name="package_id" required>
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" @if(old('package_id',$boq_version?$boq_version->package_id:null)==$package->id) selected @endif>{{$package->code}}.{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="boq_version_id">BOQ Version</label>
                                <select class="form-control select2" id="boq_version_id" placeholder="BOQ Version" name="boq_version_id" required>
                                    <option value="">Select BOQ Version</option>
                                    @foreach ($boq_versions as $version)
                                    <option value="{{ $version->id }}" @if(old('boq_version_id',$boq_version?$boq_version->id:null)==$version->id) selected @endif>{{$version->code}}.{{ $version->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="scheme_option_id">Scheme Option</label>
                                <select class="form-control select2" id="scheme_option_id" placeholder="Scheme Option" name="scheme_option_id" required>
                                    <option value="">Select Scheme Option</option>
                                    @foreach ($scheme_options as $option)
                                    <option value="{{ $option->id }}" @if(old('scheme_option_id',$boq_version_detail?$boq_version_detail->scheme_option_id:null)==$option->id) selected @endif>{{ $option->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="boq_part_id">BOQ Part</label>
                                <select class="form-control select2" name="boq_part_id" id="boq_part_id" placeholder="Enter BOQ Part" required>
                                    <option value="">Select BOQ Part</option>
                                    @foreach ($boq_parts as $boq_part)
                                    <option value="{{ $boq_part->id }}" @if (old('boq_part_id',$boq_version_detail?$boq_version_detail->boq_part_id:null)==$boq_part->id) selected @endif>{{$boq_part->code}}.{{ $boq_part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="boq_item_id">BOQ Item</label>
                                <select class="form-control select2" name="boq_item_id" id="boq_item_id" placeholder="Enter BOQ Item" required>
                                    <option value="">Select BOQ Item</option>
                                    @foreach ($boq_items as $boq_item)
                                    <option value="{{ $boq_item->id }}" data-sub_item="{{$boq_item->has_sub_items}}" @if (old('boq_item_id',$boq_version_detail?$boq_version_detail->boq_item_id:null)==$boq_item->id) selected @endif>{{$boq_item->code}}.{{ $boq_item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="boq_sub_item_id">BOQ Sub Item</label>
                                <select class="form-control select2" name="boq_sub_item_id" id="boq_sub_item_id" placeholder="Enter BOQ Sub Item">
                                    <option value="">Select BOQ Sub Item</option>
                                    @foreach ($boq_sub_items as $boq_sub_item)
                                    <option value="{{ $boq_sub_item->id }}" @if (old('boq_sub_item_id',$boq_version_detail?$boq_version_detail->boq_sub_item_id:null)==$boq_sub_item->id) selected @endif>{{$boq_sub_item->code}}.{{ $boq_sub_item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <input type="hidden" name="unit_id" id="unit_id" value="{{ old('unit_id',$boq_version_detail->unit_id) }}">
                                <input type="text" readonly class="form-control" name="unit_name" id="unit_name" placeholder="Enter Unit Name" value="{{ old('unit_name',$boq_version_detail->unit->name) }}">
                                
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" required class="form-control" name="quantity" id="quantity" placeholder="Enter Quantity" value="{{ old('quantity',$boq_version_detail?$boq_version_detail->quantity:0) }}">
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="rate">Rate</label>
                                <input type="text" required class="form-control" name="rate" id="rate" placeholder="Enter Rate" value="{{ old('rate',$boq_version_detail?$boq_version_detail->rate:0) }}">
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="hidden" id="existing_check" name="existing_check" value="0">
                    <button type="submit" class="btn btn-primary" id="btn_submit">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</section>


@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
<script type="text/javascript">
    let get_boq_item_by_boq_part_url = "{{route('common.get_boq_items_by_part','*')}}";
    let get_boq_sub_item_by_boq_item_url = "{{route('common.get_boq_sub_items_by_boq_item','*')}}";
    let get_boq_versions_by_package_url = "{{ route('common.get_boq_versions_by_package', '*') }}";
    $(document).ready(function() {
        debugger;
        $('#description').summernote();
        $('.select2').select2();

        $('#btn_submit').on('click', function(e) {

            e.preventDefault();
            var package_id = $('#package_id').val();
            var boq_version_id = $('#boq_version_id').val();
            var boq_item_id = $('#boq_item_id').val();
            var boq_sub_item_id = $('#boq_sub_item_id').val();
            var boq_part_id = $('#boq_part_id').val();
            var scheme_option_id = $('#scheme_option_id').val();
            debugger;
            $.ajax({
                url: "{{route('admin.check_existing_boq_version_details')}}",
                type: 'GET',
                data: {
                    "package_id": package_id,
                    "boq_version_id": boq_version_id,
                    "boq_item_id": boq_item_id,
                    "boq_sub_item_id": boq_sub_item_id,
                    "boq_part_id": boq_part_id,
                    "scheme_option_id": scheme_option_id
                },
                success: function(data) {
                    debugger;
                    // var data = JSON.parse(response);
                    if (data.status == 1) {
                        Swal.fire({
                            title: 'Warning',
                            text: data.message,
                            icon: 'warning'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            }
                        });
                    } else {


                        $('#boq_version_detail_form').submit();
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
            // Add any additional validation or processing here if needed


        });
    });
</script>

<script src="{{asset('backend/dist/js/fcpms/boq_sub_item.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/boq_version.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/boq_item.js')}}"></script>
@endsection