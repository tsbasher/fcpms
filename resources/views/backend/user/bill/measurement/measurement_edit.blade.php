<div class="row">
    <h3 class="col-md-12 text-center">Edit Measurement</h3>
    <h5 class="col-md-12 text-center">Scheme: {{ $measurement->scheme->code }} - {{ $measurement->scheme->name }}</h5>
    <br>
    <h6 class="col-md-12"> BOQ Part :: Part {{ $measurement->boq_part->code }} - {{ $measurement->boq_part->name }}</h6>
    <h6 class="col-md-12"> BOQ Item ::
        {{ $measurement->boq_item->code }} - {{ $measurement->boq_item->name }}</h6>
    @if ($measurement->boq_subitem)
        <h6 class="col-md-12"> BOQ Subitem :: {{ $measurement->boq_subitem->code }} -
            {{ $measurement->boq_subitem->name }}</h6>
    @endif
</div>

<div class="row">
    <h5 class="col-md-12 text-center">&nbsp;</h5>
</div>
<form
    action="{{ $is_detail ? route('user.bills.edit_measurement_details', ['bill_id' => $bill->id, 'measurement_detail_id' => $measurement->measurement_details->first()->id]) : route('user.bills.edit_measurement', ['bill_id' => $bill->id, 'measurement_id' => $measurement->id]) }}"
    method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="measurement_item">Measurement @if (in_array('weight', $fields))
                        Group
                    @else
                        description
                    @endif
                </label>
                <input class="form-control" name="measurement_item" id="measurement_item" autocomplete="off"
                    value="{{ old('measurement_item', $measurement->description) }}"
                    placeholder="Enter Measurement description" required>
                {{-- <datalist id="suggestions"></datalist> --}}
                <div id="suggestions_dropdown" class="custom-dropdown-menu"></div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="form-group">
                <label for="nos">Nos</label>
                <input class="form-control m_calculate" name="nos" id="m_nos" placeholder="Enter Nos"
                    value="{{ old('nos', $measurement->nos) }}">

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="unit">Unit</label>
                <input class="form-control" disabled name="unit" id="unit" placeholder="Enter Unit"
                    value="{{ $measurement->unit->name }} ({{ $measurement->unit->code }})">
                <input type="hidden" name="unit_id" id="unit_id" value="{{ $measurement->unit->id }}">
                <input type="hidden" name="unit_fields" id="m_unit_fields" value="{{ $measurement->unit->fields }}">
            </div>
        </div>

    </div>


    <div class="row">
        @if (in_array('length', $fields))
            <div class="col-md-3">
                <div class="form-group">
                    <label for="length">Length</label>
                    <input class="form-control m_calculate" name="length" id="m_length" placeholder="Enter Length"
                        value="{{ old('length', $measurement->length) }}" required>
                </div>
            </div>
        @endif

        @if (in_array('width', $fields))
            <div class="col-md-3">

                <div class="form-group">
                    <label for="width">Width</label>
                    <input class="form-control m_calculate" name="width" id="m_width" placeholder="Enter Width"
                        value="{{ old('width', $measurement->width) }}" required>
                </div>
            </div>
        @endif

        @if (in_array('height', $fields))
            <div class="col-md-3">
                <div class="form-group">
                    <label for="height">Height</label>
                    <input class="form-control m_calculate" name="height" id="m_height" placeholder="Enter Height"
                        value="{{ old('height', $measurement->height) }}" required>
                </div>
            </div>
        @endif

        @if (in_array('weight', $fields))
            <div class="col-md-6">
                <div class="form-group">
                    <label for="measurement_item">Measurement description</label>
                    <input class="form-control" name="measurement_detail_item" id="measurement_detail_item"
                        autocomplete="off"
                        value="{{ old('measurement_detail_item', $measurement->measurement_details->first()->description) }}"
                        placeholder="Enter Measurement Details description" required>
                    {{-- <datalist id="suggestions"></datalist> --}}
                    <div id="suggestions_details_dropdown" class="custom-dropdown-menu"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Dia of Rebar (mm)</label>
                    <input class="form-control m_calculate" name="dia" id="m_dia" placeholder="Enter Dia"
                        value="{{ old('dia', $measurement->measurement_details->first()->dia) }}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Spaceing (mm)</label>
                    <input class="form-control m_calculate" name="spacing" id="m_spacing" placeholder="Enter Spacing"
                        value="{{ old('spacing', $measurement->measurement_details->first()->spacing) }}" required>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Rebar Nos (Per Pile)</label>
                    <input class="form-control m_calculate" name="rebar_nos" id="m_rebar_nos"
                        placeholder="Enter Rebar Nos"
                        value="{{ old('rebar_nos', $measurement->measurement_details->first()->rebar_nos) }}"
                        required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Rebar length (m)</label>
                    <input class="form-control m_calculate" name="rebar_length" id="m_rebar_length"
                        placeholder="Enter Rebar length"
                        value="{{ old('rebar_length', $measurement->measurement_details->first()->rebar_length) }}"
                        required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Unit Weight (kg/m)</label>
                    <input class="form-control m_calculate" name="unit_weight" id="m_unit_weight"
                        placeholder="Enter Unit Weight"
                        value="{{ old('unit_weight', $measurement->measurement_details->first()->unit_weight) }}"
                        required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="weight">Quantity per Pile</label>
                    <input class="form-control m_calculate" name="quantity_per_pile" id="m_quantity_per_pile"
                        placeholder="Enter Quantity per Pile"
                        value="{{ old('quantity_per_pile', $measurement->measurement_details->first()->quantity_per_pile) }}"
                        readonly>
                </div>
            </div>

        @endif
        <div class="col-md-3">
            <div class="form-group">
                <label for="quantity">Total Quantity</label>
                <input class="form-control" disabled name="quantity" id="m_quantity"
                    placeholder="Enter Total Quantity" value="{{ old('quantity', $measurement->quantity) }}">
            </div>
        </div>
    </div>
    @if (in_array('weight', $fields))
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="m_remarks" placeholder="Enter Remarks">{{ old('remarks', $measurement->measurement_details->first()->remarks) }}</textarea>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="m_remarks" placeholder="Enter Remarks">{{ old('remarks', $measurement->remarks) }}</textarea>
                </div>
            </div>
        </div>
        @endif
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary" id="update-measurement">Update Measurement</button>
        </div>
</form>


<script>
    $('#update-measurement').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                $('#modal-add').modal('hide');
                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while updating the measurement.');
            }
        });
    });
    $('.m_calculate').on('input', function() {
        debugger;
        var unitFields = JSON.parse($("#m_unit_fields").val());
        if ($.inArray("weight", unitFields) != -1) {
            var nos = parseFloat($('#m_nos').val()) || 1;
            var rebar_nos = parseFloat($('#m_rebar_nos').val()) || 0;
            var rebar_length = parseFloat($('#m_rebar_length').val()) || 0;
            var unit_weight = parseFloat($('#m_unit_weight').val()) || 0;
            var quantity_per_pile = rebar_nos * rebar_length * unit_weight;
            var quantity = nos * rebar_nos * rebar_length * unit_weight;
            $('#m_quantity_per_pile').val(quantity_per_pile.toFixed(4));
        } else {
            var nos = parseFloat($('#m_nos').val()) || 1;
            var length = parseFloat($('#m_length').val()) || 1;
            var width = parseFloat($('#m_width').val()) || 1;
            var height = parseFloat($('#m_height').val()) || 1;
            var weight = parseFloat($('#m_weight').val()) || 1;

            var quantity = nos * length * width * height * weight;
        }
        $('#m_quantity').val(quantity.toFixed(4));

    });
</script>
