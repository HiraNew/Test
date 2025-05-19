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
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Optional: Animate.css for subtle animation effects --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
