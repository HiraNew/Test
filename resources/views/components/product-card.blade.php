@php
    $isWishlisted = in_array($product->id, $wishlistProductIds);
    $isInCart = in_array($product->id, $cartProductIds);
    $avgRating = round($product->reviews_avg_rating ?? 0, 1);
    $filledStars = floor($avgRating);
    $halfStar = ($avgRating - $filledStars) >= 0.5;
@endphp

<div class="col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm position-relative">
        {{-- Wishlist --}}
        <button type="button"
                class="wishlist-btn position-absolute top-0 end-0 m-2 btn btn-sm rounded-circle border-0"
                data-id="{{ $product->id }}"
                style="z-index: 10; background-color: {{ $isWishlisted ? '#dc3545' : 'transparent' }}; color: {{ $isWishlisted ? '#fff' : '#000' }};">
            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
        </button>

        {{-- Image --}}
        <img src="{{ url($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}">

        {{-- Body --}}
        <div class="card-body d-flex flex-column">
            <h6 class="fw-bold text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
            <div class="mb-2 text-primary fw-bold">₹{{ number_format($product->price, 2) }}</div>

            {{-- Rating --}}
            <div class="mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $filledStars)
                        <i class="bi bi-star-fill text-warning"></i>
                    @elseif ($halfStar && $i == $filledStars + 1)
                        <i class="bi bi-star-half text-warning"></i>
                    @else
                        <i class="bi bi-star text-warning"></i>
                    @endif
                @endfor
                <small class="text-muted">({{ $product->reviews_count }})</small>
            </div>

            {{-- Actions --}}
            <div class="mt-auto d-flex flex-column gap-2">
                @if ($isInCart)
                    <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-shopping-cart me-1"></i> Go To Cart
                    </a>
                @elseif ($product->quantity < 1)
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="fas fa-ban me-1"></i> Out of Stock
                    </button>
                @else
                    <a href="{{ url('addTocart', $product->id) }}" class="btn btn-success btn-sm w-100 addToCart">
                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </a>
                @endif

                <a href="{{ url('detail', $product->id) }}" class="btn btn-warning btn-sm w-100">
                    <i class="fas fa-info-circle me-1"></i> Details
                </a>
            </div>
        </div>
    </div>

    {{-- Include modal here if needed --}}
    @include('components.product-modal', [
        'product' => $product,
        'wishlistProductIds' => $wishlistProductIds,
        'cartProductIds' => $cartProductIds
    ])
</div>
