@extends('backend.user.layouts.app')
@section('title', 'Bill')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/plugins/jquery-ui/jquery-ui.theme.min.css')}}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Edit Bill</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('user.bills.update', $bill->id) }}" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label for="name">Bill Name</label>
                        <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Bill Name" value="{{ old('name', $bill->name) }}">
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="boq_version_id">BOQ Version</label>
                                <select class="form-control select2" required name="boq_version_id" id="boq_version_id" placeholder="Enter BOQ Version">
                                    <option value="">Select BOQ Version</option>
                                    @foreach ($boq_versions as $version)
                                    <option value="{{ $version->id }}" @if (old('boq_version_id', $bill->boq_version_id) == $version->id) selected @endif>{{ $version->name }} ({{ date('d F, Y', strtotime($version->version_date)) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="bill_no">Bill No</label>
                                <input type="text"  class="form-control" name="bill_no" id="bill_no" placeholder="Enter Bill No" value="{{ old('bill_no', $bill->bill_no) }}">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="reference_code">Refference Code</label>
                                <input type="text"  class="form-control" name="reference_code" id="reference_code" placeholder="Enter Refference Code" value="{{ old('reference_code', $bill->reference_code) }}">
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bill_date">Bill Date</label>
                                    <input type="text" required class="form-control datepicker" name="bill_date"
                                        id="bill_date" placeholder="Enter Bill Date" value="{{ old('bill_date', $bill->bill_date) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="measurement_from_date">Measurement From Date</label>
                                    <input type="text" class="form-control datepicker" name="measurement_from_date"
                                        id="measurement_from_date" placeholder="Enter Measurement From Date"
                                        value="{{ old('measurement_from_date', $bill->measurement_from_date) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="measurement_to_date">Measurement To Date</label>
                                    <input type="text" class="form-control datepicker" name="measurement_to_date"
                                        id="measurement_to_date" placeholder="Enter Measurement To Date"
                                        value="{{ old('measurement_to_date', $bill->measurement_to_date) }}">
                                </div>
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="schemes">Schemes</label>
                        <select class="form-control select2" multiple name="schemes[]" id="schemes" placeholder="Select Schemes">
                            <option value="">Select Schemes</option>
                            @foreach ($schemes as $scheme)
                            <option value="{{ $scheme->id }}" @if (old('schemes', $bill->schemes->pluck('id')->toArray()) && in_array($scheme->id, old('schemes', $bill->schemes->pluck('id')->toArray()))) selected @endif>{{ $scheme->code }} - {{ $scheme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks">{{ old('remarks', $bill->remarks) }}</textarea>
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
<script src="{{asset('backend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    // let get_boq_versions_by_package_url = "{{ route('common.get_boq_versions_by_package', '*') }}";
    $(document).ready(function() {
        
        $('#description').summernote();
        $('.select2').select2();
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    });
</script>
{{--<script src="{{ asset('backend/dist/js/fcpms/boq_version.js') }}"></script>--}}
@endsection