@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h3>{{ __('Register') }}</h3>
                    <p class="text-sm">Create an account to get started</p>
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-{{session('status_type')}} alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('registerUser') }}">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter full name.">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter email.">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Mobile Number -->
                        <div class="mb-4">
                            <label for="number" class="form-label">{{ __('Mobile Number') }}</label>
                            <input id="number" type="text" maxlength="10" class="form-control @error('number') is-invalid @enderror"
                                name="number" required placeholder="Enter 10-digit mobile number." value="{{ old('number') }}" autocomplete="number">
                            <div id="send-otp-container" class="mt-2" style="display: none;">
                                <button type="button" id="send-otp-btn" class="btn btn-outline-primary btn-sm">Send OTP</button>
                                <small id="otp-status" class="text-success d-block mt-1"></small>
                            </div>
                            @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- OTP Field -->
                       <div class="mb-4">
                        <label for="otp" class="form-label">{{ __('Enter OTP') }}</label>
                        <input id="otp" type="number" name="otp" class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}" required>

                        @error('otp')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                        </div>


                        <!-- Register Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-3 text-center">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const numberInput = document.getElementById('number');
    const sendOtpContainer = document.getElementById('send-otp-container');
    const sendOtpBtn = document.getElementById('send-otp-btn');
    const otpField = document.getElementById('otp-field');
    const otpStatus = document.getElementById('otp-status');

    numberInput.addEventListener('input', function () {
        const value = numberInput.value;
        if (/^\d{10}$/.test(value)) {
            sendOtpContainer.style.display = 'block';
        } else {
            sendOtpContainer.style.display = 'none';
        }
    });

    sendOtpBtn.addEventListener('click', function () {
        const number = numberInput.value;
        otpStatus.textContent = 'Sending OTP...';

        fetch('{{ route('send.otp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ number })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                otpStatus.textContent = 'OTP sent successfully!';
                otpField.style.display = 'block';
            } else {
                otpStatus.textContent = 'Failed to send OTP.';
            }
        });
    });
});
</script>

@endsection

{{-- Styles
<style>
    .card {
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 25px;
        padding: 15px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        border-color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 25px;
        padding: 12px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-link {
        font-size: 14px;
        color: #007bff;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .form-check-input {
        border-radius: 5px;
    }

    .card-body {
        padding: 30px;
    }

    /* Small Text Below Header */
    .card-header p {
        font-size: 14px;
        color: #aaa;
        margin-top: 5px;
    }

    /* Hover Effects */
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style> --}}
