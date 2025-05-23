@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    border-radius: 10px 10px 0 0;
    object-fit: cover;
    height: 200px;
}

.card-body {
    padding: 15px;
}

.card-title {
    font-size: 1.1rem;
    font-weight: bold;
    color: #333;
}

.card-text {
    font-size: 1rem;
    color: #555;
}

.btn-secondary {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
}

.btn-secondary:hover {
    background-color: #0056b3;
}

.btn-gradient {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: #fff;
    border: none;
    border-radius: 6px;
    transition: 0.3s ease;
}

.btn-gradient:hover {
    background: linear-gradient(135deg, #0072ff, #0052cc);
    color: #fff;
}

/* Responsive Enhancements */
@media (max-width: 576px) {
    .product-title {
        font-size: 1.5rem;
        text-align: center;
    }

    .btn-gradient {
        font-size: 1.1rem;
    }

    .list-unstyled li {
        font-size: 0.95rem;
        margin-bottom: 6px;
    }
}

.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}
.product-image {
    transition: transform 0.3s ease;
}
.product-image-container:hover .product-image {
    transform: scale(1.05);
}

/* Product Title */
.product-title {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
}

/* Add to Cart Button */
.add-to-cart-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}
.add-to-cart-btn:hover {
    background-color: #0056b3;
}

/* Reviews Section */
.review {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}
.stars i {
    margin-right: 2px;
}

/* Related Products */
.related-product-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
}
.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

</style>
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="product-image-container">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid product-image">
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-md-6 col-12 mt-4 mt-md-0">
    <div class="p-4 rounded shadow-sm bg-light border h-100 d-flex flex-column justify-content-between">

        <h2 class="product-title text-primary">{{ $product->name }}</h2>

        <ul class="list-unstyled mb-4">
            <li><strong class="text-dark">Seller:</strong> <span class="text-secondary">{{ $product->seller->name ?? 'DLS' }}</span></li>
            <li><strong class="text-dark">Size:</strong> <span class="text-secondary">{{ $product->size ?? 'N/A' }}</span></li>
            <li><strong class="text-dark"></strong> <span class="text-success fs-5">₹{{ number_format($product->price, 2) }}</span></li>
            <li><strong class="text-dark">Availability:</strong>
                <span class="{{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </li>
        </ul>

        <div class="mb-4">
            <h6 class="text-dark">Description:</h6>
            <p class="text-muted">{!! nl2br(e($product->ldescription)) !!}</p>
        </div>

        <!-- Add to Cart Button -->
        <!-- Action Buttons -->
        <div class="row g-2">
            <div class="col-6">
                <form action="{{ url('addTocart', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-gradient w-100 py-2 fs-6">
                        <i class="fas fa-cart-plus me-1"></i>Add to Cart
                    </button>
                </form>
            </div>
            <div class="col-6">
                <form action="{{ url('buynow', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 py-2 fs-6">
                        <i class="fas fa-bolt me-1"></i>Buy Now
                    </button>
                </form>
            </div>
        </div>


    </div>
</div>

    </div>

    <!-- Product Reviews -->
    <!-- Product Reviews -->
    <div class="mt-5">
       {{-- <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">Customer Reviews Overview</h4>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Average Rating</h5>
                        <div class="d-flex align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2 text-secondary">({{ $averageRating }}/5)</span>
                        </div>
                    </div>

                    <div>
                        <h5 class="mb-1">Total Reviews</h5>
                        <span class="badge bg-primary fs-6">{{ $product->reviews->count() }}</span>
                    </div>
                </div>
            </div>
        </div> --}}

        @if($averageRating > 0)
    <div class="mb-3">
        {{-- <strong>Average Rating:</strong>
        @for($i = 1; $i <= 5; $i++)
            <i class="fa fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
        @endfor
        <small class="text-muted">({{ $averageRating }}/5 from {{ $product->reviews->count() }} reviews)</small> --}}
    </div>
    <div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Average Customer Rating</h5>
         <div class="d-flex align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2 text-secondary">({{ $averageRating }}/5)</span>
                        </div>
        <p class="card-text">
            <strong>{{ number_format($averageRating, 1) }} / 5</strong> 
            <span class="badge bg-primary">{{ $product->reviews->count() }} Reviews</span>
        </p>
    </div>
</div>

@else
    <p class="text-muted">No ratings yet</p>
@endif


        @auth
        <!-- Add Review Form -->
        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title">Write a Review</h5>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label d-block">Rating</label>
                            <div id="starRating" class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star fs-4 text-muted star" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>

                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Write your thoughts..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Review</button>
                </form>
            </div>
        </div>
        @endauth
        

       @php
            $totalReviews = $product->reviews->count();
        @endphp

        <div class="mt-4">
            <h5 class="mb-3">Customer Reviews <span class="badge bg-primary">{{ $totalReviews }}</span></h5>

            <div id="review-container">
                @foreach($product->reviews as $index => $review)
                    <div class="review p-3 rounded mb-3 bg-light shadow-sm {{ $index >= 3 ? 'd-none more-review' : '' }}">
                        <p class="mb-1"><strong>{{ $review->user->name }}</strong> <small class="text-muted">({{ $review->created_at->format('d M Y') }})</small></p>
                        <div class="stars mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <p class="text-muted">{{ $review->review }}</p>
                    </div>
                @endforeach
            </div>

            @if($totalReviews > 3)
                <div class="text-center">
                    <button class="btn btn-outline-primary" id="toggle-reviews">Show More Reviews</button>
                </div>
            @endif
        </div>


        {{-- @guest
            <div class="alert alert-info mt-4">Please <a href="{{ route('login') }}">login</a> to leave a review.</div>
        @endguest --}}
    </div>


    <!-- Related Products -->
    <div class="mt-5">
    <h3>Related Products</h3>
    <div class="d-flex overflow-auto">
        @foreach($relatedProducts as $related)
            <div class="card mx-2" style="min-width: 250px;">
                <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $related->name }}</h5>
                    <p class="card-text">₹{{ number_format($related->price, 2) }}</p>
                    <a href="{{ url('detail', $related->id) }}" class="btn btn-secondary">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we fetch the product details.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            Swal.close();
        }, 500); // Close the loader after .5 seconds
    });

    document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function () {
        const rating = this.getAttribute('data-value');
        document.getElementById('ratingInput').value = rating;

        document.querySelectorAll('.star').forEach(s => {
            s.classList.remove('text-warning');
            s.classList.add('text-muted');
        });

        for (let i = 0; i < rating; i++) {
            document.querySelectorAll('.star')[i].classList.add('text-warning');
            document.querySelectorAll('.star')[i].classList.remove('text-muted');
        }
    });
});

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggle-reviews');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const hiddenReviews = document.querySelectorAll('.more-review');
                hiddenReviews.forEach(el => el.classList.toggle('d-none'));
                toggleBtn.textContent = toggleBtn.textContent.includes('More') ? 'Show Less Reviews' : 'Show More Reviews';
            });
        }
    });
</script>


@endsection
