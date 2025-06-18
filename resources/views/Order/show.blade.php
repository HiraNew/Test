@extends('layouts.app')

@section('content')
<style>
    /* Container for tracker */
    .tracker {
        position: relative;
        padding-left: 40px;
        margin-top: 20px;
    }

    .tracker::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 24px;
        width: 2px;
        background-color: #ccc;
        z-index: 0;
    }

    .tracker-step {
        display: flex;
        align-items: center;
        position: relative;
        margin-bottom: 30px;
        z-index: 1;
    }

    .tracker-step:last-child {
        margin-bottom: 0;
    }

    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ccc;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        position: absolute;
        left: 10px;
        top: 0;
        z-index: 2;
    }

    .step-label {
        margin-left: 40px;
        font-size: 14px;
    }

    .completed .step-icon {
        background-color: green;
    }

    .current .step-icon {
        background-color: orange;
        animation: blink 1s step-end infinite;
    }

    /* @keyframes blink {
        50% {
            opacity: 0;
        }
    } */

    /* Add small dot between steps */
    .tracker-step::after {
        content: '';
        position: absolute;
        left: 23px;
        top: 30px;
        height: calc(100% - 30px);
        width: 4px;
        background-color: #ccc;
        z-index: 0;
    }

    .tracker-step:last-child::after {
        content: none;
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .step-label {
            font-size: 12px;
        }

        .step-icon {
            width: 24px;
            height: 24px;
            font-size: 13px;
            left: 5px;
        }

        .tracker::before {
            left: 17px;
        }

        .tracker-step::after {
            left: 17px;
        }

        .step-label {
            margin-left: 35px;
        }
    }
    .modal-content {
        border-radius: 10px;
        padding: 20px;
    }

    .modal-title {
        font-weight: 600;
        color: #343a40;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }
    @media (min-width: 992px) {
        .tracker {
            padding-left: 50px;
        }

        .tracker-step {
            margin-bottom: 40px;
        }

        .step-icon {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }

        .step-label {
            font-size: 16px;
            margin-left: 50px;
        }
    }
        .container {
        max-width: 1140px;
    }
    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        border: none;
        padding: 2rem;
    }

    /* For large screens: flex row layout */
    @media (min-width: 992px) {
        .card .row {
            display: flex;
            flex-direction: row;
            gap: 2rem;
        }

        .card .row > div:first-child {
            flex: 1; /* image column */
            max-width: 40%;
        }

        .card .row > div:last-child {
            flex: 2; /* content column */
        }

        .card img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
        }
    }

    .out-for-delivery-alert {
        display: inline-block;
        margin: 0 10px;
        vertical-align: middle;
        color: #ffa500; /* orange for visibility */
        font-size: 16px;
    }

    .out-for-delivery-alert i {
        animation: pulse 1.5s infinite;
    }

    /* Optional animation for subtle attention */
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); opacity: 0.8; }
    }





