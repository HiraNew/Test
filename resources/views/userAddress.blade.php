@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
@if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
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

                            @php
                                $selectedCountry = old('country', $address->country_id ?? '');
                                $selectedState = old('state', $address->state_id ?? '');
                                $selectedCity = old('city', $address->city_id ?? '');
                                $selectedVillage = old('village', $address->village_id ?? '');
                            @endphp

                            {{-- Country Dropdown + Manual --}}
                            <div class="form-floating mb-3">
                                <select class="form-select" id="country" name="country" onchange="toggleManualInput(this, 'country')" required>
                                    <option value="" disabled {{ !$selectedCountry ? 'selected' : '' }}>Choose your country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ $selectedCountry == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                    {{-- <option value="manual">Other (Type manually)</option> --}}
                                </select>
                                {{-- <input type="text" class="form-control mt-2 d-none" id="country_manual" name="country_manual" placeholder="Enter country manually">
                                <label for="country">🌍 Country</label> --}}
                            </div>

                            {{-- State Dropdown + Manual --}}
                            <div class="form-floating mb-3">
                                <select class="form-select" id="state" name="state" onchange="toggleManualInput(this, 'state')" required>
                                    <option value="" disabled {{ !$selectedState ? 'selected' : '' }}>Choose your state</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{ $selectedState == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                    {{-- <option value="manual">Other (Type manually)</option> --}}
                                </select>
                                {{-- <input type="text" class="form-control mt-2 d-none" id="state_manual" name="state_manual" placeholder="Enter state manually">
                                <label for="state">🏳️ State</label> --}}
                            </div>

                            {{-- City Dropdown + Manual --}}
                            <div class="form-floating mb-3">
                                <select class="form-select" id="city" name="city" onchange="toggleManualInput(this, 'city')" required>
                                    <option value="" disabled {{ !$selectedCity ? 'selected' : '' }}>Choose your city</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ $selectedCity == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                    {{-- <option value="manual">Other (Type manually)</option> --}}
                                </select>
                                {{-- <input type="text" class="form-control mt-2 d-none" id="city_manual" name="city_manual" placeholder="Enter city manually">
                                <label for="city">🏙️ City</label> --}}
                            </div>

                            {{-- Village Dropdown + Manual --}}
                            <div class="form-floating mb-3">
                                <select class="form-select" id="village" name="village" onchange="toggleManualInput(this, 'village')" required>
                                    <option value="" disabled {{ !$selectedVillage ? 'selected' : '' }}>Choose your village</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}" {{ $selectedVillage == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                    @endforeach
                                    {{-- <option value="manual">Other (Type manually)</option> --}}
                                </select>
                                {{-- <input type="text" class="form-control mt-2 d-none" id="village_manual" name="village_manual" placeholder="Enter village manually">
                                <label for="village">🏡 Village</label> --}}
                            </div>


                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                    value="{{ $address->postal_code ?? '' }}" placeholder="Postal Code" required>
                                <label for="postal_code"><i class="fas fa-envelope me-2"></i>Postal Code</label>
                                <div class="invalid-feedback">Please enter your postal code.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="mobile_number" name="mobile_number"
                                    value="{{ $address->mobile_number ?? '' }}" placeholder="Mobile Number" required pattern="[0-9]{10}">
                                <label for="mobile_number"><i class="fas fa-phone me-2"></i>Mobile Number</label>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="alt_mobile_number" name="alt_mobile_number"
                                    value="{{ $address->alt_mobile_number ?? '' }}" placeholder="Alternate Mobile Number" pattern="[0-9]{10}">
                                <label for="alt_mobile_number"><i class="fas fa-phone-alt me-2"></i>Alternate Mobile Number (optional)</label>
                                <div class="invalid-feedback">Please enter a valid 10-digit alternate mobile number.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="landmark" name="landmark"
                                    value="{{ $address->landmark ?? '' }}" placeholder="Landmark">
                                <label for="landmark"><i class="fas fa-location-arrow me-2"></i>Landmark (optional)</label>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>Confirm Address
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
    //  function toggleManualInput(selectElem, type) {
    //     const manualInput = document.getElementById(type + '_manual');
    //     if (selectElem.value === 'manual') {
    //         manualInput.classList.remove('d-none');
    //         manualInput.required = true;
    //     } else {
    //         manualInput.classList.add('d-none');
    //         manualInput.required = false;
    //     }
    // }
</script>
@endsection
