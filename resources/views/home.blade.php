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

    {{-- Carousel --}}
    <div class="row justify-content-center mb-4"> {{-- Added mb-4 --}}
        <div class="col-12 col-xl-11">
            <div class="card shadow-sm border-0 p-4 rounded bg-white">
                <div id="dynamicCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        @foreach ($carouselItems as $index => $item)
                            <div class="carousel-item @if($index === 0) active @endif">
                                <img src="{{ url($item->image) }}" class="d-block w-100" alt="{{ $item->caption ?? 'Slide Image' }}" style="height: 300px; object-fit: cover;">
                                @if(!empty($item->caption))
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                        <h5 class="text-light">{{ $item->caption }}</h5>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#dynamicCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#dynamicCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Categories --}}
    <div class="row justify-content-center mt-3 mb-5"> {{-- Added mt-3 --}}
        <div class="col-auto">
            <div class="card shadow-sm border-0 p-3 bg-primary text-center">
                <h5 class="mb-3 fw-bold text-white">Look into Categories</h5>
                <div class="row row-cols-auto justify-content-center g-3">
                    @foreach($categories->take(7) as $category)
                    {{-- @dd($category->icon); --}}
                        <div class="col">
                            <a href="{{ route('category.view', $category->slug) }}" class="text-decoration-none text-dark d-flex flex-column align-items-center">
                                <div class="rounded-circle border bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                                    <img src="{{ url($category->icon) }}" alt="{{ $category->name }}" class="img-fluid" style="width: 28px; height: 28px; object-fit: contain;">
                                </div>
                                <small class="mt-2 text-truncate fw-bold" style="max-width: 70px;">{{ $category->name }}</small>
                            </a>
                        </div>
                    @endforeach

                    @if($categories->count() > 7)
                        <div class="col">
                            <a href="#" class="text-decoration-none text-dark d-flex flex-column align-items-center">
                                <div class="rounded-circle border bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-th-large fs-5 text-secondary"></i>
                                </div>
                                <small class="mt-2 fw-bold">All</small>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Product Grid --}}
    <div class="row justify-content-center my-4">
    <div class="col-12 col-lg-11">
        {{-- Outer Card wrapping all product cards --}}
        <div class="card shadow-sm border-0 p-4 bg-light">
            <h4 class="fw-bold mb-4 text-center">Our Products</h4>

            {{-- Responsive Product Grid Inside the Card --}}
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                @forelse ($Products as $Product)
                    @php $inCart = isset($cartProductIds) && in_array($Product->id, $cartProductIds); @endphp
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 hover-shadow position-relative" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $Product->id }}">
                            {{-- Product Image --}}
                            <div class="ratio ratio-4x3 overflow-hidden" role="button" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $Product->id }}">
                                <img src="{{ url($Product->image) }}" loading="lazy" class="img-fluid product-image zoom-hover" alt="{{ $Product->name }}">
                            </div>

                            {{-- Wishlist Icon --}}
                            <button class="btn btn-light position-absolute top-0 end-0 m-1 wishlist-btn p-1" data-id="{{ $Product->id }}">
                                <i class="{{ in_array($Product->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                            </button>

                            {{-- Card Body --}}
                            <div class="card-body py-2 px-3 d-flex flex-column">
                                <h6 class="card-title fw-semibold mb-1 text-dark text-truncate" title="{{ $Product->name }}">
                                    {{ $Product->name }}
                                </h6>

                                @php
                                    $avgRating = round($Product->reviews_avg_rating, 1);
                                    $filledStars = floor($avgRating);
                                    $halfStar = ($avgRating - $filledStars) >= 0.5;
                                @endphp

                                <div class="mb-1 small text-warning d-flex align-items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $filledStars)
                                            <i class="fas fa-star fa-xs"></i>
                                        @elseif ($halfStar && $i == $filledStars + 1)
                                            <i class="fas fa-star-half-alt fa-xs"></i>
                                        @else
                                            <i class="far fa-star fa-xs"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1 text-muted small">({{ number_format($avgRating, 1) }})</span>
                                </div>

                                <p class="card-text text-muted small mb-1 text-truncate" title="{{ $Product->sdescription }}">
                                    {{ $Product->sdescription ?? 'No description available.' }}
                                </p>

                                <span class="fw-bold text-primary small">₹{{ number_format($Product->price, 2) }}</span>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    @if ($Product->quantity < 1)
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-ban me-1"></i>Out of Stock
                                        </button>
                                    @else
                                        <button class="btn btn-success btn-sm w-100 addToCart">
                                           <i class="fas fa-cart-plus me-1"></i>In Stock
                                        </button>
                                    @endif
                                    {{-- <span class="badge bg-{{ $Product->quantity < 1 ? 'danger' : 'success' }} small">
                                        {{ $Product->quantity > 0 ? 'In Stock :' : 'Out of Stock :' }} {{$Product->quantity}}
                                    </span> --}}
                                    
                                </div>
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
                                    <div class="col-md-6 position-relative">
                                        <div class="ratio ratio-4x3 overflow-hidden rounded">
                                            <img src="{{ url($Product->image) }}" class="img-fluid w-100 h-100 object-fit-cover" alt="{{ $Product->name }}">
                                        </div>
                                        <button class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn p-1" data-id="{{ $Product->id }}" style="z-index: 10;">
                                            <i class="{{ in_array($Product->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                                        </button>
                                    </div>

                                    <div class="col-md-6">  
                                        <p>{{ $Product->ldescription ?? 'No full description.' }}</p>
                                        <div class="mb-1 small text-warning d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $filledStars)
                                                    <i class="fas fa-star fa-xs"></i>
                                                @elseif ($halfStar && $i == $filledStars + 1)
                                                    <i class="fas fa-star-half-alt fa-xs"></i>
                                                @else
                                                    <i class="far fa-star fa-xs"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1 text-muted small">({{ number_format($avgRating, 1) }})</span>
                                        </div>
                                        <h4 class="text-primary">₹{{ number_format($Product->price, 2) }}</h4>

                                        {{-- Card Footer Buttons --}}
                                        <div class="card-footer bg-white border-0 d-flex flex-column gap-1 p-2 pt-0">
                                            @if ($inCart)
                                                <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100">
                                                    <i class="fas fa-shopping-cart me-1"></i>Go To Cart
                                                </a>
                                            @elseif ($Product->quantity < 1)
                                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                                    <i class="fas fa-ban me-1"></i>Out of stock
                                                </button>
                                            @else
                                                <a href="{{ url('addTocart', $Product->id) }}" class="btn btn-success btn-sm w-100 addToCart">
                                                    <i class="fas fa-cart-plus me-1"></i>Add to cart
                                                </a>
                                            @endif

                                            <a href="{{ url('detail', $Product->id) }}" class="btn btn-warning btn-sm w-100">
                                                <i class="fas fa-info-circle me-1"></i>Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No products found.</p>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($Products instanceof \Illuminate\Pagination\LengthAwarePaginator && $Products->total() > 20)
                <div class="mt-4 d-flex justify-content-center">
                    {{ $Products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>


    {{-- Recently Viewed --}}
    @if($recentViews->isNotEmpty())
    <div class="row justify-content-center my-4">
        <div class="col-12 col-lg-11">
            <div class="card shadow-sm border-0 p-4 bg-light">
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
                                <div class="d-flex justify-content-between align-items-center mt-auto">
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
        </div>
    </div>
    @endif
</div>


{{-- Styles --}}
<style>
    @media (max-width: 576px) {
            .carousel-inner img {
                height: 180px !important;
            }
        }

        .rounded-circle img {
            transition: transform 0.2s ease;
        }

        .rounded-circle:hover img {
            transform: scale(1.1);
        }
    .object-fit-cover {
    object-fit: cover;
    }

    @media (max-width: 576px) {
        .modal .btn.wishlist-btn {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .modal .btn.wishlist-btn i {
            font-size: 16px;
        }
    }

    /* Carousel Start */
    #dynamicCarousel {
        width: 100%;
        max-height: 300px; /* Control max height */
        overflow: hidden;
    }

    #dynamicCarousel .carousel-item {
        height: 100%;
        min-height: 200px; /* Better for mobile screens */
        background-position: center;
        background-size: cover;
    }

    #dynamicCarousel .carousel-inner {
        width: 100%;
        height: 100%;
    }

    #dynamicCarousel .carousel-item img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        display: block;
    }


    /* carousel end */
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
    .card-title, .card-text {
        line-height: 1.2;
    }

    .card .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .card-body {
        padding: 0.75rem 1rem;
    }

    .card-footer {
        padding: 0.5rem 1rem;
    }