</style>
    @php
    $returnDays = $payment->product->extra1 ?? 0;
    $eligibleDate = $payment->created_at->copy()->addDays($returnDays);
    $isReturnEligible = now()->lte($eligibleDate) && is_null($payment->return_period);
    @endphp

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <p><strong>Order ID - </strong> {{ $payment->orderid }}</p>
            <div class="card p-4 shadow-sm bg-white rounded-4 border-0">
                <div class="row">
                    <h2>Order Details - #{{ $payment->product->name }}</h2>
                   <div class="col-12 col-lg-5">
                        @if($payment->product->image)
                            <img src="{{ url($payment->product->image) }}" 
                                alt="{{ $payment->product->name }}" 
                                class="img-fluid w-100"
                                style="object-fit: contain; max-height: 400px;">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" 
                                alt="No Image" 
                                class="img-fluid w-100"
                                style="object-fit: contain; max-height: 400px;">
                        @endif
                    </div>
                    <div class="col-lg-8">
                    <div class="col-12 col-lg-7">
                        <hr>
                        <h4>Price Details</h4>

                        <div class="mb-2 d-flex justify-content-between">
                            <span><strong>Amount:</strong></span>
                            <span>₹{{ number_format($payment->amount, 2) }}</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between">
                            <span><strong>Status:</strong></span>
                            <span class="@if($payment->status == 'delivered') text-success
                                        @elseif($payment->status == 'cancelled') text-danger
                                        @else text-warning
                                        @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span>Shipping Charges</span>
                            <span>₹0</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between">
                            <span><strong>Order Date:</strong></span>
                            <span>{{ $payment->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between">
                            <span><strong>Mode of Payment:</strong></span>
                            <span>{{ $payment->payment_mode ? 'Cash on delivery' : 'Online Payment' }}</span>
                        </div>

                        @if ($payment->status === 'delivered' && $eligibleDate)
                            <div class="mb-2 d-flex justify-content-between">
                                <span><strong>Return Policy Ends:</strong></span>
                                <span>{{ $eligibleDate->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                            </div>
                        @endif

                        <hr class="my-2">

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Amount</span>
                            <span>₹{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        

                        @if ($payment->status === 'shipped')
                            <div class="mt-3">
                                @if (!empty($payment->feild1))
                                    <a href="tel:{{ $payment->feild1 }}">
                                        <b class="btn btn-success w-0">Call : </b> 
                                    </a>
                                    {{ $payment->feild1 }}
                                @else
                                    <span class="btn btn-secondary w-100" disabled>
                                        Wait For Delivery Person
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($address)
                            <hr>
                            <h4 class="fw-bold mb-3"><i class="fas fa-shipping-fast me-2 text-primary"></i>Shipping Address</h4>
                            <div class="ps-3">
                                <p class="mb-1"><strong>Address:</strong> {{ $address->address }}</p>

                                @if($address->village?->name)
                                    <p class="mb-1"><strong>Village:</strong> {{ $address->village->name }}</p>
                                @endif

                                @if($address->city?->name)
                                    <p class="mb-1"><strong>City:</strong> {{ $address->city->name }}</p>
                                @endif

                                @if($address->state?->name)
                                    <p class="mb-1"><strong>State:</strong> {{ $address->state->name }}</p>
                                @endif

                                @if($address->country?->name)
                                    <p class="mb-1"><strong>Country:</strong> {{ $address->country->name }}</p>
                                @endif

                                @if(!empty($address->landmark))
                                    <p class="mb-1"><strong>Landmark:</strong> {{ $address->landmark }}</p>
                                @endif

                                <p class="mb-1"><strong>Mobile Number:</strong> {{ $address->mobile_number }}</p>

                                @if(!empty($address->alt_mobile_number))
                                    <p class="mb-1"><strong>Alternate Mobile No:</strong> {{ $address->alt_mobile_number }}</p>
                                @endif

                                <p class="mb-1"><strong>Pincode:</strong> {{ $address->pincode }}</p>
                            </div>
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
                                    if ($index < $currentIndex) $class = 'completed';
                                    elseif ($index == $currentIndex) $class = 'current';
                                @endphp

                                <div class="tracker-step {{ $class }}">
                                    <div class="step-icon">
                                        @if($class == 'completed')
                                            <i class="fas fa-check"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="step-label">{{ ucfirst($status) }}</div>
                                </div>

                                {{-- Insert minimal "Out for Delivery" icon between shipped and delivered --}}
                                @if($status === 'shipped' && $payment->status !== 'delivered' && $payment->status !== 'cancelled' && $payment->status !== 'pending')
                                    <div class="out-for-delivery-alert">
                                        <i class="fas fa-truck-moving" title="Your item is out for delivery"> </i> out for delivery
                                    </div>
                                @endif
                            @endforeach
                        </div>


                        {{-- Action Buttons Based on Status --}}
                        <div class="d-flex justify-content-between mt-4">
                            @if(in_array($payment->status, ['pending', 'confirmed']))
                                {{-- Edit and Cancel available --}}
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editAddressModal">Edit Address?</button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order?</button>

                            @elseif($payment->status === 'shipped')
                                {{-- Only Cancel available --}}
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order?</button>

                            @elseif($payment->status === 'delivered' && $isReturnEligible)
                                {{-- Only Return available --}}
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Return Order?</button>
                            @endif
                        </div>

                        {{-- Reorder Button --}}
                        @if(in_array($payment->status, ['delivered', 'cancelled']))
                            <div class="d-flex justify-content-center mt-4">
                                <a href="{{ route('detail', $payment->product->id) }}">
                                    <button class="btn btn-success">Order Again?</button>
                                </a>
                            </div>
                        @endif

                        {{-- Help Link --}}
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('detail', $payment->product->id) }}">Help?</a>
                        </div>

                        
                    </div>
                </div>
                
            </div>
               @if (isset($payment->is_canceled))
                            <div class="d-flex justify-content-between">
                                <h4>Cancel by you.</h4>
                            <span>Cancelation Reason :</span>
                            <span>{{$payment->is_canceled}}</span>
                        </div>
                        @endif
        </div>

    </div>
</div>

{{-- Edit Address Modal --}}
<!-- Address Update Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Wider modal for better layout -->
            <form action="{{ route('address.update', $payment->id) }}" method="POST" class="modal-content p-3">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressModalLabel">Edit Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Address Line -->
                        <div class="col-12">
                            <label for="address_line_modal" class="form-label">Address Line</label>
                            <input type="text" class="form-control" id="address_line_modal" name="address_line"
                                value="{{ old('address_line', $payment->address->address_line ?? '') }}" required>
                        </div>

                        <!-- Country, State, City -->
                        <div class="col-md-4">
                            <label for="country" class="form-label">Country</label>
                            <select id="country" name="country" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('country', $payment->address->country ?? '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <select id="state" name="state" class="form-select" required>
                                <option value="">Select State</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <select id="city" name="city" class="form-select" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <!-- In your Blade view -->

                        <div class="col-md-4">
                            <label for="village" class="form-label">Village</label>
                            <select id="village" name="village" class="form-select" required>
                                <option value="">Select Village</option>
                            </select>
                        </div>


                        <!-- Postal and Pincode -->
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code"
                                value="{{ old('postal_code', $payment->address->postal_code ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode"
                                value="{{ old('pincode', $payment->address->pincode ?? '') }}" required>
                        </div>

                        <!-- Mobile Numbers -->
                        <div class="col-md-6">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                                value="{{ old('mobile_number', $payment->address->mobile_number ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="alt_mobile_number" class="form-label">Alt. Mobile Number</label>
                            <input type="text" class="form-control" id="alt_mobile_number" name="alt_mobile_number"
                                value="{{ old('alt_mobile_number', $payment->address->alt_mobile_number ?? '') }}">
                        </div>
                         <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="landmark" name="landmark"
                                    value="{{ $address->landmark ?? '' }}" placeholder="Landmark">
                                <label for="landmark"><i class="fas fa-location-arrow me-2"></i>Landmark (optional)</label>
                            </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>


<!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('order.cancel', $payment->id) }}" method="POST" class="modal-content" onsubmit="return confirm('Are you sure you want to cancel this order?');">
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
                    </div>
                    <div id="otherReasonInput" class="mt-2" style="display: none;">
                        <label for="other_reason_text" class="form-label">Please specify:</label>
                        <input type="text" class="form-control" id="other_reason_text" name="other_reason_text" placeholder="Please enter reason for cancellation.">
                    </label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm</button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const otherRadio = document.getElementById('reason4');
    const otherInput = document.getElementById('otherReasonInput');
    const allRadios = document.querySelectorAll('input[name="cancel_reason"]');

    function toggleOtherInput() {
        if (otherRadio.checked) {
            otherInput.style.display = 'block';
            document.getElementById('other_reason_text').setAttribute('required', 'required');
        } else {
            otherInput.style.display = 'none';
            document.getElementById('other_reason_text').removeAttribute('required');
        }
    }

    allRadios.forEach(radio => radio.addEventListener('change', toggleOtherInput));
    toggleOtherInput(); // run on load
});


    document.getElementById('country').addEventListener('change', function() {
        let countryId = this.value;
        if (countryId) {
            fetch(`/states/${countryId}`)
                .then(response => response.json())
                .then(data => {
                    let stateSelect = document.getElementById('state');
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    for (let id in data) {
                        stateSelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                    }
                });
        }
    });

    document.getElementById('state').addEventListener('change', function() {
        let stateId = this.value;
        if (stateId) {
            fetch(`/cities/${stateId}`)
                .then(response => response.json())
                .then(data => {
                    let citySelect = document.getElementById('city');
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    for (let id in data) {
                        citySelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                    }
                });
        }
    });
    document.getElementById('city').addEventListener('change', function() {
        let cityId = this.value;
        if (cityId) {
            fetch(`/villages/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    let villageSelect = document.getElementById('village');
                    villageSelect.innerHTML = '<option value="">Select Village</option>';
                    for (let id in data) {
                        villageSelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                    }
                });
        }
    });

     window.addEventListener("pageshow", function (event) {
        // If coming back from back/forward browser button, force reload
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });

</script>


@endsection
