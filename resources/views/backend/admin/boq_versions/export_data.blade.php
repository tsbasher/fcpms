<html>

<head>
    <title>BOQ Details</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <table class="table table-bordered table-hover" id="boq-version-table">
        <tbody>
            <tr class="text-bold">
                <td colspan="10" class="text-center no-border">Local Government Engineering Department (LGED)</td>
            </tr>
            <tr class="text-bold">
                <td colspan="10" class="text-center no-border">{{ $project->name }}({{ $project->short_name }})</td>
            </tr>
            <tr class="text-bold">
                <td colspan="10" class="text-center no-border">Bill Of Quantity (BOQ)</td>
            </tr>
            <tr class="text-bold">
                <td colspan="6">Package: {{ $package->code }} - {{ $package->name }} </td>
                <td colspan="2">BOQ Version Name: {{ $boq_version->name }}</td>
                <td colspan="2">BOQ Version Date: {{ date('d F, Y', strtotime($boq_version->version_date)) }}</td>
            </tr>
            <tr class="text-bold">
                <td colspan="10" class="text-center no-border">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    @php
        $part_total = 0;
        $total = 0;
    @endphp
    @foreach ($details as $part)
        {{-- @dd($info) --}}
        @if ($part->boq_part)
            @php
                $item_total_quantity = 0;
                $item_total_amount = 0;
                $part_total = 0;

            @endphp
            <table class="table table-bordered table-hover" id="boq-version-table">


                <tbody>
                    <tr class="text-center">
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">SL</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Spec. No</th>
                        <th rowspan="2" style="vertical-align: middle;">Description of Work item</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Nos</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Quantity per Nos</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Total Quantity</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Unit</th>
                        <th colspan="2" style="width: 5%; vertical-align: middle;">Unit Rate</th>
                        <th rowspan="2" style="width: 5%; vertical-align: middle;">Total Amount (BDT)</th>
                    </tr>
                    <tr class="text-center">
                        <th style="width: 5%; vertical-align: middle;">In figure</th>
                        <th style="width: 10%; vertical-align: middle; word-wrap: break-word;">In words</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-bold">Part - {{ $part->boq_part->code }}) {{ $part->boq_part->name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    @foreach ($part->items as $item)
                        @if ($item->boq_item->has_sub_items)
                            <tr>
                                <td class="text-center">{{ $item->boq_item->code }} </td>
                                <td></td>
                                <td class="text-bold">{{ $item->boq_item->name }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($item->boq_sub_items as $sub_item)
                                @php
                                    $option_total_quantity = 0;
                                    $rate = 0;
                                @endphp
                                @if ($part->boq_part->has_option_variation)
                                    <tr>
                                        <td class="text-center">{{ $sub_item->code }}</td>
                                        <td>{{ $sub_item->specification_no }}</td>
                                        <td>{{ $sub_item->name }} <br> {!! $sub_item->description !!}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($options as $op)
                                        @php
                                            $d = $boq_version_details
                                                ->where('boq_part_id', $part->boq_part->id)
                                                ->where('boq_item_id', $item->boq_item->id)
                                                ->where('boq_sub_item_id', $sub_item->id)
                                                ->where('scheme_option_id', $op->id)
                                                ->first();
                                        @endphp
                                        @if ($d)
                                            @php
                                                $option_total_quantity += $d->total_quantity;
                                                $rate = $d->rate;
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-bold text-right">{{ $op->name }}</td>
                                                <td class="text-center">{{ $d ? $d->nos : 0 }}</td>
                                                <td class="text-center">{{ $d ? $d->quantity : 0 }}</td>
                                                <td class="text-center">{{ $d ? $d->total_quantity : 0 }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @php
                                        $part_total += $option_total_quantity * $rate;
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-bold text-right">Total =</td>
                                        <td></td>
                                        <td class="text-center">{{ round($option_total_quantity, 3) }}</td>
                                        <td class="text-center">{{ $sub_item->unit->code }}</td>
                                        <td class="text-right">{{ number_format($rate, 2) }}</td>
                                        <td class="text-center">{{ \App\Helper\InWordConvertion::convert($rate) }}</td>
                                        <td class="text-right">{{ number_format($option_total_quantity * $rate, 2) }}
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $d = $boq_version_details
                                            ->where('boq_part_id', $part->boq_part->id)
                                            ->where('boq_item_id', $item->boq_item->id)
                                            ->where('boq_sub_item_id', $sub_item->boq_sub_item->id)
                                            ->first();
                                    @endphp
                                    @if ($d)
                                        @php
                                            $part_total += $d->total_quantity * $d->rate;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $sub_item->code }}</td>
                                            <td>{{ $sub_item->specification_no }}</td>
                                            <td>{{ $sub_item->name }} <br> {!! $sub_item->description !!}</td>
                                            <td class="text-center">{{ $d ? $d->nos : 0 }}</td>
                                            <td class="text-center">{{ $d ? $d->quantity : 0 }}</td>
                                            <td class="text-center">{{ $d ? $d->total_quantity : 0 }}</td>
                                            <td class="text-center">{{ $sub_item->unit->code }}</td>
                                            <td class="text-right">{{ number_format($d->rate, 2) }}</td>
                                            <td class="text-center">
                                                {{ \App\Helper\InWordConvertion::convert($d->rate) }}</td>
                                            <td class="text-right">
                                                {{ number_format($d->total_quantity * $d->rate, 2) }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            @php
                                $option_total_quantity = 0;
                                $rate = 0;
                            @endphp
                            @if ($part->boq_part->has_option_variation)
                                <tr>
                                    <td class="text-center">{{ $item->boq_item->code }}</td>
                                    <td>{{ $item->boq_item->specification_no }}</td>
                                    <td>{{ $item->boq_item->name }} <br> {!! $item->boq_item->description !!}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($options as $op)
                                    @php
                                        $d = $boq_version_details
                                            ->where('boq_part_id', $part->boq_part->id)
                                            ->where('boq_item_id', $item->boq_item->id)
                                            ->where('scheme_option_id', $op->id)
                                            ->first();
                                    @endphp
                                    @if ($d)
                                        @php
                                            $option_total_quantity += $d->total_quantity;
                                            $rate = $d->rate;
                                        @endphp
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-bold text-right">{{ $op->name }}</td>
                                            <td class="text-center">{{ $d ? $d->nos : 0 }}</td>
                                            <td class="text-center">{{ $d ? $d->quantity : 0 }}</td>
                                            <td class="text-center">{{ $d ? $d->total_quantity : 0 }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    @endif
                                @endforeach

                                @php
                                    $part_total += $option_total_quantity * $rate;
                                @endphp
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-bold text-right">Total =</td>
                                    <td></td>
                                    <td class="text-center">{{ round($option_total_quantity, 3) }}</td>
                                    <td class="text-center">{{ $item->boq_item->unit->code }}</td>
                                    <td class="text-right">{{ number_format($rate, 2) }}</td>
                                    <td class="text-center">{{ \App\Helper\InWordConvertion::convert($rate) }}</td>
                                    <td class="text-right">{{ number_format($option_total_quantity * $rate, 2) }}
                                    </td>
                                </tr>
                            @else
                                @php
                                    $d = $boq_version_details
                                        ->where('boq_part_id', $part->boq_part->id)
                                        ->where('boq_item_id', $item->boq_item->id)
                                        ->first();
                                @endphp
                                @if ($d)
                                    @php
                                        $part_total += $d->total_quantity * $d->rate;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $item->boq_item->code }}</td>
                                        <td>{{ $item->boq_item->specification_no }}</td>
                                        <td>{{ $item->boq_item->name }} <br> {!! $item->boq_item->description !!}</td>
                                        <td class="text-center">{{ $d ? round($d->nos,2) : 0 }}</td>
                                        <td class="text-center">{{ $d ? $d->quantity : 0 }}</td>
                                        <td class="text-center">{{ $d ? $d->total_quantity : 0 }}</td>
                                        <td class="text-center">{{ $item->boq_item->unit->code }}</td>
                                        <td class="text-right">{{ number_format($d->rate, 2) }}</td>
                                        <td class="text-center">{{ \App\Helper\InWordConvertion::convert($d->rate) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($d->total_quantity * $d->rate, 2) }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endif
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right text-bold">Part - {{ $part->boq_part->code }})
                            {{ $part->boq_part->name }} Total =</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ number_format($part_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="no-border">&nbsp;</td>

                    </tr>
                </tbody>
            </table>
            @if ($loop->index < count($details) - 1)
                <div class="page-break"></div>
            @endif
        @endif

    @endforeach






</body>

</html>
