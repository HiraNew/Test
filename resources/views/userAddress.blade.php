@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white fw-semibold fs-5">
                    <i class="fas fa-map-marker-alt me-2"></i>Delivery Address Details
                </div>

                <div class="card-body">
                    <form id="addressForm" method="POST" action="{{ url('/cart-Proceed') }}" class="needs-validation" novalidate>
                        @csrf

                        <fieldset class="border p-3 rounded mb-4">
                            <legend class="float-none w-auto px-2 fs-6 text-primary fw-bold">Shipping Information</legend>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $address->address ?? '' }}" placeholder="Address" required>
                                <label for="address"><i class="fas fa-home me-2"></i>Full Address</label>
                                <div class="invalid-feedback">Please enter your full address.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="pincode" name="pincode"
                                    value="{{ $address->pincode ?? '' }}" placeholder="Pincode" required>
                                <label for="pincode"><i class="fas fa-map-pin me-2"></i>Pincode</label>
                                <div class="invalid-feedback">Please enter a valid pincode.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="state" name="state" required>
                                    <option value="" disabled {{ empty($address->state) ? 'selected' : '' }}>Choose your state</option>
                                    <option value="Maharashtra" {{ ($address->state ?? '') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                    <option value="Tamil Nadu" {{ ($address->state ?? '') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                    <option value="Karnataka" {{ ($address->state ?? '') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                    <option value="Delhi" {{ ($address->state ?? '') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                </select>
                                <label for="state"><i class="fas fa-flag me-2"></i>State</label>
                                <div class="invalid-feedback">Please select your state.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="city" name="city" required>
                                    <option value="" disabled {{ empty($address->city) ? 'selected' : '' }}>Choose your city</option>
                                    <option value="Delhi" {{ ($address->city ?? '') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                    <option value="Mumbai" {{ ($address->city ?? '') == 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                                    <option value="Bengaluru" {{ ($address->city ?? '') == 'Bengaluru' ? 'selected' : '' }}>Bengaluru</option>
                                    <option value="Chennai" {{ ($address->city ?? '') == 'Chennai' ? 'selected' : '' }}>Chennai</option>
                                </select>
                                <label for="city"><i class="fas fa-city me-2"></i>City</label>
                                <div class="invalid-feedback">Please select your city.</div>
                            </div>


                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="landmark" name="landmark"
                                    value="{{ $address->landmark ?? '' }}" placeholder="Landmark">
                                <label for="landmark"><i class="fas fa-location-arrow me-2"></i>Landmark (optional)</label>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>Order Now
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Bootstrap form validation --}}
<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
