@extends('layouts.app')

@section('content')
@include('components.sidebar')

<style>
/* ——— Category Container & Items ——— */
.category-scroll-container {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  padding: 1rem 0;
  white-space: nowrap;
}
.category-scroll-container::-webkit-scrollbar {
  height: 6px;
}
.category-scroll-container::-webkit-scrollbar-thumb {
  background: #ccc; border-radius: 10px;
}
.category-item {
  display: inline-block;
  vertical-align: top;
  width: 80px;
  margin: 0 12px;
  text-align: center;
  position: relative;
  transition: transform 0.3s ease;
}
.category-item:hover {
  transform: translateY(-4px);
}
.category-item img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 50%;
  margin-bottom: 6px;
}
.category-item .name {
  font-weight: 600;
  font-size: 0.875rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ——— Subcategory Dropdown ——— */
.subcategory-dropdown {
  display: none;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1000;
  max-width: 90vw;
  background: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  border-radius: 6px;
  padding: 0.5rem;
  overflow-x: auto;
  white-space: nowrap;
}
.subcategory-dropdown a {
  display: inline-block;
  margin-right: 0.5rem;
  padding: 0.4rem 0.8rem;
  background: #f8f9fa;
  border-radius: 4px;
  color: #333;
  font-size: 0.85rem;
}
.category-item.show-submenu .subcategory-dropdown {
  display: block;
}
.dropdown-toggle-btn {
  display: block;
  cursor: pointer;
  margin-top: 4px;
  font-size: 0.85rem;
}
.dropdown-arrow {
  display: inline-block;
  transition: transform 0.3s;
}
.category-item.show-submenu .dropdown-arrow {
  transform: rotate(180deg);
}

/* ——— Product Cards ——— */
.product-card .card {
  transition: transform 0.3s, box-shadow 0.3s;
}
.product-card .card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.12);
}
.card-img-top {
  object-fit: cover;
  height: 180px;
  transition: transform 0.3s;
}
.card-img-top:hover {
  transform: scale(1.05);
}

/* ——— Responsive Touch ——— */
@media (hover: none) {
  .category-item:hover {
    transform: none;
  }
}
</style>

<div class="container">
  {{-- Category Row --}}
  <div class="category-scroll-container bg-white rounded shadow-sm">
    @foreach ($categoriesList as $category)
      <div class="category-item" data-id="{{ $category->id }}">
        <a href="{{ route('category.view', $category->slug) }}">
          <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}">
        </a>
        <div class="name {{ strtolower($category->name)=='fashion'?'text-primary':'' }}">
          {{ $category->name }}
        </div>

        @if ($category->subcategories->count())
          <div class="dropdown-toggle-btn d-block d-md-none" onclick="toggleSub(this)">
            <span class="dropdown-arrow">▼</span>
          </div>
          <div class="subcategory-dropdown">
            @foreach ($category->subcategories as $sub)
              <a href="{{ route('category.view', $sub->slug) }}">{{ $sub->name }}</a>
            @endforeach
          </div>
        @endif
      </div>
    @endforeach
  </div>

  {{-- Products Section --}}
  <div class="mt-4">
    <h3 class="mb-3">
      @if($categories)
        Products in Category: <span class="text-primary">{{ $categories->name }}</span>
      @elseif($subcategory)
        Products in Subcategory: <span class="text-primary">{{ $subcategory->name }}</span>
      @else
        Products
      @endif
    </h3>

    @if ($products->count())
      <div class="row g-3">
        @foreach ($products as $product)
          <div class="col-6 col-md-4 col-lg-3 product-card" data-price="{{ $product->price }}">
            <div class="card h-100 position-relative">
              @php $wish = in_array($product->id, $wishlistProductIds); @endphp
              <button class="wishlist-btn position-absolute top-0 end-0 m-2 btn btn-sm rounded-circle {{ $wish?'wishlist-active':'' }}" data-id="{{ $product->id }}" title="{{ $wish?'Remove':'Add' }}">
                <i class="{{ $wish?'fas':'far' }} fa-heart"></i>
              </button>
              <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}" alt="{{ $product->name }}">
              <div class="card-body d-flex flex-column">
                <h6 class="fw-semibold text-truncate">{{ $product->name }}</h6>
                <div class="mt-1 mb-2 fs-5 text-primary fw-bold">₹{{ number_format($product->price,2) }}</div>
                <div class="mb-2 text-warning small">
                  {{-- @php $avg=round($product->reviews_avg_rating??0,1); $cnt=$product->reviews_count; @endphp
                  @for($i=1;$i<=5;$i++)
                    <i class="bi bi-star{{ $i<=$avg?'‑fill':( ($i‑$avg)<1?'‑half':'') }}"></i>
                  @endfor --}}
                  {{-- <span class="text-muted">({{ $cnt }})</span> --}}
                </div>
                <div class="mt-auto d-grid gap-2">
                  @if (in_array($product->id, $cartProductIds))
                    <a href="{{ route('cartView') }}" class="btn btn-outline-info btn-sm">
                      <i class="fas fa-shopping-cart me-1"></i>Go to Cart
                    </a>
                  @elseif ($product->quantity<1)
                    <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-ban me-1"></i>Out of Stock</button>
                  @else
                    <a href="{{ url('addTocart',$product->id) }}" class="btn btn-success btn-sm addToCart"><i class="fas fa-cart-plus me-1"></i>Add to Cart</a>
                  @endif
                  <a href="{{ url('detail',$product->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-info-circle me-1"></i>Details</a>
                </div>
              </div>
            </div>
          </div>

          {{-- Quick View Modal --}}
          <div class="modal fade" id="quickViewModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">{{ $product->name }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                  <div class="col-md-6"><img src="{{ asset('storage/'.$product->image) }}" class="img-fluid rounded"></div>
                  <div class="col-md-6">
                    <h4 class="text-primary">₹{{ number_format($product->price,2) }}</h4>
                    {{-- <div class="text-warning mb-2">
                      @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star{{ $i<=$avg?'':'‑o' }}"></i>
                      @endfor
                      <small class="text-muted">({{ $cnt }})</small>
                    </div> --}}
                    <p>{{ $product->ldescription ?? 'No description available.' }}</p>
                    <div class="d-grid gap-2">
                      @if (in_array($product->id, $cartProductIds))
                        <a href="{{ route('cartView') }}" class="btn btn-outline-info">Go to Cart</a>
                      @elseif ($product->quantity<1)
                        <button class="btn btn-secondary" disabled>Out of Stock</button>
                      @else
                        <a href="{{ url('addTocart',$product->id) }}" class="btn btn-success addToCart">Add to Cart</a>
                      @endif
                      <a href="{{ url('detail',$product->id) }}" class="btn btn-warning">View Details</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="d-flex justify-content-center mt-4">{{ $products->links() }}</div>
    @else
      <p class="fs-5 text-muted">No products found in this category.</p>
    @endif
  </div>
</div>

<script>
function toggleSub(el) {
  const parent = el.closest('.category-item');
  parent.classList.toggle('show-submenu');
  document.querySelectorAll('.category-item').forEach(ci => {
    if (ci !== parent) ci.classList.remove('show-submenu');
  });
}
document.addEventListener('click', e => {
  if (!e.target.closest('.category-item')) {
    document.querySelectorAll('.category-item.show-submenu').forEach(ci => ci.classList.remove('show-submenu'));
  }
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Include your existing addToCart & wishlist AJAX scripts here --}}
@endsection
