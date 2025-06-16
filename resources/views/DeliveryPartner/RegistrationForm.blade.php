@extends('layouts.delivery')

@section('title', 'Delivery Partner Registration')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        font-family: 'Inter', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .card {
        animation: slideFade 0.7s ease-out;
        border-radius: 15px;
    }

    @keyframes slideFade {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #4facfe;
    }

    .btn-custom {
        background: #4facfe;
        color: white;
        transition: background 0.3s ease;
    }

    .btn-custom:hover {
        background: #00c6ff;
    }

    .otp-field {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Register as Delivery Partner</h3>
{{-- {{ route('partner.register.submit') }} --}}
                <form method="POST" action="#">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" id="mobile" class="form-control" required>
                    </div>

                    <!-- OTP Field (hidden initially) -->
                    <div class="mb-3 otp-field" id="otpField">
                        <label for="otp" class="form-label">OTP</label>
                        <input type="text" name="otp" id="otp" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Create Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-custom">Register</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    {{-- {{ route('partner.login') }} --}}
                    <a href="#" class="text-decoration-none">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mobileInput = document.getElementById('mobile');
    const otpField = document.getElementById('otpField');

    mobileInput.addEventListener('blur', function () {
        const mobile = this.value.trim();
        const errorDiv = this.nextElementSibling;

        if (mobile.length === 10 && /^\d{10}$/.test(mobile)) {
            otpField.style.display = 'block';
            this.classList.remove('is-invalid');
            if (errorDiv) errorDiv.remove();
        } else {
            otpField.style.display = 'none';
            this.classList.add('is-invalid');
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                const error = document.createElement('div');
                error.className = 'invalid-feedback';
                error.innerText = 'Please enter a valid 10-digit mobile number.';
                this.parentElement.appendChild(error);
            }
        }
    });

    // Optional: Bootstrap form validation on submit
    document.querySelector('form').addEventListener('submit', function (event) {
        const form = this;
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
</script>   

@endpush
