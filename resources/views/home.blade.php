@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

{{-- Alerts --}}
@foreach (['insert' => 'success', 'error' => 'danger', 'status' => session('status_type', 'info')] as $key => $type)
    @if(session($key))
        <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

<div class="container py-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
        @if (isset($Products) && count($Products) > 0)
            @foreach ($Products as $Product)
                @php
                    $inCart = isset($cartProductIds) && in_array($Product->id, $cartProductIds);
                @endphp
                <div class="col">
                    <!-- Make the entire card clickable by wrapping the card content inside an anchor tag -->
                    <a href="{{ url('detail', $Product->id) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp hover-shadow">
                            <img src="{{ url($Product->image) }}" class="card-img-top" alt="{{ $Product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">{{ $Product->name }}</h5>
                                <p class="card-text text-muted" style="height: 50px; overflow: hidden;">{{ $Product->sdescription }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-{{ $Product->quantity < 3 ? 'danger' : 'success' }}">
                                        {{ $Product->quantity > 0 ? 'In Stock' : 'Out Of Stock' }}
                                    </span>
                                    <span class="badge bg-primary">Rs {{ $Product->price }}</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                @if ($inCart)
                                    <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100 me-1">
                                        <i class="fas fa-shopping-cart me-1"></i>Go to Cart
                                    </a>
                                @elseif ($Product->quantity < 1)
                                    <a href="#" class="btn btn-secondary btn-sm w-100 me-1 disabled outstock">
                                        <i class="fas fa-ban me-1"></i>Out of Stock
                                    </a>
                                @else
                                    <a href="{{ url('addTocart', $Product->id) }}" class="btn btn-success btn-sm w-100 me-1 addToCart">
                                        <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                    </a>
                                @endif
                                <a href="{{ url('detail', $Product->id) }}" class="btn btn-warning btn-sm w-100 ms-1">
                                    <i class="fas fa-info-circle me-1"></i>Details
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <a href="{{ route('login') }}" class="btn btn-outline-primary mt-3">You Have To Login First</a>
            </div>
        @endif
    </div>
</div>

{{-- Styles --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
    }
    .card-title {
        transition: color 0.3s ease;
    }
    .card:hover .card-title {
        color: #0d6efd;
    }
</style>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.outstock').click(function (e) {
            e.preventDefault();
            alert('This product is currently out of stock.');
        });
    });
</script>

@endsection
