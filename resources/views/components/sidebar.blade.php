<!-- resources/views/components/sidebar.blade.php -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .custom-sidebar-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-sidebar {
        position: fixed;
        top: 0;
        left: -300px;
        height: 100%;
        width: 280px;
        background-color: #fff;
        z-index: 1050;
        transition: left 0.3s ease-in-out;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
    }

    .custom-sidebar.open {
        left: 0;
    }

    .custom-sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-sidebar-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .price-values {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }

    .range-wrapper input[type="range"] {
        width: 100%;
    }
</style>

<!-- Sidebar Triggers -->
<button id="openSidebarBtn" class="btn btn-primary d-lg-none mb-3">
    <i class="bi bi-sliders me-2"></i> Apply Filter
</button>
<button id="openSidebarBtnLarge"
    class="btn btn-primary d-none d-lg-inline-block px-4 py-2 fw-semibold shadow-sm rounded-pill mb-4 position-fixed start-0 top-50 translate-middle-y ms-2">
    <i class="bi bi-sliders me-2"></i> Apply Filter
</button>


<!-- Backdrop -->
<div id="sidebarBackdrop" class="custom-sidebar-backdrop"></div>

<!-- Sidebar -->
<aside id="customSidebar" class="custom-sidebar">
    <div class="custom-sidebar-header">
        <h5 class="mb-0">Filters</h5>
        <button id="closeSidebarBtn" class="custom-sidebar-close">&times;</button>
    </div>

    <div class="p-3">
        {{-- Categories --}}
        <h6>Categories</h6>
        @if (isset($categoriesList))
            @foreach ($categoriesList as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cat{{ $category->id }}">
                    <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                </div>
            @endforeach
        @else
            @foreach ($categories as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cat{{ $category->id }}">
                    <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                </div>
            @endforeach
        @endif
        
        <hr>

        {{-- Price Range --}}
        <h6>Price Range</h6>
        <div class="range-wrapper">
            <input type="range" id="priceRange" min="0" max="10000" step="100" value="5000">
        </div>
        <div class="price-values">
            <span>Min: ₹<span id="minPrice">0</span></span>
            <span>Max: ₹<span id="maxPrice">5000</span></span>
        </div>

        <hr>

        <button id="applyFilterBtn" class="btn btn-primary w-100">Apply Filters</button>
    </div>
</aside>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('customSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const openBtn = document.getElementById('openSidebarBtn');
        const openBtnLarge = document.getElementById('openSidebarBtnLarge');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const priceRange = document.getElementById('priceRange');
        const minPriceSpan = document.getElementById('minPrice');
        const maxPriceSpan = document.getElementById('maxPrice');
        const applyBtn = document.getElementById('applyFilterBtn');

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.style.display = 'block';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.style.display = 'none';
        }

        openBtn?.addEventListener('click', openSidebar);
        openBtnLarge?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        // Update displayed price range
        priceRange?.addEventListener('input', function () {
            const value = parseInt(this.value);
            maxPriceSpan.textContent = value;
        });

        // Apply filter (frontend only)
        applyBtn?.addEventListener('click', function () {
            const maxPrice = parseInt(priceRange.value);
            let anyVisible = false;

            document.querySelectorAll('.product-card').forEach(card => {
                const price = parseFloat(card.getAttribute('data-price')) || 0;
                if (price <= maxPrice) {
                    card.style.display = 'block';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Optional: show "No products found"
            const noResult = document.getElementById('noProductsMessage');
            if (noResult) {
                noResult.style.display = anyVisible ? 'none' : 'block';
            }

            closeSidebar(); // auto-close
        });
    });
</script>
