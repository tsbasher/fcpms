{{--
    @section('title', 'Contractor User')
@section('style')
<link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')

<section class="content">
    <div class="row">
        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Contractor User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.contractors.store_user', $contractor->id) }}" enctype="multipart/form-data">
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
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Full Name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="code">Email</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Email" value="{{ old('code') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" required class="form-control" name="password" id="password" placeholder="Enter Password" value="{{ old('password') }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="phone">Phone no</label>
                                <input type="text" required class="form-control" name="phone" id="phone" placeholder="Enter Phone no" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

        
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Users</h2>
                </div>
                <div class="card-body ">

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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-head-fixed" id="user-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>

                                        <td>
                                            @if ($user->is_active == 1)
                                                <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 100%">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

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
--}}


        <!-- general form elements -->
        <div class="card card-body bg-gray-light">
            <div class="card-header">
                <h3 class="card-title">Add New Contractor User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="{{ route('admin.contractors.store_user', $contractor->id) }}" enctype="multipart/form-data">
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
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Full Name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="code">Email</label>
                                <input type="text" required class="form-control" name="code" id="code" placeholder="Enter Email" value="{{ old('code') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" required class="form-control" name="password" id="password" placeholder="Enter Password" value="{{ old('password') }}">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="phone">Phone no</label>
                                <input type="text" required class="form-control" name="phone" id="phone" placeholder="Enter Phone no" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

        
            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Users</h2>
                </div>
                <div class="card-body ">

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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-head-fixed" id="user-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>

                                        <td>
                                            @if ($user->is_active == 1)
                                                <span class="badge bg-success" style="font-size: 100%">Yes</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 100%">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

            </div>
        <!-- /.card -->