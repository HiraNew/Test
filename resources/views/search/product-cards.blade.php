
@extends('layouts.app')

@section('content')
@foreach ($Products as $Product)
    @php
        $inCart = in_array($Product->id, $cartProductIds);
    @endphp
    <div class="col">
        <div class="card h-100 shadow-sm border-0 hover-shadow">
            <a href="{{ url('detail', $Product->id) }}" class="text-decoration-none">
                <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                    <img src="{{ url($Product->image) }}" alt="{{ $Product->name }}" class="img-fluid object-fit-cover">
                </div>
            </a>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold mb-2 text-dark">{{ $Product->name }}</h5>
                <p class="card-text small text-muted flex-grow-1">{{ $Product->sdescription ?? 'No description.' }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-{{ $Product->quantity < 3 ? 'danger' : 'success' }}">
                        {{ $Product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                    <span class="fw-bold text-primary">₹{{ number_format($Product->price, 2) }}</span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 d-flex flex-column gap-2">
                @if ($inCart)
                    <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-shopping-cart me-1"></i>Go to Cart
                    </a>
                @elseif ($Product->quantity < 1)
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="fas fa-ban me-1"></i>Out of Stock
                    </button>
                @else
                    <a href="{{ url('addTocart', $Product->id) }}" class="btn btn-success btn-sm w-100 addToCart">
                        <i class="fas fa-cart-plus me-1"></i>Add to Cart
                    </a>
                @endif
                <a href="{{ url('detail', $Product->id) }}" class="btn btn-warning btn-sm w-100">
                    <i class="fas fa-info-circle me-1"></i>Details
                </a>
            </div>
        </div>
    </div>
@endforeach
@endsection
