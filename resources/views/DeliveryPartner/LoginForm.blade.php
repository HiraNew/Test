@extends('layouts.delivery')

@section('title', 'Delivery Partner Login')

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

    .error-message {
        color: red;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Login as Delivery Partner</h3>
                 @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <form method="POST" action="{{ route('partner.login.submit') }}" onsubmit="return validateLoginForm()">
                    @csrf

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile') }}" maxlength="10" required>
                        @error('mobile')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" maxlength="10" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-custom">Login as Delivery Partner</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    {{-- {{ route('partner.register') }} --}}
                    <a href="#" class="text-decoration-none">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateLoginForm() {
    const mobile = document.getElementById('mobile').value.trim();
    const password = document.getElementById('password').value.trim();

    const mobileRegex = /^\d{10}$/;

    if (!mobileRegex.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        return false;
    }

    if (password.length < 6 || password.length > 10) {
        alert("Password must be between 6 and 10 characters.");
        return false;
    }

    return true;
}
</script>
@endsection
