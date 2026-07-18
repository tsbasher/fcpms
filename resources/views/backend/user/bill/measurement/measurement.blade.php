<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="measurement_item">Measurement description</label>
            <input class="form-control" name="measurement_item" id="measurement_item" autocomplete="off" value="{{ old('measurement_item') }}" placeholder="Enter Measurement description" required>
            {{-- <datalist id="suggestions"></datalist> --}}
            <div id="suggestions_dropdown" class="custom-dropdown-menu"></div>
        </div>
    </div>

    <div class="col-md-3">

        <div class="form-group">
            <label for="nos">Nos</label>
            <input class="form-control calculate" name="nos" id="nos" placeholder="Enter Nos" value="{{ old('nos') }}" required>

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="unit">Unit</label>
            <input class="form-control" disabled name="unit" id="unit" placeholder="Enter Unit" value="{{ $unit->name }} ({{ $unit->code }})">
            <input type="hidden" name="unit_id" id="unit_id" value="{{ $unit->id }}">
        </div>
    </div>

</div>


<div class="row">
    @if (in_array('length', $fields))
        <div class="col-md-3">
            <div class="form-group">
                <label for="length">Length</label>
                <input class="form-control calculate" name="length" id="length" placeholder="Enter Length" value="{{ old('length') }}" required>
            </div>
        </div>
    @endif

    @if (in_array('width', $fields))
        <div class="col-md-3">

            <div class="form-group">
                <label for="width">Width</label>
                <input class="form-control calculate" name="width" id="width" placeholder="Enter Width" value="{{ old('width') }}" required>
            </div>
        </div>
    @endif

    @if (in_array('height', $fields))
        <div class="col-md-3">
            <div class="form-group">
                <label for="height">Height</label>
                <input class="form-control calculate" name="height" id="height" placeholder="Enter Height" value="{{ old('height') }}" required>
            </div>
        </div>
    @endif
    
    @if (in_array('weight', $fields))
        <div class="col-md-3">
            <div class="form-group">
                <label for="weight">Weight</label>
                <input class="form-control calculate" name="weight" id="weight" placeholder="Enter Weight" value="{{ old('weight') }}" required>
            </div>
        </div>
    @endif
    <div class="col-md-3">
            <div class="form-group">
                <label for="quantity">Total Quantity</label>
                <input class="form-control" disabled name="quantity" id="quantity" placeholder="Enter Total Quantity">
            </div>
        </div>
</div>


<script>
        

</script>

