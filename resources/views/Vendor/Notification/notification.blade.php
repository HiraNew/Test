@extends('layouts.vendor')

@section('title', 'Sent Notifications')

@section('content')
<div class="container mt-4">
    <h3>📨 Sent Notifications (Grouped by Product)</h3>

    @if($productNotifications->isEmpty())
        <div class="alert alert-info">You haven't sent any notifications yet.</div>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Notification Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productNotifications as $index => $item)
                    <tr class="product-row" data-product-id="{{ $item->product_id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->notification_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notifications for Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="notificationDetails" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Sent At</th>
                            </tr>
                        </thead>
                        <tbody id="notificationBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('.product-row').on('click', function () {
        const productId = $(this).data('product-id');

        $.ajax({
            url: `/vendor/product-notifications/${productId}`,
            type: 'GET',
            success: function (notifications) {
                let rows = '';
                if (notifications.length === 0) {
                    rows = '<tr><td colspan="4">No notifications found.</td></tr>';
                } else {
                    notifications.forEach(notification => {
                        rows += `
                            <tr>
                                <td>${notification.user?.name ?? 'N/A'}</td>
                                <td>${notification.notification}</td>
                                <td>${notification.status ?? 'N/A'}</td>
                                <td>${formatDate(notification.created_at)}</td>
                            </tr>
                        `;
                    });
                }
                $('#notificationBody').html(rows);
                new bootstrap.Modal(document.getElementById('notificationModal')).show();
            },
            error: function () {
                alert('Failed to fetch details.');
            }
        });
    });

    function formatDate(dateStr) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: '2-digit', hour: '2-digit', minute: '2-digit' };
        return new Date(dateStr).toLocaleDateString('en-US', options);
    }
});
</script>
@endpush
