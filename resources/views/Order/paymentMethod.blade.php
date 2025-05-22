@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Select a Payment Method</h4>
                    <p class="mb-0 small">Please choose one to complete your order</p>
                </div>
                <div class="card-body">

                    <form id="paymentForm" method="POST" action="{{ url('/paymentMethod/proceed') }}">
                        @csrf

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required>
                            <label class="form-check-label" for="cod">
                                <strong>Cash on Delivery</strong><br>
                                <span class="text-muted">Pay with cash when your order is delivered.</span>
                            </label>
                        </div>

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

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success w-100">Order Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const radios = document.querySelectorAll('input[name="payment_method"]');
        const form = document.getElementById('paymentForm');

        // Show/hide UPI section
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                document.getElementById('upi-details').classList.add('d-none');
                if (radio.value === 'upi') {
                    document.getElementById('upi-details').classList.remove('d-none');
                }
            });
        });

        // Intercept form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please select a payment method',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Order?',
                text: `You selected: ${selectedPayment.labels[0].innerText.trim()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, place order',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Optional loading
                    Swal.fire({
                        title: 'Placing your order...',
                        html: 'Please wait a moment.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    setTimeout(() => form.submit(), 1000); // Delay for UX
                }
            });
        });
    });
</script>
@endsection
