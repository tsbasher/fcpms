@extends('backend.admin.layouts.app')
@section('title', 'Project')
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
                            <h3 class="card-title">Add New Project</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
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
                                            <label for="name">Project Name</label>
                                            <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Project Name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="short_name">Project Short Name</label>
                                            <input type="text" required class="form-control" name="short_name" id="short_name" placeholder="Enter Project Short Name" value="{{ old('short_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="code">Project Code</label>
                                            <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Project Code" value="{{ old('code') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="budget">Project Budget</label>
                                            <input type="text" class="form-control" name="budget" id="budget" placeholder="Enter Project Budget" value="{{ old('budget') }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="approval_date">Project Approval Date</label>
                                            <input type="text" class="form-control datepicker" name="approval_date" id="approval_date" placeholder="Enter Project Approval Date" value="{{ old('approval_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planned_start_date">Project Planned Start Date</label>
                                            <input type="text" class="form-control datepicker" name="planned_start_date" id="planned_start_date" placeholder="Enter Project Planned Start Date" value="{{ old('planned_start_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planned_end_date">Project Planned End Date</label>
                                            <input type="text" class="form-control datepicker" name="planned_end_date" id="planned_end_date" placeholder="Enter Project Planned End Date" value="{{ old('planned_end_date') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="actual_start_date">Project Actual Start Date</label>
                                            <input type="text" class="form-control datepicker" name="actual_start_date" id="actual_start_date" placeholder="Enter Project Actual Start Date" value="{{ old('actual_start_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="actual_end_date">Project Actual End Date</label>
                                            <input type="text" class="form-control datepicker" name="actual_end_date" id="actual_end_date" placeholder="Enter Project Actual End Date" value="{{ old('actual_end_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="funded_by">Project Funded By</label>
                                            <input type="text" class="form-control" name="funded_by" id="funded_by" placeholder="Enter Project Funded By" value="{{ old('funded_by') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pd_name">Project PD Name</label>
                                            <input type="text" class="form-control" name="pd_name" id="pd_name" placeholder="Enter Project PD Name" value="{{ old('pd_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pd_contact_no">Project PD Contact No</label>
                                            <input type="text" class="form-control" name="pd_contact_no" id="pd_contact_no" placeholder="Enter Project PD Contact No" value="{{ old('pd_contact_no') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pd_email">Project PD Email</label>
                                            <input type="text" class="form-control" name="pd_email" id="pd_email" placeholder="Enter Project PD Email" value="{{ old('pd_email') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ministry">Executing Ministry</label>
                                            <input type="text" class="form-control" name="ministry" id="ministry" placeholder="Enter Executing Ministry" value="{{ old('ministry') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="executing_agency">Executing Agency</label>
                                            <input type="text" class="form-control" name="executing_agency" id="executing_agency" placeholder="Enter Executing Agency" value="{{ old('executing_agency') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="consulting_agency">Consulting Agency</label>
                                            <input type="text" class="form-control" name="consulting_agency" id="consulting_agency" placeholder="Enter Consulting Agency" value="{{ old('consulting_agency') }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                </div>


                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', 1) == 1) checked @endif>
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
    <script type="text/javascript">
        $(document).ready(function() {
            debugger;
            $('#description').summernote();
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endsection
