@extends('layouts.delivery')

@section('title', 'Partner')

@push('styles')
<style>
    body {
        background: #f3f4f6;
        font-family: 'Inter', sans-serif;
        padding-top: 30px;
    }

    .dashboard-card {
        border-radius: 15px;
        padding: 20px;
        background: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .tab-content {
        margin-top: 20px;
    }

    .badge-status {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 12px;
    }

    .badge-delivered {
        background: #28a745;
        color: white;
    }

    .badge-pending {
        background: #ffc107;
        color: black;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="dashboard-card">
        <h4 class="mb-3 text-center">Welcome, {{ Auth::guard('partner')->user()->name }}</h4>
        <form method="POST" action="{{ route('partner.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        <!-- Placeholder for alert -->
        <div id="otpAlertContainer"></div>


        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Tabs -->
        <ul class="nav nav-tabs" id="partnerTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="otp-tab" data-bs-toggle="tab" data-bs-target="#otp" type="button" role="tab">Verify with OTP</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="assigned-tab" data-bs-toggle="tab" data-bs-target="#assigned" type="button" role="tab">Assigned Products</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">Delivery Status</button>
            </li>
            
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="partnerTabContent">
            <!-- Assigned Products -->
            <div class="tab-pane fade" id="assigned" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                {{-- <th>Customer</th> --}}
                                <th>Address</th>
                                {{-- <th>Item Count</th> --}}
                                {{-- <th>Products</th> --}}
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($assigned as $orderid => $items)
                                @php
                                    $first = $items->first();
                                    $productNames = $items->pluck('product.name')->unique()->implode(', ');
                                    $itemCount = $items->count();
                                    // $link = " Please visit : 127.0.0.1:8000";
                                    // $whatsappNumber = preg_replace('/\D/', '', $first->address->mobile_number); // remove non-digits
                                    // $waLink = "https://wa.me/91{$whatsappNumber}?text=" . urlencode("Hi, I am your delivery partner from DLS, Your Item with orderid ".$orderid." Will be delivered today by 8:00 pm ". " Please share your current location ". $link);

                                    $locationLink = route('location.form', ['orderid' => $orderid]);
                                    $message = "*Hi, I am your delivery partner from DLS*.\n\n*Please click the link below to share your location*:\n\n$locationLink";
                                    $waLink = "https://wa.me/91{$first->address->mobile_number}?text=" . urlencode($message);
                                @endphp
                                <tr>
                                    <td>{{ $orderid }} , ({{ $first->qty }}), ({{$first->amount}})</td>
                                    {{-- <td>{{ $first->user->name }}</td> --}}
                                    <td>
                                        {{ $first->address->address }},
                                        {{ $first->address->mobile_number }},
                                        {{ $first->address->alt_mobile_number }},
                                        {{ $first->address->pincode ?? $first->address->postal_code }},
                                        {{-- {{ $first->address->postal_code }}, --}}
                                        {{ $first->address->landmark }}
                                        @if(!$first->feild5 && !$first->feild6)
                                        <a href="{{ $waLink }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="bi bi-whatsapp"></i> Ask for Location
                                        </a>
                                        @endif
                                        @if($first->feild5 && $first->feild6)
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $first->feild5 }},{{ $first->feild6 }}"
                                            target="_blank" class="btn btn-sm btn-outline-info">
                                            📍View Location
                                            </a>
                                        @endif
                                        {{-- <a href="{{ $waLink }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                                        </a> --}}
                                    </td>
                                    
                                    {{-- <td>{{ $itemCount }}</td> --}}
                                    {{-- <td>{{ $productNames }}</td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No assigned products.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Delivery Status -->
            <div class="tab-pane fade" id="status" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->orderid }}</td>
                                <td>{{ $order->qty }} , ({{$order->amount}})</td>
                                <td>
                                    @if($order->status === 'delivered')
                                        @php
                                             $deliveredAt = \Carbon\Carbon::parse($order->feild4, 'Asia/Kolkata');
                                        @endphp

                                        <span class="badge badge-status badge-delivered">Delivered</span>
                                        on {{ $deliveredAt->format('l, d-m-Y h:i A') }}
                                        <small class="text-muted">({{ $deliveredAt->diffForHumans() }})</small>

                                    @else
                                        <span class="badge badge-status badge-pending">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No delivery records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- OTP Verification -->
           <div class="tab-pane fade show active" id="otp" role="tabpanel">
            {{-- {{ route('partner.verify.otp') }} --}}
                <form method="POST" action="{{ route('partner.verify.otp') }}" id="otpForm">
                    @csrf
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Order ID</label>
                        <input type="text" name="order_id" id="order_id" class="form-control" value="{{ session('order_id', old('order_id')) }}" maxlength="10" required>
                        <div class="text-end mt-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" id="sendOtpBtn" class="btn btn-sm btn-outline-primary" disabled>Send OTP</button>
                                <span id="otpTimer" class="ms-2 text-muted small"></span>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="otp" class="form-label">Enter OTP from Customer</label>
                        <input type="text" name="otp" id="otpCheck" class="form-control" maxlength="6" pattern="\d{6}" required disabled>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success" id="submitOtpBtn" disabled>Verify & Mark Delivered</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const orderInput = document.getElementById('order_id');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const otpInput = document.getElementById('otpCheck');
    const submitBtn = document.getElementById('submitOtpBtn');
     const otpSentAt = @json(session('otp_sent_at')); // UNIX timestamp
    const otpTimerSpan = document.getElementById('otpTimer');

    const otpWasSent = @json(session('otp_sent', false));

    if (orderInput.value.length === 10) {
        sendOtpBtn.disabled = false;
    }

    if (otpWasSent) {
        otpInput.disabled = false;
        submitBtn.disabled = false;
    }

    orderInput.addEventListener('keyup', () => {
        if (orderInput.value.length === 10) {
            sendOtpBtn.disabled = false;
        } else {
            sendOtpBtn.disabled = true;
            otpInput.disabled = true;
            submitBtn.disabled = true;
        }
    });

    sendOtpBtn.addEventListener('click', () => {
        const orderId = orderInput.value;

        fetch("{{ route('partner.send.otp') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not OK');
            return res.json();
        })
        .then(data => {
            console.log('OTP Response:', data);
            if (data.success === true) {
                document.getElementById('otpAlertContainer').innerHTML = `
                    <div class="alert alert-info">OTP has been sent. Please enter it below.</div>
                `;
                otpInput.disabled = false;
                submitBtn.disabled = false;
            } else {
                alert(data.message || 'Failed to send OTP.');
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            alert('Something went wrong while sending the OTP.');
        });
    });



    function startOtpTimer(sentAtTimestamp) {
        const expireAfterSeconds = 10 * 60; // 10 minutes
        const endTime = sentAtTimestamp + expireAfterSeconds;

        const timer = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const remaining = endTime - now;

            if (remaining <= 0) {
                clearInterval(timer);
                otpTimerSpan.textContent = 'OTP expired';
                otpInput.disabled = true;
                submitBtn.disabled = true;
                return;
            }

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            otpTimerSpan.textContent = `OTP valid for ${minutes}:${seconds.toString().padStart(2, '0')} mins`;
        }, 1000);
    }

    if (otpWasSent && otpSentAt) {
        startOtpTimer(otpSentAt);
    }

    window.addEventListener("pageshow", function (event) {
        // If coming back from back/forward browser button, force reload
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>


@endsection
