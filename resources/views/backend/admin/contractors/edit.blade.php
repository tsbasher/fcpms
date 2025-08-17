@extends('backend.admin.layouts.app')
@section('title', 'Contractors')
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')

    <section class="content">
            <div class="row">
                    <!-- general form elements -->
                    <div class="card card-body bg-gray-light">
                        <div class="card-header">
                            <h3 class="card-title">Edit Contractor</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" method="POST" action="{{ route('admin.contractors.update', $contractor->id) }}" enctype="multipart/form-data">
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
                                            <label for="company_name">Company Name</label>
                                            <input type="text" required class="form-control" name="company_name" id="company_name" placeholder="Enter Company Name" value="{{ old('company_name', $contractor->company_name) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="company_email">Company Email</label>
                                            <input type="text" class="form-control" name="company_email" id="company_email" placeholder="Enter Company Email" value="{{ old('company_email', $contractor->company_email) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="company_phone">Company Phone</label>
                                            <input type="text" class="form-control" name="company_phone" id="company_phone" placeholder="Enter Company Phone" value="{{ old('company_phone', $contractor->company_phone) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="company_reg_code">Company Reg. Code</label>
                                            <input type="text" class="form-control" name="company_reg_code" id="company_reg_code" placeholder="Enter Company Reg. Code" value="{{ old('company_reg_code', $contractor->company_reg_code) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="company_website">Company Website</label>
                                            <input type="text" class="form-control" name="company_website" id="company_website" placeholder="Enter Company Website" value="{{ old('company_website', $contractor->company_website) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="contact_person_name">Contact Person Name</label>
                                            <input type="text" class="form-control" name="contact_person_name" id="contact_person_name" placeholder="Enter Contact Person Name" value="{{ old('contact_person_name', $contractor->contact_person_name) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="contact_person_email">Contact Person Email</label>
                                            <input type="text" class="form-control" name="contact_person_email" id="contact_person_email" placeholder="Enter Contact Person Email" value="{{ old('contact_person_email', $contractor->contact_person_email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="contact_person_phone">Contact Person Phone</label>
                                            <input type="text" class="form-control" name="contact_person_phone" id="contact_person_phone" placeholder="Enter Contact Person Phone" value="{{ old('contact_person_phone', $contractor->contact_person_phone) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="company_address">Company Address</label>
                                    <textarea class="form-control" name="company_address" id="company_address" placeholder="Enter Company Address">{{ old('company_address', $contractor->company_address) }}</textarea>
                                </div>

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $contractor->is_active) == 1) checked @endif>
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
    <script type="text/javascript">
        $(document).ready(function() {
            debugger;
            $('#company_address').summernote();
        });
    </script>
@endsection
