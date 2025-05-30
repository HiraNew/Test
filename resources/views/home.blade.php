@extends('layouts.app')

@section('content')

{{-- SweetAlert2 --}}
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Alerts --}}
@foreach (['insert' => 'success', 'error' => 'danger', 'status' => session('status_type', 'info')] as $key => $type)
    @if(session($key))
        <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

<div class="container-fluid py-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="d-lg-block d-none sticky-top" style="top:80px;">
                @include('layouts.filter-sidebar')
            </div>
            <div class="d-block d-lg-none mb-3">
                <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilter">
                    Apply Filters
                </button>
                <div class="collapse mt-2" id="mobileFilter">
                    @include('layouts.filter-sidebar')
                </div>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="col-lg-9">
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
                @forelse ($Products as $Product)
                    @php $inCart = isset($cartProductIds) && in_array($Product->id, $cartProductIds); @endphp
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 hover-shadow position-relative">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $Product->id }}">
                                <div class="ratio ratio-4x3 overflow-hidden">
                                    <img src="{{ url($Product->image) }}" loading="lazy" class="img-fluid product-image zoom-hover" alt="{{ $Product->name }}">
                                </div>
                            </a>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold mb-1 text-dark">{{ $Product->name }}</h5>
                                @php
                                    $avgRating = round($Product->reviews_avg_rating, 1); // E.g., 4.2
                                    $filledStars = floor($avgRating);
                                    $halfStar = ($avgRating - $filledStars) >= 0.5;
                                @endphp

                                <div class="mb-1 small text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $filledStars)
                                            <i class="fas fa-star"></i>
                                        @elseif ($halfStar && $i == $filledStars + 1)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor

                                    <span class="text-muted">({{ number_format($avgRating, 1) }})</span>
                                    {{ $Product->reviews_count }} review{{ $Product->reviews_count > 1 ? 's' : '' }}
                                </div>


                                <p class="card-text small text-muted flex-grow-1">{{ $Product->sdescription ?? 'No description available.' }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-{{ $Product->quantity < 3 ? 'danger' : 'success' }}">
                                        {{ $Product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                    <span class="fw-bold text-primary">₹{{ number_format($Product->price, 2) }}</span>
                                </div>
                            </div>
                            {{-- Wishlist Icon --}}
                            <button class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn" data-id="{{ $Product->id }}">
                                <i class="{{ in_array($Product->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                            </button>


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

                    {{-- Quick View Modal --}}
                    <div class="modal fade" id="quickViewModal{{ $Product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $Product->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row">
                                    <div class="col-md-6">
                                        <img src="{{ url($Product->image) }}" class="img-fluid" alt="{{ $Product->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $Product->description ?? 'No full description.' }}</p>
                                        <h4 class="text-primary">₹{{ number_format($Product->price, 2) }}</h4>
                                        <div class="mt-3">
                                            <a href="{{ url('addTocart', $Product->id) }}" class="btn btn-success addToCart">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @if($Products->isEmpty())
                        <p>No products found. Showing some random items:</p>
                    @endif

                    <div class="row">
                        @foreach ($Products as $product)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ Str::limit($product->description, 60) }}</p>
                                        <a href="{{ route('product.details', $product->id) }}" class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @endforelse
            </div>
        </div>
        @if($recentViews->isNotEmpty())
<div class="container-fluid mt-5">
    <h4 class="mb-3 fw-bold">Recently Viewed Products</h4>
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-xl-5 g-4">
        @foreach($recentViews as $recent)
            @php $inCart = isset($cartProductIds) && in_array($recent->id, $cartProductIds); @endphp
            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <a href="{{ url('detail', $recent->id) }}">
                        <div class="ratio ratio-4x3 overflow-hidden">
                            <img src="{{ url($recent->image) }}" loading="lazy" class="img-fluid product-image zoom-hover" alt="{{ $recent->name }}">
                        </div>
                    </a>
                    <button class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn" data-id="{{ $recent->id }}">
                        <i class="{{ in_array($recent->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                    </button>

                    @php
                        $avgRating = round($recent->reviews_avg_rating, 1);
                        $filledStars = floor($avgRating);
                        $halfStar = ($avgRating - $filledStars) >= 0.5;
                    @endphp

                    <div class="mb-1 small text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $filledStars)
                                <i class="fas fa-star"></i>
                            @elseif ($halfStar && $i == $filledStars + 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="text-muted">({{ number_format($avgRating, 1) }})</span>
                    </div>
                    <p class="text-muted small">{{ $recent->reviews_count }} review{{ $recent->reviews_count !== 1 ? 's' : '' }}</p>

                    <div class="card-body">
                        <h6 class="fw-bold">{{ $recent->name }}</h6>
                        <span class="text-primary fw-bold">₹{{ number_format($recent->price, 2) }}</span>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex flex-column gap-2">
                        @if ($inCart)
                            <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-shopping-cart me-1"></i>Go to Cart
                            </a>
                        @elseif ($recent->quantity < 1)
                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                <i class="fas fa-ban me-1"></i>Out of Stock
                            </button>
                        @else
                            <a href="{{ url('addTocart', $recent->id) }}" class="btn btn-success btn-sm w-100 addToCart">
                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

    </div>
</div>

{{-- Styles --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: 0.3s ease;
    }

    .zoom-hover:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    .wishlist-btn:hover i {
        color: red !important;
    }

    .sticky-top {
        z-index: 1020;
    }
</style>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('.addToCart').click(function (e) {
            e.preventDefault();
            const button = $(this);
            if (button.hasClass('goToCart')) {
                window.location.href = "{{ route('cartView') }}";
                return;
            }

            const addUrl = button.attr('href');
            $.ajax({
                url: addUrl,
                type: 'GET',
                success: function () {
                    let secondsLeft = 5;
                    Swal.fire({
                        title: '🛒 Item Added!',
                        html: `Redirecting in <b id="countdown">${secondsLeft}</b>s. Click "Stay Here" to cancel.`,
                        icon: 'success',
                        showCancelButton: true,
                        cancelButtonText: 'Stay Here',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            const countdownEl = document.getElementById('countdown');
                            const interval = setInterval(() => {
                                secondsLeft--;
                                if (countdownEl) countdownEl.textContent = secondsLeft;
                                if (secondsLeft <= 0) {
                                    clearInterval(interval);
                                    Swal.close();
                                    window.location.href = "{{ route('cartView') }}";
                                }
                            }, 1000);
                        }
                    });

                    button
                        .removeClass('btn-success addToCart')
                        .addClass('btn-outline-info goToCart')
                        .html('<i class="fas fa-shopping-cart me-1"></i>Go to Cart')
                        .attr('href', "{{ route('cartView') }}");
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Please Login First',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    setTimeout(() => window.location.href = '/login', 2000);
                }
            });
        }); 
    });
    $(window).on('pageshow', function (e) {
            if (e.originalEvent.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                window.location.reload(true);
            }
        });

    $('.wishlist-btn').click(function (e) {
    e.preventDefault();
    const btn = $(this);
    const id = btn.data('id');
    const icon = btn.find('i');

    $.ajax({
        url: '{{ route("wishlist.toggle") }}',
        method: 'POST',
        data: {
            product_id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.status === 'added') {
                icon.removeClass('far').addClass('fas');
            } else if (response.status === 'removed') {
                icon.removeClass('fas').addClass('far');
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Please Login First',
                timer: 2000,
                showConfirmButton: false,
            });
            setTimeout(() => window.location.href = '/login', 2000);
        }
    });
});

</script>
@endsection
