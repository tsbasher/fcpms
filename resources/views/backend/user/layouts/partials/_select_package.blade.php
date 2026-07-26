<form role="form" method="POST" action="{{ route('user.package.select') }}" enctype="multipart/form-data">
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
            <label for="package_id">Package</label>
            <select required class="form-control select2" name="package_id" id="package_id" placeholder="Select Package">
                @foreach ($packages as $package)
                <option value="{{ $package->id }}" @if (old('package_id', Auth::guard('web')->user()->package_id) == $package->id) selected @endif>{{ $package->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        
        $('.select2').select2();
    });
</script>