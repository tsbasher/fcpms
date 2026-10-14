@extends('backend.admin.layouts.app')
@section('title', 'Admin Users')
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Admin User</h3>
            </div>
            <form role="form" method="POST" action="{{ route('admin.admin_users.store') }}">
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
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" required class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label for="role_id">Role <span class="text-danger">*</span></label>
                        <select required class="form-control select2" name="role_id" id="role_id">
                            <option value="">-- Select Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @if (old('role_id') == $role->id) selected @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info py-2">
                        <i class="fas fa-info-circle mr-1"></i> Projects are assigned by a Super Admin from the Admin Users list after the user is created.
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" required class="form-control" name="password" id="password" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" required class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if (old('is_active', 1) == 1) checked @endif>
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="must_change_password" name="must_change_password" value="1" @if (old('must_change_password', 1) == 1) checked @endif>
                        <label class="form-check-label" for="must_change_password">Force password reset on first login</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('admin.admin_users.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script src="{{ asset('backend/plugins/select2/js/select2.full.js') }}"></script>
    <script type="text/javascript">
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    </script>
@endsection