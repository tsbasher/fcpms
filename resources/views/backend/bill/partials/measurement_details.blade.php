<div class="page portrait-page">


    <table class="table table-bordered table-hover pb-5" id="boq-version-table76">


        <tbody>
            <tr class="text-bold">
                <td colspan="{{ $colspan }}" class="text-center no-border heading">
                    Local Government
                    Engineering
                    Department (LGED)
                </td>
            </tr>
            <tr class="text-bold">
                <td colspan="{{ $colspan }}" class="text-center no-border sub-heading">
                    {{ $project->name }}({{ $project->short_name }})</td>
            </tr>
            <tr class="text-bold">
                <td colspan="{{ $colspan }}" class="text-center no-border sub-heading">Package:
                    {{ $scheme->package->name }}
                    {{ $scheme->package->code }}</td>
            </tr>
            <tr class="text-bold">
                <td colspan="{{ $colspan }}" class="text-center no-border sub-heading">Measurement
                    of Rebar</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover no break" id="boq-version-table1212">

        <tbody>
            <tr class="text-bold">
                <td colspan="3">Name of Shelter:{{ $scheme->name }}</td>
                <td colspan="3" class="text-center">Shelter ID:
                    {{ $scheme->code }}</td>
                <td colspan="3">Upazila: {{ $scheme->upazila->name }}</td>
            </tr>
            <tr class="text-bold">
                <td colspan="2" class="text-center">
                    {{ $scheme->scheme_option->name }}</td>
                <td colspan="7" class="text-center">Measurement Date: @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                        {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }}
                        to
                        {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="9" class="no-border">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="text-bold"> Item Code</td>
                <td colspan="2" class="text-bold"> Spec. No</td>
                <td colspan="5" class="text-bold"> Description</td>
            </tr>
            <tr>
                <td colspan="2" class="text-bold">{{ $boq_item->item->code }} </td>
                <td colspan="2" class="text-bold">{{ $boq_item->item->specification_no }}
                </td>
                <td colspan="5" class="text-bold">{{ $boq_item->item->name }}
            </tr>

            <tr>
                <td colspan="9" class="no-border">&nbsp;</td>
            </tr>
            <tr>
                <th class="text-bold">SL No.</th>
                <th class="text-bold">Description</th>
                <th class="text-bold">Dia of Bar (mm)</th>
                <th class="text-bold">Spacing (mm)</th>
                <th class="text-bold">Rebar Nos</th>
                <th class="text-bold">Length (m)</th>
                <th class="text-bold">Unit Weight (kg/m)</th>
                <th class="text-bold">Quantity</th>
                <th class="text-bold">Remarks</th>
            </tr>
            @foreach ($measurement_details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->index + 1 }}</td>
                    <td>{{ $detail->description }}</td>
                    <td class="text-right">{{ $detail->dia }}</td>
                    <td class="text-right">{{ $detail->spacing }}</td>
                    <td class="text-right">{{ $detail->rebar_nos }}</td>
                    <td class="text-right">{{ $detail->rebar_length }}</td>
                    <td class="text-right">{{ $detail->unit_weight }}</td>
                    <td class="text-right">{{ $detail->quantity_per_pile }}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-right text-bold">Total Quantity (Per Pile)</td>
                <td class="text-right text-bold">
                    {{ number_format($measurement_details->sum('quantity_per_pile'), 4) }}
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
