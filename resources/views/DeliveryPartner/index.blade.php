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

        @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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
                                {{-- <th>Product</th> --}}
                                {{-- <th>Qty</th> --}}
                                <th>Customer</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assigned as $order)
                            <tr>
                                <td>{{ $order->orderid }}</td>
                                {{-- <td>{{ $order->product->name }}</td> --}}
                                {{-- <td>{{ $order->qty }}</td> --}}
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->address->address }},{{$order->address->mobile_number}}, {{$order->address->alt_mobile_number}}, {{$order->address->pincode}} , {{$order->address->postal_code}}, {{$order->address->landmark}}</td>
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
                                <th>Product</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->orderid }}</td>
                                <td>{{ $order->product->name }}</td>
                                <td>
                                    @if($order->status === 'delivered')
                                        <span class="badge badge-status badge-delivered">Delivered</span>
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
                        <input type="text" name="order_id" id="order_id" class="form-control" maxlength="10" required>
                        <div class="text-end mt-1">
                            <button type="button" id="sendOtpBtn" class="btn btn-sm btn-outline-primary" disabled>Send OTP</button>
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
                alert(data.message);
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
</script>

@endsection
