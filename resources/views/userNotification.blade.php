@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-semibold fs-5">
                    <i class="fas fa-bell me-2"></i>Notifications
                </div>

                <div class="card-body">
                    <div class="card-body">
                        <div id="notifications-container">
                            {{-- Initial content could be a loading spinner or empty --}}
                            <div class="text-center text-muted">Loading notifications...</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Optional: Animate.css for subtle animation effects --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    let lastNotificationsJSON = null; // store last fetched notifications as JSON string

    function fetchNotifications() {
        $.ajax({
            url: "{{ route('notifications.data') }}",
            method: 'GET',
            success: function(data) {
                // Convert current data to JSON string for easy comparison
                let currentJSON = JSON.stringify(data);

                // Compare with last fetched data
                if (currentJSON !== lastNotificationsJSON) {
                    // Update lastNotificationsJSON
                    lastNotificationsJSON = currentJSON;

                    // Render notifications only if changed
                    if (data.length === 0) {
                        $('#notifications-container').html(`
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>No notifications available.
                            </div>
                        `);
                    } else {
                        let rows = '';
                        data.forEach(function(item) {
                            let productUrl = item.product ? `/detail/${item.product.id}` : '#'; // fallback if no product
                            rows += `
                                <tr>
                                    <td>
                                        <i class="fas fa-dot-circle text-warning me-2"></i>
                                        <a href="${productUrl}" class="text-decoration-none text-dark">
                                            ${item.notification}
                                        </a>
                                    </td>
                                    <td class="text-success fw-semibold">${item.creator.name}</td>
                                </tr>
                            `;
                        });


                        $('#notifications-container').html(`
                            <div class="table-responsive animate__animated animate__fadeIn">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col"><i class="fas fa-envelope me-1"></i>Notification</th>
                                            <th scope="col"><i class="fas fa-user me-1"></i>Sent By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${rows}
                                    </tbody>
                                </table>
                            </div>
                        `);
                    }
                }
                // else: do nothing, no change detected, avoid blinking
            },
            error: function() {
                console.error('Failed to fetch notifications');
            }
        });
    }

    // Initial fetch
    fetchNotifications();

    // Repeat every 10 seconds (10000 ms)
    setInterval(fetchNotifications, 10000);
});
</script>


@endsection
