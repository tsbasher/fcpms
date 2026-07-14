<html>

<head>
    <title>Package Summary Bill</title>
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
            break-inside: avoid;
        }

        .no-border {
            border: none !important;
            font-size: 10px !important;
            font-weight: bold !important;
        }

        .no-border-summary {
            border: none !important;
            font-size: 30px !important;
            padding-top: 40px !important;
            font-weight: normal !important;
        }

        th {
            font-weight: bold !important;
        }

        td,
        th {
            font-size: 10px !important;
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

        /* .page-break {
            page-break-after: always;
        } */

        /* @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }
        } */

        p {
            margin: 0px !important;
        }

        /* Base print setups */
        @media print {

            /* Define a standard portrait layout */
            @page portrait-layout {
                size: portrait;
                /* margin: 20mm; */
            }

            /* Define a custom landscape layout */
            @page landscape-layout {
                size: landscape;
                /* margin: 20mm; */
            }

            /* Force standard page breaks */
            /* .page {
                break-after: page;
            } */

            /* Assign layouts to specific classes */
            .portrait-page {
                page: portrait-layout;
                break-before: page;
                /* Forces a clean start */
                break-after: avoid;
                /* Prevents trailing empty pages */
            }

            .landscape-page {
                page: landscape-layout;
                break-before: page;
                /* Forces a clean start */
                break-after: avoid;
                /* Prevents trailing empty pages */
            }

            .portrait-page:first-of-type,
            .landscape-page:first-of-type {
                break-before: auto;
            }
        }
    </style>
</head>

<body>
    @php
        if ($last_bill) {
            $colspan = 13;
        } else {
            $colspan = 11;
        }
    @endphp
    <div class="page portrait-page">
        <table class="table table-bordered table-hover" id="boq-version-table">
            <tbody>

                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr class="">
                    <td class="text-center no-border-summary">Local Government Engineering
                        Department (LGED)
                    </td>
                </tr>

                <tr class="text-bold">
                    <td class="text-center no-border-summary">
                        {{ $project->name }}({{ $project->short_name }})</td>
                </tr>
                <tr class="">
                    <td class="text-center no-border-summary">
                        Package: {{ $package->name }} {{ $package->code }}</br>
                        
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr class="text-bold">
                    <td class="text-center no-border-summary" style="font-size: 80px !important;">
                        {{ strtoupper( $this_bill->name) }}
                    </td>
                </tr>
                <tr class="">
                    <td class="text-center no-border-summary" style="padding-top:0px">
                        Summary of Bill</br>
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr>

                    <td class="text-center no-border-summary">Measurement Date:<span style="font-weight: bold">
                            @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                                {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }} to
                                {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="text-bold no-border-summary">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="text-center no-border-summary">
                        SUBMITTED BY:</br>
                        {{ $contractor->company_name }}</br>
                        <span style="font-size: 20px !important;">{!! $contractor->company_address !!}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="page landscape-page">
        <table class="table table-bordered table-hover" id="boq-version-table">


            <tbody>
                <tr class="text-bold">
                    <td colspan="{{ $colspan }}" class="text-center no-border">Local Government Engineering
                        Department (LGED)
                    </td>
                </tr>
                <tr class="text-bold">
                    <td colspan="{{ $colspan }}" class="text-center no-border">
                        {{ $project->name }}({{ $project->short_name }})</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="{{ $colspan }}" class="text-center no-border">Package:
                        {{ $package->name }}
                        {{ $package->code }}</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="{{ $colspan }}" class="text-center no-border">Summary of Bill</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="{{ $colspan }}" class="text-center no-border">{{ $this_bill->name }}</td>
                </tr>
                <tr class="text-bold">
                    <td colspan="7">
                        @if (Request::get('report_type') == 'UPZ_DTL')
                            Upazila: {{ $upazila->name }}
                        @elseif(Request::get('report_type') == 'PKG_SUM')
                            Package: {{ $package->name }}
                        @endif
                    </td>

                    <td colspan="4" class="text-center">Measurement Date: @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                            {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }} to
                            {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="text-bold no-border" colspan="{{ $colspan }}">&nbsp;</td>
                </tr>

                @php
                    $part_boq_total = 0;
                    $part_total = 0;
                    $part_this_bill_total = 0;
                    $part_previous_bill_total = 0;
                @endphp
                <tr class="text-center">
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">BOQ Item No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Description</th>
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">Unit</th>
                    <th colspan="3">As Per Original Contract</th>
                    <th colspan="2">Up to {{ $this_bill->name }}</th>
                    @if ($last_bill)
                        <th colspan="2">Up to Previous {{ $last_bill->name }}</th>
                    @endif
                    <th colspan="2">Net Amount of this {{ $this_bill->name }}</th>
                    <th rowspan="2" style="width: 5px; vertical-align: middle;">Remarks</th>
                </tr>
                <tr class="text-center">
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    @if ($last_bill)
                        <th>Quantity</th>
                        <th>Amount</th>
                    @endif
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
                @foreach ($summary_bill as $sb)
                    @if (!$loop->first)
                        <tr>
                            <td class="text-bold no-border" colspan="{{ $colspan }}">&nbsp;</td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold">Bill - ({{ $sb['part']->code }})
                            {{ $sb['part']->name }}</td>
                    </tr>
                    @php
                        $boq_total = 0;
                        $total = 0;
                        $this_bill_total = 0;
                        $previous_bill_total = 0;

                    @endphp
                    @foreach ($sb['items'] as $item)
                        @if (!$item['item']->has_sub_items)
                            @php
                                $boq_total += $item['boq_quantity'] * $item['rate'];

                                $total += $item['total_quantity'] * $item['rate'];

                                $this_bill_total += $item['this_quantity'] * $item['rate'];
                                $previous_bill_total += $item['previous_quantity'] * $item['rate'];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $item['item']->code }}</td>
                                <td>{{ $item['item']->name }}</td>
                                <td class="text-center">
                                    {{ $item['item']->unit ? $item['item']->unit->code : '' }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($item['boq_quantity'], 3) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($item['rate'], 3) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($item['boq_quantity'] * $item['rate'], 2) }}
                                </td>
                                <td class="text-right">
                                    {{ $item['total_quantity'] > 0 ? number_format($item['total_quantity'], 3) : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $item['total_quantity'] > 0 ? number_format($item['total_quantity'] * $item['rate'], 2) : '-' }}
                                </td>
                                @if ($last_bill)
                                    <td class="text-right">
                                        {{ $item['previous_quantity'] > 0 ? number_format($item['previous_quantity'], 3) : '-' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $item['previous_quantity'] > 0 ? number_format($item['previous_quantity'] * $item['rate'], 2) : '-' }}
                                    </td>
                                @endif
                                <td class="text-right">
                                    {{ $item['this_quantity'] > 0 ? number_format($item['this_quantity'], 3) : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $item['this_quantity'] > 0 ? number_format($item['this_quantity'] * $item['rate'], 2) : '-' }}
                                </td>
                                <td></td>

                            </tr>
                        @else
                            <tr>
                                <td colspan="{{ $colspan }}" class="text-bold">
                                    ({{ $item['item']->code }})
                                    {{ $item['item']->name }}</td>
                            </tr>
                            @foreach ($item['sub_items'] as $sub_item)
                                @php
                                    $boq_total += $sub_item['boq_quantity'] * $sub_item['rate'];

                                    $total += $sub_item['total_quantity'] * $sub_item['rate'];

                                    $this_bill_total += $sub_item['this_quantity'] * $sub_item['rate'];
                                    $previous_bill_total += $sub_item['previous_quantity'] * $sub_item['rate'];
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $sub_item['sub_item']->code }}</td>
                                    <td>{{ $sub_item['sub_item']->name }}</td>
                                    <td class="text-center">
                                        {{ $sub_item['sub_item']->unit ? $sub_item['sub_item']->unit->code : '' }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($sub_item['boq_quantity'], 3) }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($sub_item['rate'], 3) }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($sub_item['boq_quantity'] * $sub_item['rate'], 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ $sub_item['total_quantity'] > 0 ? number_format($sub_item['total_quantity'], 3) : '-' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $sub_item['total_quantity'] > 0 ? number_format($sub_item['total_quantity'] * $sub_item['rate'], 2) : '-' }}
                                    </td>
                                    @if ($last_bill)
                                        <td class="text-right">
                                            {{ $sub_item['previous_quantity'] > 0 ? number_format($sub_item['previous_quantity'], 3) : '-' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $sub_item['previous_quantity'] > 0 ? number_format($sub_item['previous_quantity'] * $sub_item['rate'], 2) : '-' }}
                                        </td>
                                    @endif
                                    <td class="text-right">
                                        {{ $sub_item['this_quantity'] > 0 ? number_format($sub_item['this_quantity'], 3) : '-' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $sub_item['this_quantity'] > 0 ? number_format($sub_item['this_quantity'] * $sub_item['rate'], 2) : '-' }}
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    @php
                        $part_boq_total += $boq_total;
                        $part_total += $total;
                        $part_this_bill_total += $this_bill_total;
                        $part_previous_bill_total += $previous_bill_total;
                    @endphp
                    <tr>
                        <td colspan="5" class="text-right text-bold">Sub Total Bill -
                            ({{ $sb['part']->code }})
                            {{ $sb['part']->name }}=</td>
                        <td class="text-right text-bold">
                            {{ $boq_total > 0 ? number_format($boq_total, 2) : '' }}
                        </td>
                        <td></td>
                        <td class="text-right text-bold">{{ $total > 0 ? number_format($total, 2) : '' }}
                        </td>
                        @if ($last_bill)
                            <td></td>
                            <td class="text-right text-bold">
                                {{ $previous_bill_total > 0 ? number_format($previous_bill_total, 2) : '' }}
                            </td>
                        @endif
                        <td></td>
                        <td class="text-right text-bold">
                            {{ $this_bill_total > 0 ? number_format($this_bill_total, 2) : '' }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right text-bold">Total Bill =</td>
                    <td class="text-right text-bold">
                        {{ $part_boq_total > 0 ? number_format($part_boq_total, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right text-bold">
                        {{ $part_total > 0 ? number_format($part_total, 2) : '' }}
                    </td>
                    @if ($last_bill)
                        <td></td>
                        <td class="text-right text-bold">
                            {{ $part_previous_bill_total > 0 ? number_format($part_previous_bill_total, 2) : '' }}
                        </td>
                    @endif
                    <td></td>
                    <td class="text-right text-bold">
                        {{ $part_this_bill_total > 0 ? number_format($part_this_bill_total, 2) : '' }}</td>
                    <td></td>
                </tr>


            </tbody>
        </table>
    </div>


</body>

</html>
