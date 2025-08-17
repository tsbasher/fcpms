{{--@extends('backend.admin.layouts.app')
@section('title', 'Add package to Contractor')
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
                <h3 class="card-title">Add New Package to Contractor</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.contractors.add_package', $contractor->id) }}" enctype="multipart/form-data">
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
                                <label for="name">Contractor Name</label>
                                <input type="text" readonly class="form-control" name="name" id="name" placeholder="Enter Contractor Name" value="{{ $contractor->company_name }}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="package_id">Package</label>
                        <select required class="form-control select2" name="package_id" id="package_id" placeholder="Select Package">
                            <option value="">Select Package</option>
                            @foreach ($packages as $package)
                            <option value="{{ $package->id }}" @if ($package->id == old('package_id', $contractor->packages->first()->id ?? null)) selected @endif>{{ $package->name }}</option>
                            @endforeach
                        </select>
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
--}}

<form role="form" method="POST" action="{{ route('admin.contractors.add_package', $contractor->id) }}" enctype="multipart/form-data">
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
                                <label for="name">Contractor Name</label>
                                <input type="text" readonly class="form-control" name="name" id="name" placeholder="Enter Contractor Name" value="{{ $contractor->company_name }}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="package_id">Package</label>
                        <select required class="form-control select2" name="package_id" id="package_id" placeholder="Select Package">
                            <option value="">Select Package</option>
                            @foreach ($packages as $package)
                            <option value="{{ $package->id }}" @if ($package->id == old('package_id', $contractor->packages->first()->id ?? null)) selected @endif>{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>