@extends('admin.layouts.master')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="row justify-content-center text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Admin Dashboard</h1>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <input type="text" id="statsSearch" class="form-control form-control-lg" placeholder="Search stats (e.g., revenue, users)">
        </div>
    </div>

    <!-- Stats -->
    @php
        $colors = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];
        $icons = ['fa-user', 'fa-users', 'fa-money-bill', 'fa-box', 'fa-chart-line', 'fa-file-invoice-dollar'];
    @endphp

    @foreach($stats as $group => $items)
        <div class="mb-4">
            <h4 class="text-uppercase fw-bold text-muted border-bottom pb-1 mb-3">{{ $group }}</h4>
            <div class="row">
                @foreach($items as $index => $stat)
                    <div class="col-md-3 mb-4 stat-card" data-title="{{ strtolower($stat['title']) }}">
                        <div class="card shadow-sm border-0 rounded-3 h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 fs-1 text-{{ $colors[$index % count($colors)] }}">
                                    <i class="fas {{ $icons[$index % count($icons)] }}"></i>
                                </div>
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1"
                                        data-bs-toggle="tooltip"
                                        title="{{ $stat['title'] }}">{{ $stat['title'] }}</h6>
                                    <h3 class="fw-bold mb-0">{{ $stat['value'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Charts -->
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

    <!-- Orders Search Input -->
<div class="row my-3">
    <div class="col-md-6 mx-auto">
        <input type="text" id="ordersSearch" class="form-control" placeholder="Search orders by customer, order ID, or status">
    </div>
</div>

    <!-- Recent Orders -->
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
                    <tbody id="ordersTableBody">
                        @foreach($recentOrders ?? [] as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order['customer'] ?? 'N/A' }}</td>
                                <td>{{ $order['orderid'] }}</td>
                                <td>{{ $order['item_count'] }}</td>
                                <td>{{ $order['payment_mode'] }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $order['status'] === 'successful' ? 'bg-success' : 
                                        ($order['status'] === 'pending' ? 'bg-warning text-dark' : 
                                        ($order['status'] === 'canceled' ? 'bg-danger' : 'bg-secondary')) }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td>{{ $order['order_date'] }}</td>
                                <td>{{ $order['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
{{-- @push('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
    let monthlyChart, weeklyChart, dailyChart;
    let lastDataHash = null;

    function hashData(data) {
        return CryptoJS.SHA256(JSON.stringify(data)).toString();
    }



    function renderChart(chartId, labels, data, borderColor) {
        const ctx = document.getElementById(chartId).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: borderColor + '33',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                },
                interaction: { mode: 'nearest', intersect: false },
                scales: {
                    x: { title: { display: true, text: 'Time' }},
                    y: { beginAtZero: true, title: { display: true, text: 'Revenue (₹)' }}
                }
            }
        });
    }

    async function loadDashboardData() {
        try {
            const res = await fetch('{{ route("admin.dashboard.data") }}');
            const data = await res.json();

            const currentHash = hashData(data);
            if (currentHash === lastDataHash) {
                // Data hasn't changed, skip DOM update
                return;
            }

            lastDataHash = currentHash; // Update the hash for next comparison

            // 🟢 Update Stats
            document.querySelectorAll('.stat-card').forEach(card => {
                const title = card.getAttribute('data-title');
                for (const group in data.stats) {
                    const stat = data.stats[group].find(item => item.title.toLowerCase() === title);
                    if (stat) {
                        card.querySelector('h3').textContent = stat.value;
                    }
                }
            });

            // 🟢 Update Charts
            if (monthlyChart) monthlyChart.destroy();
            if (weeklyChart) weeklyChart.destroy();
            if (dailyChart) dailyChart.destroy();

            monthlyChart = renderChart('monthlyRevenueChart', data.monthlyLabels, data.monthlyRevenue, '#007bff');
            weeklyChart = renderChart('weeklyRevenueChart', data.weeklyLabels, data.weeklyRevenue, '#28a745');
            dailyChart = renderChart('dailyRevenueChart', data.dailyLabels, data.dailyRevenue, '#dc3545');

            // 🟢 Update Recent Orders
            let i = 1;
            const tbody = document.getElementById('ordersTableBody');
            tbody.innerHTML = '';
            data.recentOrders.forEach(order => {
                tbody.innerHTML += `
                    <tr>
                        <td>${i++}</td>
                        <td>${order.user.name}</td>
                        <td>${order.orderid}</td>
                        <td>${order.item_count}</td>
                        <td>${order.payment_mode}</td>
                        <td>
                            <span class="badge ${
                                order.status === 'delivered' ? 'bg-success' :
                                order.status === 'pending' ? 'bg-warning text-dark' :
                                order.status === 'canceled' ? 'bg-danger' : 'bg-secondary'
                            }">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
                        </td>
                        <td>${order.order_date}</td>
                        <td>${order.total_amount}</td>
                    </tr>
                `;
            });

        } catch (err) {
            console.error("Failed to fetch dashboard data:", err);
        }
    }


    document.addEventListener('DOMContentLoaded', () => {
        loadDashboardData();               // initial fetch
        setInterval(loadDashboardData, 50000000); // every 30 seconds

        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });

    // Optional: Stats card search filter
    document.getElementById('statsSearch').addEventListener('input', function () {
        let search = this.value.toLowerCase();
        document.querySelectorAll('.stat-card').forEach(card => {
            let title = card.getAttribute('data-title');
            card.style.display = title.includes(search) ? 'block' : 'none';
        });
    });

    // Orders Table Search
    document.getElementById('ordersSearch').addEventListener('input', function () {
        let search = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(search) ? '' : 'none';
        });
    });

</script>
{{-- @endpush --}}


@endsection

