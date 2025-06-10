@extends('layouts.app')

@section('content')
<style>
    /* Container for tracker */
    .tracker {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 30px 0;
        padding: 0 15px;
        flex-wrap: nowrap; /* keep in one line */
        overflow-x: auto; /* allow horizontal scroll on small */
        -webkit-overflow-scrolling: touch; /* smooth scrolling on iOS */
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .tracker::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .tracker {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Line connecting steps */
    .tracker::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 40px; /* shift from left to avoid overlap with circle */
        right: 40px; /* shift from right */
        height: 4px;
        background: #e9ecef;
        transform: translateY(-50%);
        z-index: 0;
    }

    /* Each step */
    .tracker-step {
        position: relative;
        z-index: 1;
        background: #e9ecef;
        color: #6c757d;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        font-weight: 600;
        user-select: none;
        cursor: default;
        flex-shrink: 0;
        margin-right: 60px; /* space between steps */
        transition: background-color 0.3s, color 0.3s;
    }

    /* Remove margin-right on last step */
    .tracker-step:last-child {
        margin-right: 0;
    }

    /* Completed step */
    .tracker-step.completed {
        background: #198754;
        color: white;
    }

    /* Current step */
    .tracker-step.current {
        background: #ffc107;
        color: black;
        font-weight: 700;
        box-shadow: 0 0 10px #ffc107aa;
    }

    /* Step label text */
    .tracker-label {
        margin-top: 8px;
        font-size: 0.8rem;
        max-width: 70px;
        word-wrap: break-word;
        white-space: normal;
    }

</style>

<div class="container my-5">
    <div class="row gap-4">
        <div class="col-12 col-lg-8">
            <div class="card p-4 shadow-sm">
                <div class="row">
                    @if($payment->product->image)
                        <img src="{{ url($payment->product->image) }}" 
                            alt="{{ $payment->product->name }}" 
                            class="img-fluid h-100 w-100" 
                            style="object-fit: contain;">
                    @else
                        <img src="{{ asset('images/default-product.png') }}" 
                            alt="No Image" 
                            class="img-fluid h-100 w-100" 
                            style="object-fit: contain;">
                    @endif
                    <div class="col-12 col-lg-8">
                        
                        <h2>Order Details - #{{ $payment->product->name }}</h2>

                        <p><strong>Amount:</strong> ₹{{ number_format($payment->amount, 2) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="@if($payment->status == 'delivered') text-success
                                        @elseif($payment->status == 'cancelled') text-danger
                                        @else text-warning
                                        @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </p>
                        <p><strong>Order Date :</strong> {{ $payment->created_at->format('d M Y, h:i A') }}</p>
                        <p><strong>Mode of payment : </strong> {{ $payment->payment_mode ? 'Cash on delivery' : 'Online Payment' }}</p>
                        <p><strong>Order Id : </strong> {{ $payment->orderid }}</p>
                        @if ($payment->status === 'shipped')
                            <span class="btn btn-success mb-3">Contact Agent: {{ $payment->agent ?? 'Wait For Delivery Person.' }}</span>
                        @endif

                        {{-- Shipping Address --}}
                        @if($payment->address)
                            <hr>
                            <h4>Shipping Address</h4>
                            <p>
                                {{ $payment->address->address_line }}<br>
                                {{ $payment->address->city }}, {{ $payment->address->state }} - {{ $payment->address->postal_code }}<br>
                                {{ $payment->address->country }}
                            </p>
                        @endif

                        <hr>

                        {{-- Horizontal order tracking progress bar --}}
                        <h4>Status Tracking</h4>
                        @php
                            $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
                            $currentIndex = array_search($payment->status, $statuses);
                        @endphp

                        <div class="tracker">
                            @foreach($statuses as $index => $status)
                                @php
                                    $class = '';
                                    if ($index < $currentIndex) {
                                        $class = 'completed';
                                    } elseif ($index == $currentIndex) {
                                        $class = 'current';
                                    }
                                @endphp
                                <div class="text-center">
                                    <div class="tracker-step {{ $class }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="tracker-label">{{ ucfirst($status) }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Action buttons --}}
                        @if($payment->status !== 'delivered' && $payment->status !== 'cancelled')
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal">Edit Address</button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order</button>
                        </div>
                        @endif
                        @if($payment->status === 'delivered' || $payment->status === 'cancelled')
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('detail', $payment->product->id) }}">
                                <button class="btn btn-primary">Order Again?</button>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">
            <div class="card p-4 shadow-sm">
                <h4>Recent Orders</h4>
                <ul class="list-group">
                    @forelse($recentPayments as $recent)
                        <li class="list-group-item">
                            <a href="{{ route('payments.show', $recent->id) }}">
                                Order #{{ $recent->product->name }} - ₹{{ number_format($recent->amount, 2) }}<br>
                                <small class="text-muted">{{ $recent->created_at->format('d M Y') }}</small>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item">No recent orders.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Edit Address Modal --}}
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    {{-- {{ route('payments.updateAddress', $payment->id) }} --}}
    <form action="#" method="POST" class="modal-content">
        @csrf
        @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title" id="editAddressModalLabel">Edit Shipping Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label for="address_line_modal" class="form-label">Address Line</label>
            <input type="text" class="form-control" id="address_line_modal" name="address_line" value="{{ old('address_line', $payment->address->address_line ?? '') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="city_modal" class="form-label">City</label>
                <input type="text" class="form-control" id="city_modal" name="city" value="{{ old('city', $payment->address->city ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="state_modal" class="form-label">State</label>
                <input type="text" class="form-control" id="state_modal" name="state" value="{{ old('state', $payment->address->state ?? '') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="postal_code_modal" class="form-label">Postal Code</label>
                <input type="text" class="form-control" id="postal_code_modal" name="postal_code" value="{{ old('postal_code', $payment->address->postal_code ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="country_modal" class="form-label">Country</label>
                <input type="text" class="form-control" id="country_modal" name="country" value="{{ old('country', $payment->address->country ?? '') }}" required>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Address</button>
      </div>
    </form>
  </div>
</div>

{{-- Cancel Order Modal --}}
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    {{-- {{ route('payments.cancel', $payment->id) }} --}}
    <form action="#" method="POST" class="modal-content" onsubmit="return confirm('Are you sure you want to cancel this order?');">
        @csrf
        @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order - Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <p>Please select a reason for cancellation:</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="cancel_reason" id="reason1" value="Found a better price elsewhere" required>
          <label class="form-check-label" for="reason1">
            Found a better price elsewhere
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="cancel_reason" id="reason2" value="Ordered by mistake" required>
          <label class="form-check-label" for="reason2">
            Ordered by mistake
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="cancel_reason" id="reason3" value="Shipping is too slow" required>
          <label class="form-check-label" for="reason3">
            Shipping is too slow
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="cancel_reason" id="reason4" value="Other reasons" required>
          <label class="form-check-label" for="reason4">
            Other reasons
          </label>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Confirm Cancel</button>
      </div>
    </form>
  </div>
</div>

@endsection
