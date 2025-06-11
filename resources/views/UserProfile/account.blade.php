
@extends('layouts.app')

{{-- @section('title', $product->name) --}}

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
        }
        .profile-card {
            border-radius: 15px;
            background: linear-gradient(135deg, #ffffff, #f1f3f6);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        .feature-btn {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 1rem;
            background-color: #fff;
            transition: 0.3s ease;
            height: 100%;
        }
        .feature-btn:hover {
            background-color: #f8f9fa;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #dee2e6;
            background-color: #fff;
            z-index: 1030;
        }
        .bottom-nav a {
            color: #333;
            text-decoration: none;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.85rem;
        }
        .badge-cart {
            position: absolute;
            top: 2px;
            right: 20px;
            font-size: 0.7rem;
        }
    </style>

<div class="container py-4 mb-5">
    <!-- Profile Section -->
    <div class="profile-card p-3 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">{{ $user->name ?? 'Guest' }}</h5>
            <small class="text-muted fw-semibold">✨ {{ $membership['level'] }}</small><br>
            <small class="text-muted">valid till {{ $membership['valid_till'] }}</small>
        </div>
        <div class="text-end">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">⚡ {{ $membership['points'] }}</span>
        </div>
    </div>


    <!-- Buttons -->
    <div class="row g-3 text-center mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('payments.index') }}" class="feature-btn h-100 d-block text-decoration-none text-dark">
                📦<br><strong>Orders</strong>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('wishlist.index') }}" class="feature-btn h-100 d-block text-decoration-none text-dark">
                ❤️<br><strong>Wishlist</strong>
            </a>
        </div>
        <div class="col-6 col-md-3">
            {{-- {{ route('coupons.index') }} --}}
            <a href="#" class="feature-btn h-100 d-block text-decoration-none text-dark">
                🎟️<br><strong>Coupons</strong>
            </a>
        </div>
        <div class="col-6 col-md-3">
            {{-- {{ route('help.index') }} --}}
            <a href="#" class="feature-btn h-100 d-block text-decoration-none text-dark">
                🎧<br><strong>Help Center</strong>
            </a>
        </div>
    </div>


    <!-- Finance Options -->
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-title">Finance Options</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <a href="#" class="text-decoration-none d-flex justify-content-between align-items-start">
                        <div>
                            <strong>Dls Personal Loan</strong><br>
                            <small class="text-muted">Pre-approved loan up to ₹100,000</small>
                        </div>
                        ➡️
                    </a>
                </li>
                <li>
                    <a href="#" class="text-decoration-none d-flex justify-content-between align-items-start">
                        <div>
                            <strong>Dls Axis Bank Credit Card</strong><br>
                            <small class="text-muted">Get ₹1,000 + ₹500 Gift Voucher, Offer Ending Soon!</small>
                        </div>
                        ➡️
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Credit Score -->
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-title">Credit Score</h6>
            <a href="#" class="text-decoration-none d-flex justify-content-between align-items-start">
                <div>
                    <strong>Free credit score check</strong><br>
                    <small class="text-muted">Get detailed credit report instantly.</small>
                </div>
                ➡️
            </a>
        </div>
    </div>

    <!-- Notifications -->
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Notifications</h6>
            <a href="{{route('notificationView')}}" class="text-decoration-none d-flex justify-content-between align-items-center">
                <div>
                    🔔 Tap for latest updates and offers
                </div>
                ➡️
            </a>
        </div>
    </div>
    @if($recentViews->count())
            <div class="mt-5">
                <h5 class="fw-semibold mb-3">Recently Viewed</h5>

                <div class="position-relative">
                    <!-- Left Scroll Button -->
                    <button class="scroll-btn left position-absolute top-50 start-0 translate-middle-y btn btn-light shadow-sm" style="z-index: 10;" onclick="scrollRecently(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <!-- Scrollable Container -->
                    <div id="recent-scroll" class="d-flex overflow-auto px-2 py-1" style="gap: 10px; scroll-behavior: smooth;">
                        @foreach($recentViews as $recent)
                            <a href="{{ url('detail', $recent->id) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm" style="min-width: 140px; max-width: 140px; font-size: 0.85rem;">
                                    <button class="btn btn-light position-absolute top-0 end-0 m-1 wishlist-btn p-1" data-id="{{ $recent->id }}">
                                <i class="{{ in_array($recent->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                            </button>
                                    <img src="{{ asset($recent->image ?? $recent->images->first()->image_path ?? 'placeholder.jpg') }}"
                                        alt="{{ $recent->name }}"
                                        class="card-img-top" style="height: 100px; object-fit: contain;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title text-truncate mb-1" title="{{ $recent->name }}">{{ $recent->name }}</h6>
                                        <p class="text-success fw-bold mb-1">₹{{ number_format($recent->price, 0) }}</p>

                                        @if($recent->averageRating > 0)
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $avgRating = round($recent->averageRating, 1);
                                                    $fullStars = floor($avgRating);
                                                    $hasHalfStar = ($avgRating - $fullStars) >= 0.25 && ($avgRating - $fullStars) < 0.75;
                                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                                @endphp

                                                {{-- Full Stars --}}
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="fa fa-star text-success"></i>
                                                @endfor

                                                {{-- Half Star --}}
                                                @if ($hasHalfStar)
                                                    <i class="fa fa-star-half-alt text-success"></i>
                                                @endif

                                                {{-- Empty Stars --}}
                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <i class="far fa-star text-muted"></i>
                                                @endfor

                                                <small class="ms-1 text-muted">({{ number_format($recent->averageRating, 1) }})</small>
                                            </div>
                                        @else
                                            <small class="text-muted">No ratings yet</small>
                                        @endif

                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Right Scroll Button -->
                    <button class="scroll-btn right position-absolute top-50 end-0 translate-middle-y btn btn-light shadow-sm" style="z-index: 10;" onclick="scrollRecently(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        @endif
</div>
<script>
    function scrollRecently(direction) {
        const container = document.getElementById('recent-scroll');
        const scrollAmount = 200;
        container.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }

</script>
@endsection
