@extends('backend.admin.layouts.app')
@section('title', 'Scheme')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Scheme</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.schemes.store') }}" enctype="multipart/form-data">
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

                            <div class="form-group">
                                <label for="name">Scheme Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Scheme Name" value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">Scheme Code</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Scheme Code" value="{{ old('code') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="alias">Scheme Alias</label>
                                <input type="text" class="form-control" name="alias" id="alias" placeholder="Enter Scheme Alias" value="{{ old('alias') }}">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="package_id">Package</label>
                                <select name="package_id" id="package_id" class="form-control select2" required>
                                    <option value="">Select Package</option>
                                    @foreach($packages as $package)
                                    <option value="{{ $package->id }}" @if(old('package_id')==$package->id) selected @endif>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="division_id">Division</label>
                                <select name="division_id" id="division_id" class="form-control select2">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" @if(old('division_id')==$division->id) selected @endif>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="district_id">District</label>
                                <select name="district_id" id="district_id" class="form-control select2">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}" @if(old('district_id')==$district->id) selected @endif>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="upazila_id">Upazila</label>
                                <select name="upazila_id" id="upazila_id" class="form-control select2">
                                    <option value="">Select Upazila</option>
                                    @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" @if(old('upazila_id')==$upazila->id) selected @endif>{{ $upazila->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="union_id">Union</label>
                                <select name="union_id" id="union_id" class="form-control select2">
                                    <option value="">Select Union</option>
                                    @foreach($unions as $union)
                                    <option value="{{ $union->id }}" @if(old('union_id')==$union->id) selected @endif>{{ $union->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="village_name">Village Name</label>
                                <input type="text" class="form-control" name="village_name" id="village_name" placeholder="Enter Village Name" value="{{ old('village_name') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="scheme_option_id">Scheme Option</label>
                                <select name="scheme_option_id" id="scheme_option_id" class="form-control select2">
                                    <option value="">Select Scheme Option</option>
                                    @foreach($schemeOptions as $option)
                                    <option value="{{ $option->id }}" @if(old('scheme_option_id')==$option->id) selected @endif>{{ $option->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_start_date">Scheme Planned Start Date</label>
                                <input type="text" class="form-control datepicker" name="planned_start_date" id="planned_start_date" placeholder="Enter Scheme Planned Start Date" value="{{ old('planned_start_date') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_end_date">Scheme Planned End Date</label>
                                <input type="text" class="form-control datepicker" name="planned_end_date" id="planned_end_date" placeholder="Enter Scheme Planned End Date" value="{{ old('planned_end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_start_date">Scheme Actual Start Date</label>
                                <input type="text" class="form-control datepicker" name="actual_start_date" id="actual_start_date" placeholder="Enter Scheme Actual Start Date" value="{{ old('actual_start_date') }}">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_end_date">Scheme Actual End Date</label>
                                <input type="text" class="form-control datepicker" name="actual_end_date" id="actual_end_date" placeholder="Enter Scheme Actual End Date" value="{{ old('actual_end_date') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_budget">Package Planned Budget</label>
                                <input type="text" class="form-control" name="planned_budget" id="planned_budget" placeholder="Enter Package Planned Budget" value="{{ old('planned_budget') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_budget">Package Actual Budget</label>
                                <input type="text" class="form-control" name="actual_budget" id="actual_budget" placeholder="Enter Package Actual Budget" value="{{ old('actual_budget') }}">
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="external_code">Scheme External Code</label>
                                <input type="text" class="form-control" name="external_code" id="external_code" placeholder="Enter Scheme External Code" value="{{ old('external_code') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Enter Latitude" value="{{ old('latitude') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Enter Longitude" value="{{ old('longitude') }}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', 1)==1) checked @endif>
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
<script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
<script type="text/javascript">
    
        let district_url = "{{ route('common.get_districts_by_division','*') }}";
        let upazila_url = "{{ route('common.get_upazilas_by_district','*') }}";
        let union_url = "{{ route('common.get_unions_by_upazila','*') }}";

    $(document).ready(function() {
        debugger;
        $(".select2").select2();
        $('#description').summernote();
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    });
</script>

<script src="{{asset('backend/dist/js/fcpms/district.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/upazila.js')}}"></script>
<script src="{{asset('backend/dist/js/fcpms/union.js')}}"></script>
@endsection