<form role="form" method="POST" action="{{ route('user.bills.store_scheme', $bill->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="card-body">

       

        <div class="form-group">
            <label for="scheme_id">Scheme</label>
            <select class="form-control select2" multiple name="schemes[]" id="schemes" placeholder="Select Schemes">
                            <option value="">Select Schemes</option>
                            @foreach ($schemes as $scheme)
                            <option value="{{ $scheme->id }}" @if (old('schemes', $bill->schemes->pluck('id')->toArray()) && in_array($scheme->id, old('schemes', $bill->schemes->pluck('id')->toArray()))) selected @endif>{{ $scheme->name }}</option>
                            @endforeach
                        </select>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>