@extends('layouts.app')

@section('content')

{{-- SweetAlert2 CSS --}}
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
        {{-- Sidebar (collapsible on small screens) --}}
        <div class="col-lg-3 mb-4">
            <div class="d-lg-block d-none">
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
                @if (isset($Products) && count($Products) > 0)
                    @foreach ($Products as $Product)
                        @php
                            $inCart = isset($cartProductIds) && in_array($Product->id, $cartProductIds);
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
                                    <p class="card-text small text-muted flex-grow-1">{{ $Product->sdescription ?? 'No description available.' }}</p>
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
                    

                @else
                  @if ($productTableIsEmpty)
                        <div class="alert alert-danger text-center my-4">
                            No products available at the moment.
                        </div>
                    @elseif ($fallbackShown)
                        <div class="alert alert-warning text-center my-4">
                            No products matched your search for "<strong>{{ $query }}</strong>".<br>
                            But here’s what we currently have available:
                        </div>
                    @elseif ($query)
                        <div class="alert alert-info text-center my-4">
                            Showing results for "<strong>{{ $query }}</strong>".
                        </div>
                    @endif



                @endif
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: 0.3s ease;
    }

    .card-title:hover {
        color: #0d6efd;
    }

    .product-image {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .btn-sm {
        font-size: 0.875rem;
    }
</style>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
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
                    let timerInterval;
                    Swal.fire({
                        title: '🛒 Item Added!',
                        html: `
                            <p>You’ll be redirected to your cart in <b id="countdown">${secondsLeft}</b> seconds.</p>
                            <p class="small text-muted">Click "Stay Here" to cancel redirection.</p>
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        cancelButtonText: 'Stay Here',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            const countdownEl = document.getElementById('countdown');
                            timerInterval = setInterval(() => {
                                secondsLeft--;
                                if (countdownEl) countdownEl.textContent = secondsLeft;
                                if (secondsLeft <= 0) {
                                    clearInterval(timerInterval);
                                    Swal.close();
                                    window.location.href = "{{ route('cartView') }}";
                                }
                            }, 1000);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
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
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            });
        });
    });
    // for  refresh page each time code to detect actual conent each time
    $(window).on('pageshow', function (event) {
        if (event.originalEvent.persisted || window.performance.navigation.type === 2) {
            // Page was restored from back/forward cache or accessed via back button
            location.reload();
        }
    });
    
</script>

@endsection
