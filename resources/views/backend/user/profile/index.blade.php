@extends('backend.user.layouts.app')
@section('title', 'Profile')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h3 class="card-title">My Profile</h3>
                </div>
                <form role="form" method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone No</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone No" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter Current Password">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm New Password">
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection