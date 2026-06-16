<form role="form" method="POST" action="{{ route('user.bills.store_boq_parts', $bill->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="card-body">

       
        <div class="form-group">
            <label for="scheme_id">Scheme</label>
            <select class="form-control select2" name="schemes" id="schemes" placeholder="Select Schemes" required>
                            <option value="">Select Schemes</option>
                            @foreach ($schemes as $scheme)
                            <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                            @endforeach
                        </select>
        </div>

        <div class="form-group">
            <label for="boq_part_id">BOQ Part</label>
            <select class="form-control select2" multiple name="boq_parts[]" id="boq_parts" placeholder="Select BOQ Parts" required>
                            <option value="">Select BOQ Parts</option>
                            @foreach ($boq_parts as $boq_part)
                            <option value="{{ $boq_part->id }}">{{ $boq_part->code}} - {{ $boq_part->name }}</option>
                            @endforeach
                        </select>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>