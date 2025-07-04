@extends('layouts.vendor')

@section('title', 'Product Analysis')

@section('content')
<div class="container">
    <h2>Recent Product Views</h2>
    <table class="table table-hover" id="viewsTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Unique Users</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentViews as $view)
                <tr class="view-row" data-product-id="{{ $view->product_id }}">
                    <td>{{ $view->product->name }}</td>
                    <td>{{ $view->unique_user_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Modal -->

    <div class="modal fade" id="productAnalysisModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product View Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h5 id="productName"></h5>
                    <div style="max-width: 500px; margin: auto;">
                        <canvas id="viewsChart"></canvas>
                    </div>
                    <div id="viewDetails" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Bootstrap Bundle (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('.view-row').on('click', function () {
        const productId = $(this).data('product-id');

        $.ajax({
            url: `/vendor/recent-views/analysis/${productId}`,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const today = data.today;
                const yesterday = data.yesterday;
                const lastWeek = data.last_week;
                const productName = data.product;

                $('#productName').text(productName);

                const ctx = document.getElementById('viewsChart').getContext('2d');
                if (window.viewChart) window.viewChart.destroy();

                window.viewChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Today', 'Yesterday', 'Last Week'],
                        datasets: [{
                            data: [today.length, yesterday.length, lastWeek.length],
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });

                let html = `<h5>Today's Views</h5>`;
                if (today.length === 0) html += '<p>No views today.</p>';
                else {
                    function formatDate(dateString) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}
                    today.forEach((v, i) => {
                        html += `
                            <div>
                                <strong>#${i + 1} - ${v.user?.name ?? 'Guest'}</strong>
                                <ul>
                                    <li>Viewed At: ${formatDate(v.viewed_at)}</li>
                                    <li>User Agent: ${v.user_agent ?? 'N/A'}</li>
                                    <li>Referrer: ${v.referrer ?? 'N/A'}</li>
                                    <li>Location: ${v.location ?? 'Unknown'}</li>
                                </ul>
                            </div><hr/>
                        `;
                    });
                }

                $('#viewDetails').html(html);

                const modal = new bootstrap.Modal(document.getElementById('productAnalysisModal'));
                modal.show();
            },
            error: function () {
                alert('Could not load analysis.');
            }
        });
    });
});
</script>

@endpush