</style>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('.addToCart').click(function (e) {
    e.preventDefault();
    const button = $(this);
    const productId = button.attr('href').split('/').pop(); // Extract ID from URL


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
            let countdownInterval;
            let redirectCanceled = false;

            const productId = addUrl.split('/').pop();

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
                    countdownInterval = setInterval(() => {
                        secondsLeft--;
                        if (countdownEl) countdownEl.textContent = secondsLeft;

                        if (secondsLeft <= 0) {
                            clearInterval(countdownInterval);
                            if (!redirectCanceled) {
                                Swal.close();
                                window.location.href = "{{ route('cartView') }}";
                            }
                        }
                    }, 1000);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    redirectCanceled = true;
                    clearInterval(countdownInterval);

                    // Update all visible cart counters
                    ['#cart-count', '#cart-count-mobile'].forEach(selector => {
                        let badge = $(selector);
                        if (badge.length) {
                            let count = parseInt(badge.text()) || 0;
                            badge.text(count + 1);
                        }
                    });
                }
            });

            // Update all add-to-cart buttons for this product ID
            $(`.addToCart[href$="/${productId}"]`).each(function () {
                $(this)
                    .removeClass('btn-success addToCart')
                    .addClass('btn-outline-info goToCart')
                    .html('<i class="fas fa-shopping-cart me-1"></i>Go to Cart')
                    .attr('href', "{{ route('cartView') }}");
            });
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

    const clickedBtn = $(this);
    const productId = clickedBtn.data('id');

    $.ajax({
        url: '{{ route("wishlist.toggle") }}',
        method: 'POST',
        data: {
            product_id: productId,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            // Find all wishlist buttons with this product ID
            const allBtns = $(`.wishlist-btn[data-id="${productId}"]`);
            
            if (response.status === 'added') {
                allBtns.find('i').removeClass('far').addClass('fas');
            } else if (response.status === 'removed') {
                allBtns.find('i').removeClass('fas').addClass('far');
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
