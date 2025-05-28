@extends('admin.layouts.master')

@section('content')
<div class="container py-5">
    <!-- Title and Intro -->
    <div class="row justify-content-center text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Admin Dashboard</h1>
        {{-- <p class="lead text-muted">Welcome to your admin panel. Monitor key metrics and manage your platform efficiently.</p> --}}
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        @foreach($stats as $stat)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 fs-1 text-{{ $stat['color'] ?? 'primary' }}">
                            <i class="{{ $stat['icon'] ?? 'fas fa-chart-bar' }}"></i>
                        </div>
                        <div>
                            <h6 class="text-uppercase text-muted mb-1" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $stat['title'] }}">
                                {{ $stat['title'] }}
                            </h6>
                            <h3 class="fw-bold mb-0">{{ $stat['value'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Earnings Graphs -->
    <div class="row mb-5">
        @foreach([
            ['id' => 'monthlyRevenueChart', 'title' => 'Monthly Revenue', 'color' => '#007bff'],
            ['id' => 'weeklyRevenueChart', 'title' => 'Weekly Revenue', 'color' => '#28a745'],
            ['id' => 'dailyRevenueChart', 'title' => "Today's Revenue", 'color' => '#dc3545'],
        ] as $chart)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-light text-center fw-semibold text-secondary">
                        {{ $chart['title'] }}
                    </div>
                    <div class="card-body">
                        <canvas id="{{ $chart['id'] }}" style="height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Orders Table -->
    <!-- Search Form -->


    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-light fw-semibold">
            Recent Orders
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ $order->orderid }}</td>
                                <td>{{ $order->item_count }}</td>
                                <td>{{ $order->payment_mode === 'cod' ? 'Cash On Delivery' : 'Online' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'completed' => 'badge bg-success',
                                            'pending' => 'badge bg-warning text-dark',
                                            'canceled' => 'badge bg-danger',
                                            default => 'badge bg-secondary'
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- Pagination Links -->

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartConfigs = [
            {
                id: 'monthlyRevenueChart',
                labels: @json($monthlyLabels),
                data: @json($monthlyRevenue),
                borderColor: '#007bff'
            },
            {
                id: 'weeklyRevenueChart',
                labels: @json($weeklyLabels),
                data: @json($weeklyRevenue),
                borderColor: '#28a745'
            },
            {
                id: 'dailyRevenueChart',
                labels: @json($dailyLabels),
                data: @json($dailyRevenue),
                borderColor: '#dc3545'
            }
        ];

        chartConfigs.forEach(config => {
            const ctx = document.getElementById(config.id).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: config.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: config.data,
                        borderColor: config.borderColor,
                        backgroundColor: config.borderColor + '33', // slight transparent fill
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: { display: true, text: 'Time' }
                        },
                        y: {
                            display: true,
                            title: { display: true, text: 'Revenue ($)' },
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Enable Bootstrap tooltips for stats cards
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
