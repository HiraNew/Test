@extends('layouts.vendor')

@section('title', 'Sent Notifications')

@section('content')
<div class="container mt-4">
    <h3>📨 Sent Notifications</h3>

    @if($notifications->isEmpty())
        <div class="alert alert-info">You haven't sent any notifications yet.</div>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $index => $notification)
                <tr>
                    <td>{{ $index + $notifications->firstItem() }}</td>
                    <td>{{ $notification->user->name ?? 'N/A' }}</td>
                    <td>{{ $notification->product->name ?? 'N/A' }}</td>
                    <td>{{ $notification->notification }}</td>
                    <td>{{ $notification->status ?? 'N/A' }}</td>
                    <td>{{ $notification->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
