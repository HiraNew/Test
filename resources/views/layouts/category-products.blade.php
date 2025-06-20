@extends('layouts.app')

@section('content')

 @include('components.sidebar')
<style>
    /* Dropdown */
 .subcategory-dropdown {
        display: none;
        position: absolute;
        top: 90%;
        left: 0;
        z-index: 1000;
        min-width: 150px;
    }

    /* Show on hover (non-touch devices only) */
    @media (hover: hover) and (pointer: fine) {
        .category-item:hover .subcategory-dropdown {
            display: block;
        }

        .dropdown-toggle-btn {
            display: none !important;
        }
    }

    /* For touch devices: only show when active class is toggled */
    .category-item.show-submenu .subcategory-dropdown {
        display: block;
    }

    .dropdown-toggle-btn {
        cursor: pointer;
        font-size: 14px;
        color: #333;
        user-select: none;
    }

    .dropdown-arrow {
        display: inline-block;
        transition: transform 0.3s;
    }

    .category-item.show-submenu .dropdown-arrow {
        transform: rotate(180deg);
    }
    .wishlist-btn {
        width: 34px;
        height: 34px;
        background-color: white;
        color: #000;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .wishlist-btn.wishlist-active {
        background-color: #dc3545;
        color: white;
    }

</style>
<div class="container">
    <div class="row justify-content-center mb-4 pb-2">
        <div class="col-12 col-xl-11 px-0">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 px-3 py-3 bg-white rounded shadow-sm">
                @foreach ($categoriesList as $category)
                    <div class="col category-item text-center position-relative">
                        <div class="d-flex flex-column align-items-center">
                            <a href="{{ route('category.view', $category->slug) }}"
                            class="text-decoration-none">
                            
                                <img src="{{ asset($category->icon) }}"
                                    alt="{{ $category->name }}"
                                    class="rounded-circle img-fluid mb-1"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            </a>
                            <div class="small mt-1 {{ strtolower($category->name) == 'fashion' ? 'text-primary' : '' }}">
                                <small class="mt-2 text-truncate fw-bold" style="max-width: 70px;">
                                    {{ $category->name }}
                                </small>
                            </div>
                        </div>

                        @if ($category->subcategories->count())
                            <!-- Mobile dropdown toggle -->
                            <div class="dropdown-toggle-btn d-md-none mt-1" onclick="toggleSubDropdown(this)">
                                <span class="dropdown-arrow">&#x25BC;</span>
                            </div>

                            <!-- Subcategories dropdown -->
                            <div class="subcategory-dropdown bg-white border rounded shadow-sm mt-1">
                                @foreach ($category->subcategories as $sub)
                                    <a href="{{ route('category.view', $sub->slug) }}"
                                    class="dropdown-item text-decoration-none text-dark d-block px-3 py-2">
                                        {{ $sub->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
 </div>

    {{-- <h4 class="fw-bold mb-3">{{ $categories->name }}</h4> --}}
    
    <div class="container my-4">
    <h3 class="mb-4">
        @if($categories)
            Products in Category: <span class="text-primary">{{ $categories->name }}</span>
        @elseif($subcategory)
            Products in Subcategory: <span class="text-primary">{{ $subcategory->name }}</span>
        @else
            Products
        @endif
    </h3>

    @if ($products->count() > 0)
    <div class="row">
        @foreach ($products as $product)
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4 product-card" data-price="{{ $product->price }}">
                <div class="card h-100 shadow-sm position-relative">
                    @php
                        $isWishlisted = in_array($product->id, $wishlistProductIds);
                    @endphp
                    <button type="button"
                        class="wishlist-btn position-absolute top-0 end-0 m-2 btn btn-sm rounded-circle border border-light shadow"
                        data-id="{{ $product->id }}"
                        title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                        style="z-index: 10; background-color: white; color: red;">
                        <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
                    </button>


                    <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}"
                    style="height: 180px; object-fit: cover; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}">


                    {{-- <img src="{{ url($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}"> --}}
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                        
                        <div class="mb-2">
                            <span class="text-primary fs-5 fw-bold">₹{{ number_format($product->price, 2) }}</span>
                        </div>

                        {{-- Rating --}}
                        <div class="mb-2">
                            @php
                                $avgRating = round($product->reviews_avg_rating ?? 0, 1);
                                $reviewCount = $product->reviews_count;
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <i class="bi bi-star-fill text-warning"></i>
                                @elseif ($i - $avgRating < 1)
                                    <i class="bi bi-star-half text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                            <small class="text-muted">({{ $reviewCount }})</small>
                        </div>

                        {{-- Add to Cart & Wishlist Buttons --}}
                        <div class="mt-auto d-flex flex-column gap-2">

                            {{-- Cart Button --}}
                            @php
                                $isInCart = in_array($product->id, $cartProductIds);
                            @endphp
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

                            {{-- Product Details --}}
                            <a href="{{ url('detail', $product->id) }}" class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-info-circle me-1"></i> Details
                            </a>
                        </div>

                    </div>
                </div>
            </div>



            <!-- Product Quick View Modal -->
         <div class="modal fade" id="quickViewModal{{ $product->id }}" tabindex="-1" aria-labelledby="quickViewModalLabel{{ $product->id }}" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
           <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body row">
                <div class="col-md-6">
                    <img src="{{asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded w-100">
                </div>

                <div class="col-md-6">
                    <h4 class="text-primary mb-2">₹{{ number_format($product->price, 2) }}</h4>

                    {{-- Average rating --}}
                    @php
                        $avgRating = round($product->reviews_avg_rating ?? 0, 1);
                        $filledStars = floor($avgRating);
                        $halfStar = ($avgRating - $filledStars) >= 0.5;
                    @endphp

                    <div class="mb-2 text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $filledStars)
                                <i class="fas fa-star"></i>
                            @elseif ($halfStar && $i == $filledStars + 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="ms-2 text-muted">({{ $product->reviews_count ?? 0 }})</span>
                    </div>

                    <p class="mb-3">{{ $product->ldescription ?? 'No description available.' }}</p>

                    <div class="d-grid gap-2">
                        @if (in_array($product->id, $cartProductIds))
                            <a href="{{ route('cartView') }}" class="btn btn-outline-info">
                                <i class="fas fa-shopping-cart me-1"></i> Go To Cart
                            </a>
                        @elseif ($product->quantity < 1)
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-ban me-1"></i> Out of Stock
                            </button>
                        @else
                            <a href="{{ url('addTocart', $product->id) }}" class="btn btn-success addToCart">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </a>
                        @endif

                        <a href="{{ url('detail', $product->id) }}" class="btn btn-warning">
                            <i class="fas fa-info-circle me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>

    @else
        <p class="text-muted fs-5">No products found in this category.</p>
    @endif
</div>

<!-- Bootstrap Icons CDN (if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    // subcategories
document.querySelectorAll('.position-relative').forEach(function(categoryDiv) {
        categoryDiv.addEventListener('mouseenter', function() {
            let dropdown = this.querySelector('.subcategory-dropdown');
            if (dropdown) dropdown.style.display = 'block';
        });
        categoryDiv.addEventListener('mouseleave', function() {
            let dropdown = this.querySelector('.subcategory-dropdown');
            if (dropdown) dropdown.style.display = 'none';
        });
    });
     function toggleSubDropdown(btn) {
        const item = btn.closest('.category-item');
        item.classList.toggle('show-submenu');
    }

    // Close dropdowns on outside click (for touch devices)
    document.addEventListener('click', function (event) {
        const isToggleBtn = event.target.closest('.dropdown-toggle-btn');
        const isInsideDropdown = event.target.closest('.category-item');

        if (!isToggleBtn && !isInsideDropdown) {
            document.querySelectorAll('.category-item.show-submenu')
                .forEach(el => el.classList.remove('show-submenu'));
        }
    });

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
       const btn = $(this);
       const productId = btn.data('id');
   
       $.ajax({
           url: '{{ route("wishlist.toggle") }}',
           method: 'POST',
           data: {
               product_id: productId,
               _token: '{{ csrf_token() }}'
           },
           success: function (response) {
               const allBtns = $(`.wishlist-btn[data-id="${productId}"]`);
   
               if (response.status === 'added') {
                   allBtns.each(function () {
                       $(this)
                            .addClass('wishlist-active')
                            .find('i')
                            .removeClass('far')
                            .addClass('fas');

                   });
               } else if (response.status === 'removed') {
                   allBtns.each(function () {
                       $(this)
                            .removeClass('wishlist-active')
                            .find('i')
                            .removeClass('fas')
                            .addClass('far');

                   });
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
