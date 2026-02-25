@extends('layout')
@section('title')
{{ get_label('extract_analytics', 'Extract Analytics') }}
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home.index') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">{{ get_label('reports', 'Reports') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('extract_analytics', 'Extract Analytics') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ url('reports/estimates-invoices') }}" class="btn btn-sm btn-primary me-2">
                <i class="bx bx-file"></i> {{ get_label('invoice_reports', 'Invoice Reports') }}
            </a>
            <a href="{{ route('home.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> {{ get_label('back_to_dashboard', 'Back to Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ get_label('total_estimates', 'Total Estimates') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_estimates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-file fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ get_label('total_invoices', 'Total Invoices') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_invoices }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-receipt fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ get_label('total_extract_value', 'Total Extract Value') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ format_currency($total_extract_value) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-dollar-circle fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ get_label('outstanding_amount', 'Outstanding Amount') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ format_currency($total_unpaid_amount) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-md-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-line-chart"></i> {{ get_label('monthly_trend', 'Monthly Trend') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div id="monthlyTrendChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-pie-chart-alt"></i> {{ get_label('status_distribution', 'Status Distribution') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div id="statusDistributionChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Insights -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-check-circle"></i> {{ get_label('payment_statistics', 'Payment Statistics') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h5 text-success">{{ format_currency($total_paid_amount) }}</div>
                            <div class="small text-muted">{{ get_label('total_paid', 'Total Paid') }}</div>
                            <div class="h3 text-success">{{ $total_paid_invoices }}</div>
                            <div class="small text-muted">{{ get_label('paid_invoices', 'Paid Invoices') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="h5 text-danger">{{ format_currency($total_unpaid_amount) }}</div>
                            <div class="small text-muted">{{ get_label('total_unpaid', 'Total Unpaid') }}</div>
                            <div class="h3 text-danger">{{ $total_unpaid_invoices }}</div>
                            <div class="small text-muted">{{ get_label('unpaid_invoices', 'Unpaid Invoices') }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $total_invoices > 0 ? ($total_paid_invoices / $total_invoices) * 100 : 0 }}%" 
                                aria-valuenow="{{ $total_paid_invoices }}" 
                                aria-valuemin="0" 
                                aria-valuemax="{{ $total_invoices }}">
                                {{ $total_invoices > 0 ? number_format(($total_paid_invoices / $total_invoices) * 100, 1) }}%
                            </div>
                        </div>
                        <div class="small text-muted mt-2">
                            {{ get_label('payment_completion_rate', 'Payment Completion Rate') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-trophy"></i> {{ get_label('top_clients', 'Top Clients') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ get_label('client', 'Client') }}</th>
                                    <th>{{ get_label('total_value', 'Total Value') }}</th>
                                    <th>{{ get_label('documents', 'Documents') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($top_clients as $client)
                                <tr>
                                    <td>{{ $client->client_name }}</td>
                                    <td>{{ format_currency($client->total_value) }}</td>
                                    <td>{{ $client->document_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        {{ get_label('no_data_available', 'No Data Available') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-table"></i> {{ get_label('monthly_summary', 'Monthly Summary') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ get_label('month', 'Month') }}</th>
                                    <th>{{ get_label('estimates', 'Estimates') }}</th>
                                    <th>{{ get_label('invoices', 'Invoices') }}</th>
                                    <th>{{ get_label('revenue', 'Revenue') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthly_data as $data)
                                <tr>
                                    <td>{{ date('F Y', mktime(0, 0, 0, $data->month, 1, $data->year)) }}</td>
                                    <td>{{ $data->estimate_count }}</td>
                                    <td>{{ $data->invoice_count }}</td>
                                    <td>{{ format_currency($data->invoice_total) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        {{ get_label('no_data_available', 'No Data Available') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trend Chart
    const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthly_data as $data)
                    '{{ date('M Y', mktime(0, 0, 0, $data->month, 1, $data->year)) }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ get_label('estimates', 'Estimates') }}',
                data: [
                    @foreach($monthly_data as $data)
                        {{ $data->estimate_count }},
                    @endforeach
                ],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.3
            }, {
                label: '{{ get_label('invoices', 'Invoices') }}',
                data: [
                    @foreach($monthly_data as $data)
                        {{ $data->invoice_count }},
                    @endforeach
                ],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.3
            }, {
                label: '{{ get_label('revenue', 'Revenue') }}',
                data: [
                    @foreach($monthly_data as $data)
                        {{ $data->invoice_total }},
                    @endforeach
                ],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.3,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($status_distribution as $status)
                    '{{ ucfirst(str_replace('_', ' ', $status->status)) }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($status_distribution as $status)
                        {{ $status->count }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection