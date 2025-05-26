<div class="bg-white shadow-sm p-3" style="width: 300px; min-height: 100vh;">
    <h5 class="fw-bold mb-3">Filters</h5>

    <!-- Categories -->
    <div class="mb-3">
        <p class="mb-1 fw-semibold">CATEGORIES</p>
        <div>
            <a class="text-decoration-none d-block mb-1" data-bs-toggle="collapse" href="#categoryCollapse" role="button" aria-expanded="true" aria-controls="categoryCollapse">
                ▾ Wearable Smart Devices
            </a>
            <div class="collapse show" id="categoryCollapse">
                <div class="ps-3">Smart Watches</div>
            </div>
        </div>
    </div>

    <!-- Price Filter -->
    <div class="mb-3">
        <p class="fw-semibold mb-1">PRICE</p>
        <input type="range" class="form-range mb-2" min="0" max="20000" step="1000">
        <div class="d-flex justify-content-between">
            <select class="form-select form-select-sm w-50 me-1">
                <option>Min</option>
                <option>₹1000</option>
                <option>₹5000</option>
            </select>
            <select class="form-select form-select-sm w-50">
                <option>₹20000+</option>
                <option>₹10000</option>
                <option>₹5000</option>
            </select>
        </div>
    </div>

    <!-- Assured & Delivery -->
    <div class="mb-3">
        <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" id="assured">
            <label class="form-check-label" for="assured">
                {{-- <img src="https://rukminim1.flixcart.com/www/200/200/promos/04/11/2020/a3526a52-5b7c-4b8d-9f7c-e4502513887e.png?q=90" width="20" alt="Assured" class="me-1"> --}}
                DLS Assured
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="delivery">
            <label class="form-check-label" for="delivery">Delivery in 1 day</label>
        </div>
    </div>

    <!-- Brand -->
    <div class="mb-3">
        <p class="fw-semibold mb-1">BRAND</p>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="brandNoise">
            <label class="form-check-label" for="brandNoise">Noise</label>
        </div>
    </div>

    <!-- Ratings -->
    <div class="mb-3">
        <p class="fw-semibold mb-1">CUSTOMER RATINGS</p>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rating4">
            <label class="form-check-label" for="rating4">4★ & above</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rating3">
            <label class="form-check-label" for="rating3">3★ & above</label>
        </div>
    </div>

    <!-- Offers -->
    <div class="mb-3">
        <p class="fw-semibold mb-1">OFFERS</p>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="offer1">
            <label class="form-check-label" for="offer1">Buy More, Save More</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="offer2">
            <label class="form-check-label" for="offer2">Special Price</label>
        </div>
    </div>

    <!-- Accordion Sections -->
    <div class="accordion" id="accordionFilters">
        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#discount" aria-expanded="false">
                    DISCOUNT
                </button>
            </h2>
            <div id="discount" class="accordion-collapse collapse">
                <div class="accordion-body px-0">Coming soon...</div>
            </div>
        </div>

        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#gst" aria-expanded="false">
                    GST INVOICE AVAILABLE
                </button>
            </h2>
            <div id="gst" class="accordion-collapse collapse">
                <div class="accordion-body px-0">Coming soon...</div>
            </div>
        </div>

        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#availability" aria-expanded="false">
                    AVAILABILITY
                </button>
            </h2>
            <div id="availability" class="accordion-collapse collapse">
                <div class="accordion-body px-0">Coming soon...</div>
            </div>
        </div>
    </div>
</div>
