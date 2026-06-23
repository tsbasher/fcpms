<html>

<head>
    <title>Shelter Wise Bill</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}"> --}}
    <!-- jQuery -->
    {{-- <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script> --}}
    <!-- Bootstrap 4 -->
    {{-- <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border {
            border: none !important;
            font-size: 14px !important;
            font-weight: bold !important;
        }

        th {
            font-weight: bold !important;
        }

        td,
        th {
            font-size: 14px !important;
            border: 1px solid #000 !important;
            padding: 5px !important;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }
        }
    </style>
</head>

<body>

    @foreach ($shelter_bill as $info)
    {{-- @dd($info) --}}
    @if($info->parts && count($info->parts) > 0 && $info->parts[0]->items && count($info->parts[0]->items) > 0 && $info->parts[0]->items[0]->bill_detail)
        <table class="table table-bordered table-hover" id="boq-version-table">


            <tbody>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Local Government Engineering Department (LGED)</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">{{ $project->name }}({{ $project->short_name }})</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Package: {{ $info->scheme->package->name }} {{ $info->scheme->package->code }}</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Summary of Bill</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="4">Name of Shelter:{{ $info->scheme->name }}</td>
                    <td colspan="3" class="text-center">Shelter ID: {{ $info->scheme->code }}</td>
                    <td colspan="2" class="text-center">{{ $info->scheme->scheme_option->name }}</td>
                    <td colspan="4" class="text-center">Measurement Date: {{ date('Y-m-d') }}</td>
                </tr>
                
                @php
                    $part_boq_total = 0;
                    $part_total = 0;
                    $part_this_bill_total = 0;
                    $part_previous_bill_total = 0;

                @endphp
                @foreach ($info->parts as $part)
                @if(!$loop->first)
                    <tr>
                        <td class="text-bold no-border">&nbsp;</td>
                    </tr>
                    @endif
                <tr class="text-center">
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">BOQ Item No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Description</th>
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">Unit</th>
                    <th colspan="3">As Per Original Contract</th>
                    <th colspan="2">Up to {{ $bill->name }}</th>
                    <th colspan="2">Up to Previous {{ $last_bill->name }}</th>
                    <th colspan="2">Net Amount of this {{ $bill->name }}</th>
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">Remarks</th>
                </tr>
                <tr class="text-center">
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
                    <tr>
                        <td colspan="13" class="text-bold">Bill - ({{ $part->part->code }}) {{ $part->part->name }}</td>
                    </tr>
                    @php
                        $boq_total = 0;
                        $total = 0;
                        $this_bill_total = 0;
                        $previous_bill_total = 0;

                    @endphp
                    @foreach ($part->items as $item)
                        {{-- @dd($item) --}}
                        @php
                            $boq_total += $item->boq_version_details ? $item->boq_version_details->quantity * $item->boq_version_details->rate : 0;
                            $total += $item->bill_detail ? ($item->bill_detail->quantity - $item->bill_detail->held_up_quantity) * $item->boq_version_details->rate : 0;
                            $this_bill_total += $item->bill_detail ? $item->bill_detail->this_bill_quantity * $item->boq_version_details->rate : 0;
                            $previous_bill_total += $item->bill_detail ? $item->bill_detail->previous_quantity * $item->boq_version_details->rate : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $item->item->code }}</td>
                            <td>{{ $item->item->name }}</td>
                            <td class="text-center">{{ $item->item->unit ? $item->item->unit->code : '' }}</td>
                            <td class="text-right">{{ $item->boq_version_details ? number_format($item->boq_version_details->quantity, 3) : '' }}</td>
                            <td class="text-right">{{ $item->boq_version_details ? number_format($item->boq_version_details->rate, 2) : '' }}</td>
                            <td class="text-right">{{ $item->boq_version_details ? number_format($item->boq_version_details->quantity * $item->boq_version_details->rate, 2) : '' }}</td>

                            <td class="text-right">{{ $item->bill_detail && $item->bill_detail->quantity-$item->bill_detail->held_up_quantity>0? number_format($item->bill_detail->quantity-$item->bill_detail->held_up_quantity, 3) : '' }}</td>
                            <td class="text-right">{{ $item->bill_detail && ($item->bill_detail->quantity - $item->bill_detail->held_up_quantity) * $item->boq_version_details->rate > 0 ? number_format(($item->bill_detail->quantity - $item->bill_detail->held_up_quantity) * $item->boq_version_details->rate, 2) : '' }}</td>
                            <td class="text-right">{{ $item->bill_detail && $item->bill_detail->previous_quantity>0 ? number_format($item->bill_detail->previous_quantity, 3) : '' }}</td>
                            <td class="text-right">{{ $item->bill_detail && $item->bill_detail->previous_quantity * $item->boq_version_details->rate>0 ? number_format($item->bill_detail->previous_quantity * $item->boq_version_details->rate, 2) : '' }}</td>
                            <td class="text-right">{{ $item->bill_detail && $item->bill_detail->this_bill_quantity>0 ? number_format($item->bill_detail->this_bill_quantity, 3) : '' }}</td>
                            <td class="text-right">{{ $item->bill_detail && $item->bill_detail->this_bill_quantity * $item->boq_version_details->rate>0 ? number_format($item->bill_detail->this_bill_quantity * $item->boq_version_details->rate, 2) : '' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    @php
                        $part_boq_total += $boq_total;
                        $part_total += $total;
                        $part_this_bill_total += $this_bill_total;
                        $part_previous_bill_total += $previous_bill_total;
                    @endphp
                    <tr>
                        <td colspan="5" class="text-right text-bold">Sub Total Bill - ({{ $part->part->code }}) {{ $part->part->name }}=</td>
                        <td class="text-right text-bold">{{ $boq_total > 0 ? number_format($boq_total, 2) : '' }}</td>
                        <td></td>
                        <td class="text-right text-bold">{{ $total > 0 ? number_format($total, 2) : '' }}</td>
                        <td></td>
                        <td class="text-right text-bold">{{ $previous_bill_total > 0 ? number_format($previous_bill_total, 2) : '' }}</td>
                        <td></td>
                        <td class="text-right text-bold">{{ $this_bill_total > 0 ? number_format($this_bill_total, 2) : '' }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right text-bold">Total Bill =</td>
                    <td class="text-right text-bold">{{ $part_boq_total > 0 ? number_format($part_boq_total, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right text-bold">{{ $part_total > 0 ? number_format($part_total, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right text-bold">{{ $part_previous_bill_total > 0 ? number_format($part_previous_bill_total, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right text-bold">{{ $part_this_bill_total > 0 ? number_format($part_this_bill_total, 2) : '' }}</td>
                    <td></td>
                </tr>

            </tbody>
        </table>

        <div class="page-break"></div>

        <table class="table table-bordered table-hover" id="boq-version-table">


            <tbody>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Local Government Engineering Department (LGED)</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">{{ $project->name }}({{ $project->short_name }})</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Package: {{ $info->scheme->package->name }} {{ $info->scheme->package->code }}</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="13" class="text-center no-border">Measurement Sheet</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="4">Name of Shelter:{{ $info->scheme->name }}</td>
                    <td colspan="3" class="text-center">Shelter ID: {{ $info->scheme->code }}</td>
                    <td colspan="2" class="text-center">{{ $info->scheme->scheme_option->name }}</td>
                    <td colspan="4" class="text-center">Measurement Date: {{ date('Y-m-d') }}</td>
                </tr>

                @foreach ($info->parts as $part)
                    <tr>
                        <td colspan="10" class="text-bold no-border">Bill - ({{ $part->part->code }}) {{ $part->part->name }}</td>
                    </tr>
                    @foreach ($part->items as $item)
                    @if($item->measurements && count($item->measurements) > 0)
                    @php
                        $colspan=count(json_decode($item->item->unit->fields)) + 7;
                    @endphp
                    @if(!$loop->first)
                    <tr>
                        <td class="text-bold no-border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-bold no-border">&nbsp;</td>
                    </tr>
                    @endif
                    
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold no-border">Name of Item: {{ $item->item->code }} : {{ $item->item->name }}</td>
                    </tr>
                        <tr>
                            <th style="width: 5px;">SL No.</th>
                            <th style="width: 5px;">Sspec. No</th>
                            <th>Description</th>
                            <th style="width: 5px;">Unit</th>
                            <th>Nos</th>
                            @if (in_array('length', json_decode($item->item->unit->fields)))
                                <th>Length</th>
                            @endif
                            @if (in_array('width', json_decode($item->item->unit->fields)))
                                <th>Width</th>
                            @endif
                            @if (in_array('height', json_decode($item->item->unit->fields)))
                                <th>Height</th>
                            @endif
                            @if (in_array('weight', json_decode($item->item->unit->fields)))
                                <th>Weight</th>
                            @endif
                            <th>Quantity</th>
                            <th style="width: 5px;">Remarks</th>
                        </tr>
                        @foreach ($item->measurements as $measurement)
                            <tr>
                                <td class="text-center">{{ $item->item->code }}</td>
                                <td class="text-center"></td>
                                <td>{{ $measurement->description }}</td>
                                <td class="text-center">{{ $item->item->unit->code }}</td>
                                <td class="text-right">{{ $measurement->nos }}</td>
                                @if (in_array('length', json_decode($item->item->unit->fields)))
                                    <td class="text-right">{{ $measurement->length }}</td>
                                @endif
                                @if (in_array('width', json_decode($item->item->unit->fields)))
                                    <td class="text-right">{{ $measurement->width }}</td>
                                @endif
                                @if (in_array('height', json_decode($item->item->unit->fields)))
                                    <td class="text-right">{{ $measurement->height }}</td>
                                @endif
                                @if (in_array('weight', json_decode($item->item->unit->fields)))
                                    <td class="text-right">{{ $measurement->weight }}</td>
                                @endif
                                <td class="text-right">{{ $measurement->quantity }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            @if(in_array('piece', json_decode($item->item->unit->fields)))
                            <td colspan="3" class="no-border"></td>
                            @else
                            
                            <td colspan="4" class="no-border"></td>
                            @endif
                            <td colspan="{{ count(json_decode($item->item->unit->fields))+1 }}" class="text-right text-bold">Work done Quantity</td>
                            <td class="text-right text-bold"> {{ number_format($item->measurements->sum('quantity'), 3) }}</td>
                            <td></td>
                        </tr>
                        
                        <tr>
                            @if(in_array('piece', json_decode($item->item->unit->fields)))
                            <td colspan="3" class="no-border"></td>
                            @else
                            
                            <td colspan="4" class="no-border"></td>
                            @endif
                            <td colspan="{{ count(json_decode($item->item->unit->fields))+1 }}" class="text-right text-bold">Previous Quantity</td>
                            <td class="text-right text-bold"> {{ number_format($item->bill_detail->previous_quantity, 3) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            @if(in_array('piece', json_decode($item->item->unit->fields)))
                            <td colspan="3" class="no-border"></td>
                            @else
                            
                            <td colspan="4" class="no-border"></td>
                            @endif
                            <td colspan="{{ count(json_decode($item->item->unit->fields))+1 }}" class="text-right text-bold">BOQ Quantity</td>
                            <td class="text-right text-bold"> {{ number_format($item->boq_version_details->quantity, 3) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            @if(in_array('piece', json_decode($item->item->unit->fields)))
                            <td colspan="3" class="no-border"></td>
                            @else
                            
                            <td colspan="4" class="no-border"></td>
                            @endif
                            <td colspan="{{ count(json_decode($item->item->unit->fields))+1 }}" class="text-right text-bold">Held Up Quantity</td>
                            <td class="text-right text-bold"> {{ number_format($item->bill_detail->held_up_quantity, 3) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            @if(in_array('piece', json_decode($item->item->unit->fields)))
                            <td colspan="3" class="no-border"></td>
                            @else
                            
                            <td colspan="4" class="no-border"></td>
                            @endif
                            <td colspan="{{ count(json_decode($item->item->unit->fields))+1 }}" class="text-right text-bold">This Bill Quantity</td>
                            <td class="text-right text-bold"> {{ number_format($item->bill_detail->this_bill_quantity, 3) }}</td>
                            <td></td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>
        @endif
    @endforeach


</body>

</html>
