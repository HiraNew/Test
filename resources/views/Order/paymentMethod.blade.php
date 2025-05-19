@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Select a Payment Method</h4>
                    <p class="mb-0 small">Please choose one to complete your order</p>
                </div>
                <div class="card-body">
                    {{-- {{ route('payment.process') }} --}}

                    <form action="" method="POST">
                        @csrf

                        {{-- Cash on Delivery --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required>
                            <label class="form-check-label" for="cod">
                                <strong>Cash on Delivery</strong><br>
                                <span class="text-muted">Pay with cash when your order is delivered.</span>
                            </label>
                        </div>

                        {{-- Credit / Debit Card --}}
                        {{-- <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                            <label class="form-check-label" for="card">
                                <strong>Credit / Debit Card</strong><br>
                                <span class="text-muted">Visa, Mastercard, RuPay supported.</span>
                            </label>
                            <div id="card-details" class="mt-3 d-none">
                                <input type="text" class="form-control mb-2" placeholder="Card Number">
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="MM/YY">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="CVV">
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- UPI --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="upi" value="upi">
                            <label class="form-check-label" for="upi">
                                <strong>UPI / QR Code</strong><br>
                                <span class="text-muted">Google Pay, PhonePe, Paytm, etc.</span>
                            </label>
                            <div id="upi-details" class="mt-3 d-none text-center">
                                <input type="text" class="form-control" placeholder="Enter UPI ID">
                                <p class="text-muted mt-2 mb-0">OR scan the QR code below</p>
                                <img src="{{ asset('phonePe.jpg') }}" 
                                alt="QR Code" 
                                class="img-fluid mt-2 d-block mx-auto" 
                                style="max-height: 500px;" 
                                oncontextmenu="return false;" 
                                ondragstart="return false;" 
                                onselectstart="return false;" 
                                onmousedown="return false;">

                            </div>
                        </div>

                        {{-- Gift Card --}}
                        {{-- <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="giftcard" value="giftcard">
                            <label class="form-check-label" for="giftcard">
                                <strong>Gift Card</strong><br>
                                <span class="text-muted">Use your gift card or voucher code.</span>
                            </label>
                            <div id="giftcard-details" class="mt-3 d-none">
                                <input type="text" class="form-control" placeholder="Enter Gift Card Code">
                            </div>
                        </div> --}}

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success w-100">Proceed to Pay</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional: Include Bootstrap JS --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const radios = document.querySelectorAll('input[name="payment_method"]');

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('#card-details, #upi-details, #giftcard-details').forEach(el => el.classList.add('d-none'));

                switch (radio.value) {
                    case 'card':
                        document.getElementById('card-details').classList.remove('d-none');
                        break;
                    case 'upi':
                        document.getElementById('upi-details').classList.remove('d-none');
                        break;
                    case 'giftcard':
                        document.getElementById('giftcard-details').classList.remove('d-none');
                        break;
                }
            });
        });
    });
</script>
@endsection
