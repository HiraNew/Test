@extends('layouts.app')

@section('content')
<style>
    @media (max-width: 768px) {
        .wishlist-card img {
            width: 100px !important;
            height: 100px !important;
        }
        .wishlist-card .btn {
            font-size: 13px;
            padding: 6px 10px;
        }
    }
</style>

<div class="container py-5">
    <h2 class="mb-4 text-center font-weight-bold">My Wishlist</h2>

    <div class="row">
    @forelse($wishlists as $item)
        @if($item->product)
            @php
                $product = $item->product;
                $isInCart = in_array($product->id, $cartProductIds);
            @endphp

            <div class="col-md-4 col-12 mb-4">
                <!-- Desktop View -->
                <div class="card shadow-sm position-relative d-none d-md-block h-100">
                    <img src="{{ url($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 p-2">
                        <i class="fas fa-heart text-danger fs-4"></i>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                        <p class="card-text text-muted mb-2">₹{{ number_format($product->price, 2) }}</p>

                        @if ($isInCart)
                            <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Go To Cart
                            </a>
                        @elseif ($product->quantity < 1)
                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                <i class="fas fa-ban me-1"></i> Out of Stock
                            </button>
                        @else
                            <a href="{{ route('addCart', $product->id) }}" class="btn btn-success btn-sm addToCart" data-product-id="{{ $product->id }}">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </a>

                        @endif

                        <a href="{{ url('detail', $product->id) }}" class="btn btn-warning btn-sm w-100 mt-2">
                            <i class="fas fa-info-circle me-1"></i> View Details
                        </a>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="card shadow-sm d-flex d-md-none flex-row align-items-center p-2">
                    <img src="{{ url($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded me-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                        <p class="text-muted mb-2">₹{{ number_format($product->price, 2) }}</p>

                        <div class="d-flex flex-row flex-wrap align-items-center gap-2 mt-2">
                            <a href="{{ url('detail', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-info-circle me-1"></i> View Details
                            </a>

                            @if ($isInCart)
                                <a href="{{ route('cartView') }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-shopping-cart me-1"></i> Go To Cart
                                </a>
                            @elseif ($product->quantity < 1)
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="fas fa-ban me-1"></i> Out of Stock
                                </button>
                            @else
                                <a href="{{ route('addCart', $product->id) }}" 
                                class="btn btn-success btn-sm addToCart" 
                                data-product-id="{{ $product->id }}">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                </a>


                            @endif

                            <button class="btn btn-sm btn-outline-danger remove-wishlist" data-id="{{ $product->id }}">
                                <i class="fas fa-trash-alt me-1"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Your wishlist is empty.
            </div>
        </div>
    @endforelse
</div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.remove-wishlist').forEach(button => {
    button.addEventListener('click', function () {
        const productId = this.dataset.id;
        const card = this.closest('.col-md-4');

        Swal.fire({
            title: 'Are you sure?',
            text: "Remove from wishlist?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('wishlist.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'removed') {
                        Swal.fire('Removed!', 'Product removed from wishlist.', 'success');
                        if (card) card.remove();

                        if (document.querySelectorAll('.remove-wishlist').length === 0) {
                            document.querySelector('.row').innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        Your wishlist is empty.
                                    </div>
                                </div>`;
                        }
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            }
        });
    });
});


$(function () {
    $('.addToCart').click(function (e) {
        e.preventDefault();

        const button = $(this);
        const productId = button.data('product-id');
        const addUrl = button.attr('href');

        $.ajax({
            url: addUrl,
            type: 'GET',
            success: function () {
                let secondsLeft = 5;
                let countdownInterval;
                let redirectCanceled = false;

                Swal.fire({
                    title: '🛒 Item Added!',
                    html: `Redirecting in <b id="countdown">${secondsLeft}</b>s.<br>Click "Stay Here" to cancel.`,
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

                        // Update cart count badges
                        ['#cart-count', '#cart-count-mobile'].forEach(selector => {
                            const badge = $(selector);
                            if (badge.length) {
                                let count = parseInt(badge.text()) || 0;
                                badge.text(count + 1);
                            }
                        });
                    }
                });

                // Update all add-to-cart buttons for this product ID to "Go to Cart"
                $(`.addToCart[data-product-id="${productId}"]`).each(function () {
                    $(this)
                        .removeClass('btn-success addToCart')
                        .addClass('btn-outline-info goToCart')
                        .html('<i class="fas fa-shopping-cart me-1"></i> Go to Cart')
                        .attr('href', "{{ route('cartView') }}");
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Please login first',
                    timer: 2000,
                    showConfirmButton: false,
                });
                setTimeout(() => window.location.href = '/login', 2000);
            }
        });
    });
});


</script>

@endsection
