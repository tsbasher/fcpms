

            <div class="card card-body bg-gray-light">
                <div class="card-header">
                    <h2 class="card-title ">Contractors User</h2>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body ">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-head-fixed" id="contractor-user-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                                        <td>
                                            <a href="{{ route('admin.contractors.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-sm btn-danger delete_record" data-url="{{ route('admin.contractors.destroy', $user->id) }}"><i class="fas fa-trash"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" style="background: #00000000">
                    {{ $contractors->links() }}
                </div>
            </div>

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