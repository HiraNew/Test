@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">My Orders</h1>

    @if($payments->count())
        <div class="row g-4">
            @foreach($payments as $payment)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('payments.show', $payment->id) }}" class="card h-100 text-decoration-none text-dark shadow-sm hover-shadow">
                        <div class="card-body">
                            <h5 class="card-title">Order #{{ $payment->product->name }}</h5>
                            <p class="card-text">Amount: ₹{{ number_format($payment->amount, 2) }}</p>
                            <p>Status: 
                                <span class="@if($payment->status == 'delivered') text-success
                                              @elseif($payment->status == 'cancelled') text-danger
                                              @else text-warning
                                              @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            <p class="text-muted small">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
          Showing  {{ $payments->count() }} on page {{ $payments->links() }}
        </div>
    @else
        <p>No orders found.</p>
    @endif
    
</div>
@endsection
