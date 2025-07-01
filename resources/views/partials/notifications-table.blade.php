@if($notifications->isEmpty())
    <div class="alert alert-info text-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>No notifications available.
    </div>
@else
    <div class="table-responsive animate__animated animate__fadeIn">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col"><i class="fas fa-envelope me-1"></i>Notification</th>
                    <th scope="col"><i class="fas fa-user me-1"></i>Sent By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $item)
                    <tr>
                        <td>
                            <i class="fas fa-dot-circle text-warning me-2"></i>
                            {{ $item->notification }}
                        </td>
                        <td class="text-success fw-semibold">{{ $item->creator->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
