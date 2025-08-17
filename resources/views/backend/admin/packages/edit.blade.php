@extends('backend.admin.layouts.app')
@section('title', 'Package')
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
                <h3 class="card-title">Edit Package</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.packages.update', $package->id) }}" enctype="multipart/form-data">
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
                                <label for="name">Package Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Package Name" value="{{ old('name', $package->name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alias">Package Alias</label>
                                <input type="text" required class="form-control" name="alias" id="alias" placeholder="Enter Package Alias" value="{{ old('alias', $package->alias) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">Package Code</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Package Code" value="{{ old('code', $package->code) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bid_invitation_date">Package Bid Invitation Date</label>
                                <input type="text" class="form-control datepicker" name="bid_invitation_date" id="bid_invitation_date" placeholder="Enter Package Bid Invitation Date" value="{{ old('bid_invitation_date', $package->bid_invitation_date) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bid_submission_date">Package Bid Submission Date</label>
                                <input type="text" class="form-control datepicker" name="bid_submission_date" id="bid_submission_date" placeholder="Enter Package Bid Submission Date" value="{{ old('bid_submission_date', $package->bid_submission_date) }}">
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="division_id">Package Division</label>
                                <select name="division_id" id="division_id" class="form-control select2" required>
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" @if(old('division_id', $package->division_id)==$division->id) selected @endif>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="region_id">Package Region</label>
                                <select name="region_id" id="region_id" class="form-control select2">
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}" @if(old('region_id', $package->region_id)==$region->id) selected @endif>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="district_id">Package District</label>
                                <select name="district_id" id="district_id" class="form-control select2">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}" @if(old('district_id', $package->district_id) == $district->id) selected @endif>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_start_date">Package Planned Start Date</label>
                                <input type="text" class="form-control datepicker" name="planned_start_date" id="planned_start_date" placeholder="Enter Package Planned Start Date" value="{{ old('planned_start_date', $package->planned_start_date) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_end_date">Package Planned End Date</label>
                                <input type="text" class="form-control datepicker" name="planned_end_date" id="planned_end_date" placeholder="Enter Package Planned End Date" value="{{ old('planned_end_date', $package->planned_end_date) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_start_date">Package Actual Start Date</label>
                                <input type="text" class="form-control datepicker" name="actual_start_date" id="actual_start_date" placeholder="Enter Package Actual Start Date" value="{{ old('actual_start_date', $package->actual_start_date) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_end_date">Package Actual End Date</label>
                                <input type="text" class="form-control datepicker" name="actual_end_date" id="actual_end_date" placeholder="Enter Package Actual End Date" value="{{ old('actual_end_date', $package->actual_end_date) }}">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="planned_budget">Package Planned Budget</label>
                                <input type="text" class="form-control" name="planned_budget" id="planned_budget" placeholder="Enter Package Planned Budget" value="{{ old('planned_budget', $package->planned_budget) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="actual_budget">Package Actual Budget</label>
                                <input type="text" class="form-control" name="actual_budget" id="actual_budget" placeholder="Enter Package Actual Budget" value="{{ old('actual_budget', $package->actual_budget) }}">
                            </div>
                        </div>

                    </div>


                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description', $package->description) }}</textarea>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $package->is_active) == 1) checked @endif>
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
@endsection