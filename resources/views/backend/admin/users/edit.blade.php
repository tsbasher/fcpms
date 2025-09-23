@extends('backend.admin.layouts.app')
@section('title', 'Contractor Users')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Edit Contractor User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.contractor_users.update', $user->id) }}" enctype="multipart/form-data">
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
                                <label for="name">User Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter User Name" value="{{ old('name', $user->name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="email">User Email</label>
                                <input type="email" required class="form-control" name="email" id="email" placeholder="Enter User Email" value="{{ old('email', $user->email) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">User Phone</label>
                                <input type="text"  class="form-control" name="phone" id="phone" placeholder="Enter User Phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contractor_id">Select Contractor</label>
                                <select class="form-control" name="contractor_id" id="contractor_id" required>
                                    <option value="">-- Select Contractor --</option>
                                    @foreach ($contractors as $contractor)
                                    <option value="{{ $contractor->id }}" {{ old('contractor_id', $user->contractor_id) == $contractor->id ? 'selected' : '' }}>{{ $contractor->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password"  class="form-control" name="password" id="password" placeholder="Enter Password" value="{{ old('password') }}">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter Confirm Password" value="{{ old('password_confirmation') }}">
                            </div>
                        </div>
                    </div>
                    
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', $user->is_active)==1) checked @endif>
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
        $('#description').summernote();
    });
</script>
@endsection