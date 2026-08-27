@extends('backend.admin.layouts.app')
@section('title','Admin Dashboard')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        {{-- Row 1: Small boxes (Stat box) --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalProjects }}</h3>
                        <p>Projects <span class="text-sm">({{ $activeProjects }} active)</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-th"></i>
                    </div>
                    <a href="{{ route('admin.projects.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalPackages }}</h3>
                        <p>Packages <span class="text-sm">({{ $activePackages }} active)</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{ route('admin.packages.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalSchemes }}</h3>
                        <p>Schemes <span class="text-sm">({{ $activeSchemes }} active)</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <a href="{{ route('admin.schemes.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $totalContractors }}</h3>
                        <p>Contractors <span class="text-sm">({{ $activeContractors }} active)</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <a href="{{ route('admin.contractors.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        {{-- Row 2: Secondary Small boxes --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalBills }}</h3>
                        <p>Total Bills <span class="text-sm">({{ $currentMonthBills }} this month)</span></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <a href="{{ route('admin.bills.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $totalBoqVersions }}</h3>
                        <p>BOQ Versions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <a href="{{ route('admin.boq_versions.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color: #6f42c1; color: white;">
                    <div class="inner">
                        <h3>{{ number_format($billedSchemeCount) }}</h3>
                        <p>Billed Schemes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white;">
                        In all bills
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box" style="background-color: #20c997; color: white;">
                    <div class="inner">
                        <h3 style="font-size: 26px;">৳{{ number_format($totalBillAmount / 1000000, 2) }}M</h3>
                        <p>Total Bill Amount</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white;">
                        All-time billed
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        {{-- Row: Total vs Billed Schemes (full width) --}}
        <div class="row">
            <div class="col-12">
                <div class="card bg-gradient-navy">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Total vs Billed Schemes by Package
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-navy btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="schemesByDistrictChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        {{-- Main row --}}
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">

                <!-- Charts with tabs -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Bill Analytics
                        </h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#monthly-trend-tab" data-toggle="tab">Amount</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#status-tab" data-toggle="tab">Donut</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="chart tab-pane active" id="monthly-trend-tab" style="position: relative; height: 300px;">
                                <canvas id="monthlyBillTrendChart" height="300" style="height: 300px;"></canvas>
                            </div>
                            <div class="chart tab-pane" id="status-tab" style="position: relative; height: 300px;">
                                <canvas id="billsByStatusChart" height="300" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->

                {{-- Row: Package-wise Bill Count (full width) --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-gray-dark">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    Package-wise Bill Count
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-gray-dark btn-sm" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="packageBudgetChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <!-- Recent Bills table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-1"></i>
                            Recent Bills
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.bills.index') }}" class="btn btn-tool btn-sm">
                                View all <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle text-nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contractor</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBills as $bill)
                                <tr>
                                    <td>{{ $bill->name }}</td>
                                    <td>{{ $bill->contractor->company_name ?? 'N/A' }}</td>
                                    <td>{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($bill->status === 'Draft')
                                            <span class="badge badge-warning">{{ $bill->status }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $bill->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No bills found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- Top Contractors table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-trophy mr-1"></i>
                            Top Contractors by Bill Amount
                        </h3>
                    </div><!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contractor</th>
                                    <th>Bills</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topContractors as $index => $contractor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contractor->company_name }}</td>
                                    <td><span class="badge badge-primary">{{ $contractor->bill_count }}</span></td>
                                    <td>৳{{ number_format($contractor->total_amount / 1000000, 2) }}M</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No contractor data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('script')
<script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(function () {

    // Monthly Bill Amount Trend Area Chart
    var trendLabels = {!! json_encode($monthlyBillAmountTrend->pluck('month')) !!};
    var trendData = {!! json_encode($monthlyBillAmountTrend->pluck('total_amount')) !!};

    new Chart(document.getElementById('monthlyBillTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Bill Amount (BDT)',
                data: trendData,
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return '৳' + Number(context.parsed.y).toLocaleString('en-IN');
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Bills by Status Doughnut
    var statusLabels = {!! json_encode($billsByStatus->pluck('status')) !!};
    var statusData = {!! json_encode($billsByStatus->pluck('total')) !!};
    var statusColors = ['#ffc107', '#28a745', '#17a2b8', '#dc3545', '#6c757d', '#343a40'];
    var statusBgColors = statusColors.slice(0, statusLabels.length);

    new Chart(document.getElementById('billsByStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: statusBgColors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Total vs Billed Schemes by Package Grouped Bar
    var schemePackageLabels = {!! json_encode($packageSchemeStats->pluck('name')) !!};
    var totalSchemeData = {!! json_encode($packageSchemeStats->pluck('total_schemes')) !!};
    var billedSchemeData = {!! json_encode($packageSchemeStats->pluck('billed_schemes')) !!};

    new Chart(document.getElementById('schemesByDistrictChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: schemePackageLabels,
            datasets: [
                {
                    label: 'Total Schemes',
                    data: totalSchemeData,
                    backgroundColor: 'rgba(0,100,0,0.95)',
                    borderRadius: 4
                },
                {
                    label: 'Billed Schemes',
                    data: billedSchemeData,
                    backgroundColor: 'rgba(252,235,58,0.95)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: { fontColor: '#fff' }
            },
            scales: {
                xAxes: [{
                    ticks: { fontColor: '#fff' }
                }],
                yAxes: [{
                    ticks: { beginAtZero: true, stepSize: 1, fontColor: '#fff' }
                }]
            }
        }
    });

    // Package-wise Bill Count Bar
    var budgetLabels = {!! json_encode($packageBillCounts->pluck('name')) !!};
    var budgetData = {!! json_encode($packageBillCounts->pluck('total')) !!};

    new Chart(document.getElementById('packageBudgetChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: budgetLabels,
            datasets: [
                {
                    label: 'Bills',
                    data: budgetData,
                    backgroundColor: 'rgba(255,140,0,0.95)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    ticks: { fontColor: '#fff' }
                }],
                yAxes: [{
                    ticks: { beginAtZero: true, stepSize: 1, fontColor: '#fff' }
                }]
            }
        }
    });

});
</script>
@endsection
