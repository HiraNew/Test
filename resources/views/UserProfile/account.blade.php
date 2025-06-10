
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
</div>
@endsection
