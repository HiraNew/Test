@extends('layouts.app')

@section('content')

<div class="container my-5">
    <h1 class="mb-4 text-center">My Orders</h1>
    <form method="GET" action="{{ route('payments.index') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by product name...">
    </div>
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
    </div>
    <div class="col-md-4 d-flex align-items-center">
        <button type="submit" class="btn btn-primary me-2">Filter</button>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="alert alert-primary text-center">
                <strong>Total Orders:</strong> {{ $totalOrders }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success text-center">
                <strong>Delivered:</strong> {{ $deliveredOrders }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-danger text-center">
                <strong>Cancelled:</strong> {{ $cancelledOrders }}
            </div>
        </div>
    </div>



    @if($payments->count())
        <div class="row row-cols-1 g-3">
            @foreach($payments as $payment)
                <div class="col">
                    <a href="{{ route('payments.show', $payment->id) }}" class="text-decoration-none text-dark">
                        <div class="card shadow-sm border-0 hover-shadow transition-hover">
                            <div class="card-body d-flex align-items-center">
                                {{-- Thumbnail on Left --}}
                                @if($payment->product->image)
                                    <img src="{{ asset('storage/'.$payment->product->image) }}"
                                        class="rounded me-3" 
                                        alt="{{ $payment->product->name }}"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded me-3"
                                        style="width: 60px; height: 60px;">
                                        <i class="bi bi-box"></i>
                                    </div>
                                @endif

                                {{-- Text Details --}}
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $payment->product->name }}</h6>
                                    <p class="mb-1 text-muted small">Amount: ₹{{ number_format($payment->amount, 2) }}</p>
                                    <span class="badge 
                                        @if($payment->status == 'delivered') bg-success 
                                        @elseif($payment->status == 'cancelled') bg-danger 
                                        @else bg-warning text-dark 
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    {{$payment->product->id}}
                                </div>

                                {{-- Timestamp --}}
                                <div class="text-end text-muted small d-none d-md-block">
                                    {{ $payment->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">
            No orders found.
        </div>
    @endif
    

</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: scale(1.01);
        transition: all 0.3s ease-in-out;
    }
    .transition-hover {
        transition: transform 0.2s ease-in-out;
    }
</style>

<script>
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>
@endsection
