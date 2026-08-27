@extends('backend.user.layouts.app')
@section('title', 'Dashboard')
@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">

            {{-- Row 1: Small boxes (Stat box) --}}
            <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalBills }}</h3>
                        <p>My Bills</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <a href="{{ route('user.bills.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $draftBills }}</h3>
                            <p>Draft Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <a href="{{ route('user.bills.create') }}" class="small-box-footer">
                            Continue editing <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 style="font-size: 26px;">৳{{ number_format($totalBilledAmount / 1000000, 2) }}M</h3>
                            <p>Total Billed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <div class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white;">
                            All-time billed
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $schemesCovered }}</h3>
                            <p>Schemes Covered</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white;">
                            Current package
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- Main row --}}
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7">

                    <!-- Charts with tabs -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                My Bill Analytics
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#bills-month-tab" data-toggle="tab">Bar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#amount-tab" data-toggle="tab">Donut</a>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="chart tab-pane active" id="bills-month-tab" style="position: relative; height: 300px;">
                                    <canvas id="billsByMonthChart" height="300" style="height: 300px;"></canvas>
                                </div>
                                <div class="chart tab-pane" id="amount-tab" style="position: relative; height: 300px;">
                                    <canvas id="billAmountChart" height="300" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- My Recent Bills table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                My Recent Bills
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('user.bills.index') }}" class="btn btn-tool btn-sm">
                                    View all <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Schemes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBills as $bill)
                                        <tr>
                                            <td>{{ $bill->name }}</td>
                                            <td>{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : '-' }}</td>
                                            <td><span class="badge badge-info">{{ $bill->bill_scheme_count }}</span></td>
                                            <td>
                                                @if ($bill->status === 'Draft')
                                                    <span class="badge badge-warning">{{ $bill->status }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ $bill->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No bills yet. Create your first bill!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </section>
                <!-- /.Left col -->

                <!-- Right col -->
                <section class="col-lg-5">

                    <!-- Bills needing measurements -->
                    <div class="card bg-gradient-danger">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                Bills Needing Measurements
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-danger btn-sm" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0" style="max-height: 270px; overflow-y: auto;">
                            <table class="table table-striped table-valign-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Schemes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($billsNeedingMeasurements as $bill)
                                        <tr>
                                            <td>{{ $bill->name }}</td>
                                            <td><span class="badge badge-secondary">{{ $bill->bill_scheme_count }}</span></td>
                                            <td>
                                                <a href="{{ route('user.bills.details.measurement', $bill->id) }}?schemes=All&boq_part_id=&boq_item_id="
                                                    class="btn btn-sm btn-outline-light">
                                                    <i class="fas fa-ruler"></i> Add
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-light">All bills have measurements!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- Quick actions gradient card -->
                    <div class="card bg-gradient-success">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-1"></i>
                                Quick Actions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('user.bills.create') }}" class="btn btn-light btn-block mb-2">
                                <i class="fas fa-plus"></i> Create New Bill
                            </a>
                            <a href="{{ route('user.bills.report') }}" class="btn btn-outline-light btn-block">
                                <i class="fas fa-chart-bar"></i> View Bill Report
                            </a>
                        </div>
                    </div>
                    <!-- /.card -->

                </section>
                <!-- /.right col -->
            </div>
            <!-- /.row (main row) -->

        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('script')
    <script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {

            // Bills by Month Bar Chart
            var monthLabels = {!! json_encode($billsByMonth->pluck('month')) !!};
            var monthData = {!! json_encode($billsByMonth->pluck('total')) !!};

            new Chart(document.getElementById('billsByMonthChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Bills',
                        data: monthData,
                        backgroundColor: '#007bff',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Bill Amount Breakdown Doughnut
            var amountLabels = {!! json_encode($billAmountBreakdown->pluck('name')) !!};
            var amountData = {!! json_encode($billAmountBreakdown->pluck('total_amount')) !!};
            var amountColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'];

            new Chart(document.getElementById('billAmountChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: amountLabels,
                    datasets: [{
                        data: amountData,
                        backgroundColor: amountColors.slice(0, amountLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

        });
    </script>
@endsection
