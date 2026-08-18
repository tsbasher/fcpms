<html>

<head>
    <title>
        @if (Request::get('report_type') == 'UPZ_DTL')
            ({{ $this_bill->calculate_with_heldup == 1 ? 'NVB' : 'VB' }}) - {{ $upazila->name }} Upazila Wise Details
            Bill
        @elseif(Request::get('report_type') == 'SCH_DTL')
            ({{ $this_bill->calculate_with_heldup == 1 ? 'NVB' : 'VB' }}) -  Scheme Details Bill
        @endif
    </title>
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
            break-inside: auto !important;
        }

        .no-border {
            border: none !important;
            font-size: 10px !important;
            font-weight: bold !important;
        }

        .no-border-summary {
            border: none !important;
            font-size: 20px !important;
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

        .heading {
            font-size: 14px !important;
            font-weight: bold !important;
        }

        .sub-heading {
            font-size: 12px !important;
            /* font-weight: bold !important; */
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

            @page portrait-layout {
                size: portrait;

                @bottom-center {
                    content: "Page " counter(page) " of " counter(pages);
                    font-family: sans-serif;
                    font-size: 10pt;
                }
            }

            @page landscape-layout {
                size: landscape;

                @bottom-center {
                    content: "Page " counter(page) " of " counter(pages);
                    font-family: sans-serif;
                    font-size: 10pt;
                }
            }

            @page portrait-layout:first {
        @bottom-center {
            content: normal; /* Hides footer if first page is portrait */
        }
    }

    @page landscape-layout:first {
        @bottom-center {
            content: normal; /* Hides footer if first page is landscape */
        }
    }

            .portrait-page {
                page: portrait-layout;
                break-before: page;
            }

            .landscape-page {
                page: landscape-layout;
                break-before: page;
            }

            .portrait-page:first-of-type,
            .landscape-page:first-of-type {
                break-before: auto;
            }

            /* IMPORTANT */
            table {
                width: 100%;
                border-collapse: collapse;
                break-inside: auto !important;
                page-break-inside: auto !important;
            }

            thead {
                display: table-header-group !important;
            }

            tfoot {
                display: table-footer-group !important;
            }

            tbody {
                display: table-row-group !important;
            }

            tr {
                break-inside: avoid;
                page-break-inside: avoid;
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
    @if (Request::get('report_type') != 'SCH_DTL')
        <div class="page portrait-page">
            <table class="table table-bordered table-hover" id="boq-version-table">
                <tbody>

                    <tr>
                        <td class="text-bold no-border-summary">
                            &nbsp;
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-center no-border-summary heading" style="font-size: 30px !important;">
                            Local Government Engineering
                            Department (LGED)
                        </td>
                    </tr>

                    <tr class="text-bold">
                        <td class="text-center no-border-summary heading">
                            {{ $project->name }}({{ $project->short_name }})</td>
                    </tr>
                    <tr class="">
                        <td class="text-center no-border-summary heading">
                            Package: {{ $package->name }} {{ $package->code }}</br>
                            {{ $this_bill->name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold no-border-summary">
                            &nbsp;
                        </td>
                    </tr>
                    <tr class="text-bold">
                        <td class="text-center no-border-summary" style="font-size: 40px !important;">
                            {{ strtoupper($upazila->name) }}
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-center no-border-summary" style="padding-top:0px">
                            Detailed Measurement
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
            <table class="table table-bordered table-hover" id="boq-version-table123">


                <thead>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border heading">Local Government
                            Engineering
                            Department (LGED)
                        </th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">
                            {{ $project->name }}({{ $project->short_name }})</th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">Package:
                            {{ $package->name }}
                            {{ $package->code }}</th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">Summary of Bill
                        </th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">
                            {{ $this_bill->name }}</th>
                    </tr>
                </thead>
            </table>
            <table class="table table-bordered table-hover" id="boq-version-table">
                <thead>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan - 4 }}">
                            @if (Request::get('report_type') == 'UPZ_DTL')
                                Upazila: {{ $upazila->name }}
                            @elseif(Request::get('report_type') == 'PKG_SUM')
                                Package: {{ $package->name }}
                            @endif
                        </th>

                        <th colspan="4" class="text-center">Measurement Date: @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                                {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }} to
                                {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                            @endif
                        </th>
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
                </thead>

                <tbody>
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

                <tfoot>
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                    </tr>
                </tfoot>

            </table>
        </div>
    @endif
    @foreach ($shelter_bill as $info)
    @php
        
        $measurement_details_view=[];
    @endphp
        {{-- @dd($shelter_bill) --}}
        @if ($info->has_bill_details)
            <div class="page landscape-page">
                <table class="table table-bordered table-hover" id="boq-version-table1">


                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border heading">Local Government
                            Engineering
                            Department (LGED)
                        </th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">
                            {{ $project->name }}({{ $project->short_name }})</th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">Package:
                            {{ $info->scheme->package->name }}
                            {{ $info->scheme->package->code }}</th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">Summary of Bill
                        </th>
                    </tr>
                    <tr class="text-bold">
                        <th colspan="{{ $colspan }}" class="text-center no-border sub-heading">
                            {{ $this_bill->name }}
                        </th>
                    </tr>
                </table>

                <table class="table table-bordered table-hover" id="boq-version-table">
                    <thead>
                        <tr class="text-bold">
                            <th colspan="2">Name of Shelter:{{ $info->scheme->name }}</th>
                            <th colspan="2">Upazila: {{ $info->scheme->upazila->name }}</th>
                            <th colspan="@if ($last_bill) 3 @else 2 @endif" class="text-center">
                                Shelter ID: {{ $info->scheme->code }}</th>
                            <th class="text-center">{{ $info->scheme->scheme_option->name }}</th>
                            <th colspan="@if ($last_bill) 5 @else 4 @endif" class="text-center">
                                Measurement Date: @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                                    {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }} to
                                    {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                                @endif
                            </th>
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
                    </thead>
                    <tbody>
                        @foreach ($info->parts as $part)
                            @if (!$loop->first)
                                <tr>
                                    <td class="text-bold no-border" colspan="{{ $colspan }}">&nbsp;</td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="{{ $colspan }}" class="text-bold">Bill - ({{ $part->part->code }})
                                    {{ $part->part->name }}</td>
                            </tr>
                            @php
                                $boq_total = 0;
                                $total = 0;
                                $this_bill_total = 0;
                                $previous_bill_total = 0;

                            @endphp
                            {{-- @dd($part->items[0]) --}}
                            @foreach ($part->items as $item)
                                @if ($item->item->pile_type == 'NA' || $info->scheme->pile_type == $item->item->pile_type)
                                    @if (!$item->item->has_sub_items)
                                        @if ($item->boq_version_details)
                                            @php
                                                $boq_total += $item->boq_version_details
                                                    ? $item->boq_version_details->quantity *
                                                        $item->boq_version_details->rate
                                                    : 0;

                                                $total += $item->this_bill_detail
                                                    ? $item->this_bill_detail->amount
                                                    : ($item->old_bill_detail
                                                        ? $item->old_bill_detail->amount
                                                        : 0);

                                                $this_bill_total += $item->this_bill_detail
                                                    ? $item->this_bill_detail->this_bill_amount
                                                    : 0;
                                                $previous_bill_total += $item->this_bill_detail
                                                    ? $item->this_bill_detail->previous_quantity *
                                                        $item->this_bill_detail->rate
                                                    : ($item->old_bill_detail
                                                        ? ($item->old_bill_detail->quantity -
                                                                $item->old_bill_detail->held_up_quantity) *
                                                            $item->old_bill_detail->rate
                                                        : 0);
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $item->item->code }}</td>
                                                <td>{{ $item->item->name }}</td>
                                                <td class="text-center">
                                                    {{ $item->item->unit ? $item->item->unit->code : '' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item->boq_version_details ? number_format($item->boq_version_details->quantity, 3) : '' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item->boq_version_details ? number_format($item->boq_version_details->rate, 2) : '' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item->boq_version_details ? number_format($item->boq_version_details->quantity * $item->boq_version_details->rate, 2) : '' }}
                                                </td>

                                                <td class="text-right">
                                                    @if ($item->this_bill_detail)
                                                        {{ $item->this_bill_detail->quantity - $item->this_bill_detail->held_up_quantity > 0 ? number_format($item->this_bill_detail->quantity - $item->this_bill_detail->held_up_quantity, 3) : '-' }}
                                                    @elseif($item->old_bill_detail)
                                                        {{ $item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity > 0 ? number_format($item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity, 3) : '-' }}
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if ($item->this_bill_detail)
                                                        {{ number_format($item->this_bill_detail->amount, 2) }}
                                                    @elseif($item->old_bill_detail)
                                                        {{ number_format($item->old_bill_detail->amount, 2) }}
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </td>
                                                @if ($last_bill)
                                                    <td class="text-right">
                                                        @if ($item->this_bill_detail)
                                                            {{ $item->this_bill_detail->previous_quantity > 0 ? number_format($item->this_bill_detail->previous_quantity, 3) : '-' }}
                                                        @elseif($item->old_bill_detail)
                                                            {{ $item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity > 0 ? number_format($item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity, 3) : '-' }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        @if ($item->this_bill_detail)
                                                            {{ $item->this_bill_detail->previous_quantity > 0 ? number_format($item->this_bill_detail->previous_quantity * $item->this_bill_detail->rate, 2) : '-' }}
                                                        @elseif($item->old_bill_detail)
                                                            {{ $item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity > 0 ? number_format(($item->old_bill_detail->quantity - $item->old_bill_detail->held_up_quantity) * $item->old_bill_detail->rate, 2) : '-' }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="text-right">
                                                    {{ $item->this_bill_detail ? number_format($item->this_bill_detail->this_bill_quantity, 3) : '-' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item->this_bill_detail ? number_format($item->this_bill_detail->this_bill_amount, 2) : '-' }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="{{ $colspan }}" class="text-bold">
                                                ({{ $item->item->code }})
                                                {{ $item->item->name }}</td>
                                        </tr>
                                        @foreach ($item->sub_items as $sub_item)
                                            @if ($sub_item->boq_version_details)
                                                @php
                                                    $boq_total += $sub_item->boq_version_details
                                                        ? $sub_item->boq_version_details->quantity *
                                                            $sub_item->boq_version_details->rate
                                                        : 0;

                                                    $total += $sub_item->this_bill_detail
                                                        ? $sub_item->this_bill_detail->amount
                                                        : ($sub_item->old_bill_detail
                                                            ? $sub_item->old_bill_detail->amount
                                                            : 0);

                                                    $this_bill_total += $sub_item->this_bill_detail
                                                        ? $sub_item->this_bill_detail->this_bill_amount
                                                        : 0;
                                                    $previous_bill_total += $sub_item->this_bill_detail
                                                        ? $sub_item->this_bill_detail->previous_quantity *
                                                            $sub_item->this_bill_detail->rate
                                                        : ($sub_item->old_bill_detail
                                                            ? ($sub_item->old_bill_detail->quantity -
                                                                    $sub_item->old_bill_detail->held_up_quantity) *
                                                                $sub_item->old_bill_detail->rate
                                                            : 0);
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $sub_item->sub_item->code }}</td>
                                                    <td>{{ $sub_item->sub_item->name }}</td>
                                                    <td class="text-center">
                                                        {{ $sub_item->sub_item->unit ? $sub_item->sub_item->unit->code : '' }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $sub_item->boq_version_details ? number_format($sub_item->boq_version_details->quantity, 3) : '' }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $sub_item->boq_version_details ? number_format($sub_item->boq_version_details->rate, 2) : '' }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $sub_item->boq_version_details ? number_format($sub_item->boq_version_details->quantity * $sub_item->boq_version_details->rate, 2) : '' }}
                                                    </td>

                                                    <td class="text-right">
                                                        @if ($sub_item->this_bill_detail)
                                                            {{ $sub_item->this_bill_detail->quantity - $sub_item->this_bill_detail->held_up_quantity > 0 ? number_format($sub_item->this_bill_detail->quantity - $sub_item->this_bill_detail->held_up_quantity, 3) : '-' }}
                                                        @elseif($sub_item->old_bill_detail)
                                                            {{ $sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity > 0 ? number_format($sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity, 3) : '-' }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        @if ($sub_item->this_bill_detail)
                                                            {{ number_format($sub_item->this_bill_detail->amount, 2) }}
                                                        @elseif($sub_item->old_bill_detail)
                                                            {{ number_format($sub_item->old_bill_detail->amount, 2) }}
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    </td>
                                                    @if ($last_bill)
                                                        <td class="text-right">
                                                            @if ($sub_item->this_bill_detail)
                                                                {{ $sub_item->this_bill_detail->previous_quantity > 0 ? number_format($sub_item->this_bill_detail->previous_quantity, 3) : '-' }}
                                                            @elseif($sub_item->old_bill_detail)
                                                                {{ $sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity > 0 ? number_format($sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity, 3) : '-' }}
                                                            @else
                                                                {{ '-' }}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            @if ($sub_item->this_bill_detail)
                                                                {{ $sub_item->this_bill_detail->previous_quantity > 0 ? number_format($sub_item->this_bill_detail->previous_quantity * $sub_item->this_bill_detail->rate, 2) : '-' }}
                                                            @elseif($sub_item->old_bill_detail)
                                                                {{ $sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity > 0 ? number_format(($sub_item->old_bill_detail->quantity - $sub_item->old_bill_detail->held_up_quantity) * $sub_item->old_bill_detail->rate, 2) : '-' }}
                                                            @else
                                                                {{ '-' }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td class="text-right">
                                                        {{ $sub_item->this_bill_detail ? number_format($sub_item->this_bill_detail->this_bill_quantity, 3) : '-' }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $sub_item->this_bill_detail ? number_format($sub_item->this_bill_detail->this_bill_amount, 2) : '-' }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
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
                                    ({{ $part->part->code }})
                                    {{ $part->part->name }}=</td>
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
                                    {{ number_format($this_bill_total, 2) }}</td>
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
                                {{ number_format($part_this_bill_total, 2) }}</td>
                            <td></td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>

            </div>



            {{-- ******************************************************************************** --}}
            {{-- ******************************************************************************** --}}
            {{-- ******************************************************************************** --}}
            {{-- **********************************Measurements********************************** --}}
            {{-- ******************************************************************************** --}}
            {{-- ******************************************************************************** --}}
            {{-- ******************************************************************************** --}}



            <div class="page portrait-page">


                <table class="table table-bordered table-hover pb-5" id="boq-version-table76">


                    <tbody>
                        <tr class="text-bold">
                            <td colspan="{{ $colspan }}" class="text-center no-border heading">Local Government
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
                                {{ $info->scheme->package->name }}
                                {{ $info->scheme->package->code }}</td>
                        </tr>
                        <tr class="text-bold">
                            <td colspan="{{ $colspan }}" class="text-center no-border sub-heading">Measurement
                                Sheet</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-hover no break" id="boq-version-table1212">

                    <tbody>
                        <tr class="text-bold">
                            <td colspan="3">Name of Shelter:{{ $info->scheme->name }}</td>
                            <td colspan="2">Upazila: {{ $info->scheme->upazila->name }}</td>
                            <td colspan="1" class="text-center">Shelter ID: {{ $info->scheme->code }}</td>
                            <td colspan="2" class="text-center">{{ $info->scheme->scheme_option->name }}</td>
                            <td colspan="2" class="text-center">Measurement Date: @if ($this_bill->measurement_from_date && $this_bill->measurement_to_date)
                                    {{ date('d F, Y', strtotime($this_bill->measurement_from_date)) }} to
                                    {{ date('d F, Y', strtotime($this_bill->measurement_to_date)) }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-hover no break" id="boq-version-table1212">
                        @foreach ($info->parts as $part)
                            <tr>
                                <td colspan="10" class="text-bold no-border">Bill - ({{ $part->part->code }})
                                    {{ $part->part->name }}</td>
                            </tr>
                            @foreach ($part->items as $item)
                                @if (!$item->item->has_sub_items)
                                    @if ($item->this_bill_detail || $item->old_bill_detail)
                                        @php
                                            $m_colspan = count(json_decode($item->item->unit->fields)) + 7;
                                        @endphp
                                        @if (!$loop->first)
                                            <tr>
                                                <td class="text-bold no-border">&nbsp;</td>
                                            </tr>
                                            {{-- <tr>
                                                <td class="text-bold no-border">&nbsp;</td>
                                            </tr> --}}
                                        @endif

                                        <tr>
                                            <td colspan="{{ $m_colspan }}" class="text-bold no-border">Name of
                                                Item:
                                                {{ $item->item->code }} : {{ $item->item->name }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 5px;">SL No.</th>
                                            <th style="width: 5px;">Spec. No</th>
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
                                        @php
                                            $measurements = $item->this_bill_detail
                                                ? $item->this_bill_detail
                                                : ($item->old_bill_detail
                                                    ? $item->old_bill_detail
                                                    : []);
                                        @endphp
                                        @foreach ($measurements->measurements as $measurement)
                                            @php
                                                $workdone = $measurement->quantity;
                                                $previous = $item->this_bill_detail
                                                    ? $item->this_bill_detail->previous_quantity
                                                    : ($item->old_bill_detail
                                                        ? $item->old_bill_detail->previous_quantity +
                                                            $item->old_bill_detail->this_bill_quantity
                                                        : 0);
                                                $heldup = $item->this_bill_detail
                                                    ? $item->this_bill_detail->held_up_quantity
                                                    : ($item->old_bill_detail
                                                        ? $item->old_bill_detail->held_up_quantity
                                                        : 0);
                                                $thisbill = $item->this_bill_detail
                                                    ? $item->this_bill_detail->this_bill_quantity
                                                    : 0;
                                                    if($measurement->measurement_details && count($measurement->measurement_details) > 0){
                                                        $measurement_details= $measurement->measurement_details;
                                                        $scheme= $info->scheme;
                                                        $boq_item= $item;
                                                        array_push($measurement_details_view, View::make('backend.bill.partials.measurement_details', compact('project','colspan','measurement_details','scheme','this_bill','boq_item'))->render());
                                                    }
                                            @endphp
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
                                            {{-- @if (in_array('piece', json_decode($item->item->unit->fields)))
                                                <td colspan="3" class="no-border"></td>
                                            @else --}}
                                            <td colspan="4" class="no-border"></td>
                                            {{-- @endif --}}
                                            <td colspan="{{ in_array('piece', json_decode($item->item->unit->fields)) ? 0 : count(json_decode($item->item->unit->fields)) + 1 }}"
                                                class="text-right text-bold">Work done Quantity</td>
                                            <td class="text-right text-bold">
                                                {{ number_format($workdone, 3) }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            {{-- @if (in_array('piece', json_decode($item->item->unit->fields)))
                                                <td colspan="3" class="no-border"></td>
                                            @else --}}
                                            <td colspan="4" class="no-border"></td>
                                            {{-- @endif --}}
                                            <td colspan="{{ in_array('piece', json_decode($item->item->unit->fields)) ? 0 : count(json_decode($item->item->unit->fields)) + 1 }}"
                                                class="text-right text-bold">Previous Quantity</td>
                                            <td class="text-right text-bold">
                                                {{ number_format($previous, 3) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            {{-- @if (in_array('piece', json_decode($item->item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else --}}
                                            <td colspan="4" class="no-border"></td>
                                            {{-- @endif --}}
                                            <td colspan="{{ in_array('piece', json_decode($item->item->unit->fields)) ? 0 : count(json_decode($item->item->unit->fields)) + 1 }}"
                                                class="text-right text-bold">BOQ Quantity</td>
                                            <td class="text-right text-bold">
                                                {{ number_format($item->boq_version_details->quantity, 3) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            {{-- @if (in_array('piece', json_decode($item->item->unit->fields)))
                                                <td colspan="3" class="no-border"></td>
                                            @else --}}
                                            <td colspan="4" class="no-border"></td>
                                            {{-- @endif --}}
                                            <td colspan="{{ in_array('piece', json_decode($item->item->unit->fields)) ? 0 : count(json_decode($item->item->unit->fields)) + 1 }}"
                                                class="text-right text-bold">Held Up Quantity</td>
                                            <td class="text-right text-bold">
                                                {{ number_format($heldup, 3) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            {{-- @if (in_array('piece', json_decode($item->item->unit->fields)))
                                                <td colspan="3" class="no-border"></td>
                                            @else --}}
                                            <td colspan="4" class="no-border"></td>
                                            {{-- @endif --}}
                                            <td colspan="{{ in_array('piece', json_decode($item->item->unit->fields)) ? 0 : count(json_decode($item->item->unit->fields)) + 1 }}"
                                                class="text-right text-bold">This Bill Quantity</td>
                                            <td class="text-right text-bold">
                                                {{ number_format($thisbill, 3) }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @else
                                    @foreach ($item->sub_items as $sub_item)
                                        @if ($sub_item->this_bill_detail || $sub_item->old_bill_detail)
                                            <tr>
                                                <td colspan="{{ $colspan }}" class="text-bold no-border">Name
                                                    of
                                                    Item:
                                                    {{ $item->item->code }} : {{ $item->item->name }}</td>
                                            </tr>
                                            @php
                                                $m_colspan = in_array(
                                                    'piece',
                                                    json_decode($sub_item->sub_item->unit->fields),
                                                )
                                                    ? count(json_decode($sub_item->sub_item->unit->fields)) + 6
                                                    : count(json_decode($sub_item->sub_item->unit->fields)) + 7;
                                            @endphp
                                            @if (!$loop->first)
                                                <tr>
                                                    <td class="text-bold no-border">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold no-border">&nbsp;</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="{{ $m_colspan }}" class="text-bold no-border">Name
                                                    of
                                                    Sub
                                                    Item:
                                                    {{ $sub_item->sub_item->code }} :
                                                    {{ $sub_item->sub_item->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 5px;">SL No.</th>
                                                <th style="width: 5px;">Sspec. No</th>
                                                <th>Description</th>
                                                <th style="width: 5px;">Unit</th>
                                                <th>Nos</th>
                                                @if (in_array('length', json_decode($sub_item->sub_item->unit->fields)))
                                                    <th>Length</th>
                                                @endif
                                                @if (in_array('width', json_decode($sub_item->sub_item->unit->fields)))
                                                    <th>Width</th>
                                                @endif
                                                @if (in_array('height', json_decode($sub_item->sub_item->unit->fields)))
                                                    <th>Height</th>
                                                @endif
                                                @if (in_array('weight', json_decode($sub_item->sub_item->unit->fields)))
                                                    <th>Weight</th>
                                                @endif
                                                <th>Quantity</th>
                                                <th style="width: 5px;">Remarks</th>
                                            </tr>
                                            @php
                                                $measurements = $sub_item->this_bill_detail
                                                    ? $sub_item->this_bill_detail
                                                    : ($sub_item->old_bill_detail
                                                        ? $sub_item->old_bill_detail
                                                        : []);
                                            @endphp
                                            @foreach ($measurements->measurements as $measurement)
                                                @php
                                                    $workdone = $measurement->quantity;
                                                    $previous = $item->this_bill_detail
                                                        ? $item->this_bill_detail->previous_quantity
                                                        : ($item->old_bill_detail
                                                            ? $item->old_bill_detail->previous_quantity +
                                                                $item->old_bill_detail->this_bill_quantity
                                                            : 0);
                                                    $heldup = $item->this_bill_detail
                                                        ? $item->this_bill_detail->held_up_quantity
                                                        : ($item->old_bill_detail
                                                            ? $item->old_bill_detail->held_up_quantity
                                                            : 0);
                                                    $thisbill = $item->this_bill_detail
                                                        ? $item->this_bill_detail->this_bill_quantity
                                                        : 0;
                                                        
                                                    if($measurement->measurement_details && count($measurement->measurement_details) > 0){
                                                        $measurement_details= $measurement->measurement_details;
                                                        $scheme= $info->scheme;
                                                        $boq_item= $sub_item;
                                                        array_push($measurement_details_view, View::make('backend.bill.partials.measurement_details', compact('project','colspan','measurement_details','scheme','this_bill','boq_item'))->render());
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $sub_item->sub_item->code }}</td>
                                                    <td class="text-center"></td>
                                                    <td>{{ $measurement->description }}</td>
                                                    <td class="text-center">{{ $sub_item->sub_item->unit->code }}
                                                    </td>
                                                    <td class="text-right">{{ $measurement->nos }}</td>
                                                    @if (in_array('length', json_decode($sub_item->sub_item->unit->fields)))
                                                        <td class="text-right">{{ $measurement->length }}</td>
                                                    @endif
                                                    @if (in_array('width', json_decode($sub_item->sub_item->unit->fields)))
                                                        <td class="text-right">{{ $measurement->width }}</td>
                                                    @endif
                                                    @if (in_array('height', json_decode($sub_item->sub_item->unit->fields)))
                                                        <td class="text-right">{{ $measurement->height }}</td>
                                                    @endif
                                                    @if (in_array('weight', json_decode($sub_item->sub_item->unit->fields)))
                                                        <td class="text-right">{{ $measurement->weight }}</td>
                                                    @endif
                                                    <td class="text-right">{{ $measurement->quantity }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                @if (in_array('piece', json_decode($sub_item->sub_item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else
                                                    <td colspan="4" class="no-border"></td>
                                                @endif
                                                <td colspan="{{ count(json_decode($sub_item->sub_item->unit->fields)) + 1 }}"
                                                    class="text-right text-bold">Work done Quantity</td>
                                                <td class="text-right text-bold">
                                                    {{ number_format($workdone, 3) }}</td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                @if (in_array('piece', json_decode($sub_item->sub_item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else
                                                    <td colspan="4" class="no-border"></td>
                                                @endif
                                                <td colspan="{{ count(json_decode($sub_item->sub_item->unit->fields)) + 1 }}"
                                                    class="text-right text-bold">Previous Quantity</td>
                                                <td class="text-right text-bold">
                                                    {{ number_format($previous, 3) }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                @if (in_array('piece', json_decode($sub_item->sub_item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else
                                                    <td colspan="4" class="no-border"></td>
                                                @endif
                                                <td colspan="{{ count(json_decode($sub_item->sub_item->unit->fields)) + 1 }}"
                                                    class="text-right text-bold">BOQ Quantity</td>
                                                <td class="text-right text-bold">
                                                    {{ number_format($sub_item->boq_version_details->quantity, 3) }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                @if (in_array('piece', json_decode($sub_item->sub_item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else
                                                    <td colspan="4" class="no-border"></td>
                                                @endif
                                                <td colspan="{{ count(json_decode($sub_item->sub_item->unit->fields)) + 1 }}"
                                                    class="text-right text-bold">Held Up Quantity</td>
                                                <td class="text-right text-bold">
                                                    {{ number_format($heldup, 3) }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                @if (in_array('piece', json_decode($sub_item->sub_item->unit->fields)))
                                                    <td colspan="3" class="no-border"></td>
                                                @else
                                                    <td colspan="4" class="no-border"></td>
                                                @endif
                                                <td colspan="{{ count(json_decode($sub_item->sub_item->unit->fields)) + 1 }}"
                                                    class="text-right text-bold">This Bill Quantity</td>
                                                <td class="text-right text-bold">
                                                    {{ number_format($thisbill, 3) }}</td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colspan }}" class="text-bold no-border">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($measurement_details_view && count($measurement_details_view) > 0)
                @foreach ($measurement_details_view as $view)
                    {!! $view !!}
                @endforeach
                @endif
        @endif
    @endforeach


</body>

</html>
