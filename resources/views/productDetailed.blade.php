@extends('layouts.app')

@section('title', $product->name)

@section('content')

<!-- GLightbox CSS -->
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet" />

<!-- GLightbox JS (at end of body) -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>


<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .review {
    background-color: #f8f9fa; /* Light neutral */
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.review:hover {
    background-color: #e9ecef;
}

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
    transition: all 0.3s ease;
}
.review:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
}

 .hide-scroll::-webkit-scrollbar {
        display: none;
    }

    .hide-scroll {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }

    .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 10;
    }

    .scroll-btn.left {
        left: 0;
    }

    .scroll-btn.right {
        right: 0;
    }

    .scroll-btn:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    .card-link-wrapper {
        text-decoration: none;
        color: inherit;
    }

    .card-link-wrapper:hover .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transform: scale(1.02);
        transition: 0.2s ease-in-out;
    }
    .spinner-border {
        width: 2rem;
        height: 2rem;
    }

    //big image css
    /* Always visible control buttons */
    .carousel-control-prev,
    .carousel-control-next {
        opacity: 1 !important; /* Always show */
        width: 5%; /* Adjust width as needed */
    }

    /* Style the control icons */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: green; /* Green background */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        background-size: 60% 60%;
        background-position: center;
        background-repeat: no-repeat;
        position: absolute;
    top: 10%;
    transform: translateY(-50%);
    }

    /* Left arrow icon */
    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L6.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
    }

    /* Right arrow icon */
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L9.293 7 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    }

    .card-variant {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        transition: box-shadow 0.2s ease-in-out;
    }
    .card-variant:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .color-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 3px;
    }
    .stock-text {
        font-size: 0.75rem;
        color: #28a745; /* green */
    }
    .stock-text.out-of-stock {
        color: #dc3545; /* red */
    }
    footer {
    padding-bottom: 5px; /* matches sticky-action-bar height */
    position: relative;
    z-index: 0;
}
</style>
<div class="container py-5">
    <div class="row">
    <!-- Left Side: Product Image -->
    <div class="col-md-6 mb-4">
        <div class="position-sticky" style="top: 80px; z-index: 1;">
            <div id="productImageCarousel" class="carousel slide position-relative" data-bs-ride="carousel" data-bs-interval="4000" data-bs-touch="true">
                <div class="carousel-inner">
                    @foreach($product->images as $key => $img)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            {{-- {{ asset('storage/' . $recent->image) }} --}}
                            <a href="{{ asset('storage/'.$img->image_path) }}" class="glightbox" data-gallery="product-gallery" data-title="Image {{ $key + 1 }}">
                                <img src="{{ asset('storage/'.$img->image_path) }}"
                                    class="d-block w-100 rounded border shadow-sm"
                                    style="max-height: 400px; object-fit: contain;"
                                    alt="Product Image {{ $key + 1 }}">
                            </a>
                        </div>
                    @endforeach
                    <!-- Wishlist & Share Buttons -->
                     <div class="position-absolute top-0 end-0 m-2 d-flex gap-2 z-3">
                        <button class="btn btn-light border rounded-circle p-2 wishlist-btn" data-id="{{ $product->id }}">
                            <i class="{{ in_array($product->id, $wishlistProductIds ?? []) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                        </button>
                        <button onclick="shareProduct('{{ url()->current() }}')" class="btn btn-outline-primary d-none d-sm-inline-block">
                            <i class="fas fa-share-alt me-1"></i> Share
                        </button>
                        <button class="btn btn-outline-primary d-block d-sm-none" onclick="openShareOptions('{{ urlencode(url()->current()) }}')">
                            <i class="fas fa-share-alt me-1"></i> Share
                        </button>
                    </div>
                </div>

                <!-- Carousel Controls -->
                @if($product->images->count() > 1)
                    <button class="carousel-control-prev top-50 translate-middle-y" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next top-50 translate-middle-y" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
                <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                    @foreach($product->images as $key => $img)
                        <button
                            type="button"
                            data-bs-target="#productImageCarousel"
                            data-bs-slide-to="{{ $key }}"
                            class="border rounded {{ $key == 0 ? 'border-primary' : 'border-secondary' }}"
                            style="width: 60px; height: 60px; overflow: hidden; padding: 0;"
                            aria-label="Slide {{ $key + 1 }}">
                            <img src="{{ asset('storage/'.$img->image_path) }}" alt="Thumbnail {{ $key + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <!-- Right Side: Product Info and Specifications -->
    <div class="col-md-6">
        <div class="p-4 rounded shadow-sm bg-light border mb-4">
            {{-- product-title  --}}
            <h2 class="text-primary">{{ $product->name }}</h2>
            <p class="card-text">
                        <strong class="badge bg-success me-2">{{ number_format($averageRating, 1) }} ★</strong>                         
                        <span class="badge bg-primary">{{ $product->reviews->count() }} Reviews</span>
                    </p>
            <ul class="list-unstyled mb-4">
                <li><strong class="text-dark">Seller:</strong> <span class="text-secondary">{{ $product->extra5 ?? 'DLS' }}</span></li>
                @if (isset($product->size))
                <li><strong class="text-dark">Size:</strong> <span class="text-secondary">{{ $product->size ?? 'N/A' }}</span></li>                    
                @endif
                @for ($i = 1; $i <= 5; $i++)
                    @if($product["extra$i"] === "1")
                        <li> <strong class="text-dark"> Test1: </strong> <span class="text-secondary">{{ $product["extra$i"] }}</span></li>
                    @elseif($product["extra$i"] === "2")
                        <li> <strong class="text-dark"> Test2: </strong> <span class="text-secondary"><del> {{  $product["extra$i"] }}</del></span></li>
                    @elseif($product["extra$i"] === "3")
                        <li> <strong class="text-dark"> Test3: </strong> <span class="text-secondary">{{ $product["extra$i"] }}</span></li>
                    @endif
                @endfor
                <li><span class="text-success fs-5"> <del>₹{{ number_format($product->extra4 ?? ($product->price * 1.2), 2) }}</del> :  ₹{{ number_format($product->price, 2) }}</span></li>
                <li><strong class="text-dark">Availability:</strong>
                    <span class="{{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </li>
                <li>
                    @if(isset($colorVariants) && count($colorVariants) > 0)
                        <div class="mt-4">
                            <h6 class="mb-3">More Variants</h6>

                            {{-- Mobile: Horizontal scrollable --}}
                            <div class="d-block d-sm-none">
                                <div class="d-flex overflow-auto flex-nowrap gap-3 px-2">
                                    @foreach($colorVariants as $variant)
                                        <div class="position-relative card card-variant shadow-sm" style="min-width: 140px; cursor: pointer;" onclick="window.location.href='{{ route('detail', $variant->id) }}'">
                                            @if (isset($variant->color ))
                                            <span class="color-badge">{{ ucfirst($variant->color) }}</span>
                                            @endif
                                            <img src="{{ asset('storage/'.$variant->image ?? 'images/placeholder.png') }}" class="card-img-top border-bottom" style="height: 80px; object-fit: cover;" alt="{{ $variant->name }}">
                                            <div class="card-body p-2 text-center">
                                                {{-- <p class="card-text small text-truncate mb-1">{{ $variant->name }}</p> --}}
                                                <span class="stock-text {{ $variant->quantity == 0 ? 'out-of-stock' : '' }}">
                                                    {{ $variant->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                                </span> 
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Desktop & Tablet: Grid layout --}}
                            <div class="d-none d-sm-block">
                                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                    @foreach($colorVariants as $variant)
                                        <div class="col">
                                            <div class="position-relative card card-variant h-100 shadow-sm" style="cursor: pointer;" onclick="window.location.href='{{ route('detail', $variant->id) }}'">
                                                @if (isset($variant->color ))
                                                    <span class="color-badge">{{ ucfirst($variant->color) }}</span>
                                                @endif
                                                <img src="{{ asset('storage/'.$variant->image ?? 'images/placeholder.png') }}" class="card-img-top border-bottom" style="height: 160px; object-fit: cover;" alt="{{ $variant->name }}">
                                                <div class="card-body p-2 text-center">
                                                    <p class="card-text small text-truncate mb-1">{{ $variant->name }}</p>
                                                    <span class="stock-text {{ $variant->quantity == 0 ? 'out-of-stock' : '' }}">
                                                        {{ $variant->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                     <p>No Varient Available.</p>
                    @endif
                </li>
            </ul>
            <div class="mb-4">
                <h6 class="text-dark">Description:</h6>
                <p class="text-muted">{!! nl2br(e($product->ldescription)) !!}</p>
            </div>

            @if($inCart)
                <a href="{{ route('cartView') }}" class="btn btn-outline-info w-100 py-2 fs-6">
                    <i class="fas fa-shopping-cart me-1"></i>Go to Cart
                </a>
            @else
             @if ($product->quantity > 0)
                    <form action="{{ url('addTocart', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-gradient w-100 py-2 fs-6">
                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                        </button>
                    </form>
                @else
                   <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="fas fa-ban me-1"></i>Out of Stock
                    </button>
                @endif                
            @endif
        </div>

        <!-- Specification Section -->
        <div class="p-4 rounded shadow-sm bg-white border">
            <h4 class="text-dark">Specifications</h4>
            <ul class="list-unstyled mt-3 text-muted">
                <li><strong>Material:</strong> {{ $product->material ?? 'Not specified' }}</li>
                <li><strong>Brand:</strong> {{ $product->brand ?? 'Not specified' }}</li>
                <li><strong>Color:</strong> {{ $product->color ?? 'Not specified' }}</li>
                <li><strong>Weight:</strong> {{ $product->weight ?? 'Not specified' }}</li>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis unde eius corporis non tenetur labore neque, asperiores natus assumenda accusantium quo et. Facilis enim nam consequuntur reiciendis est quas autem delectus ad qui fuga ipsa corrupti, praesentium distinctio ea inventore laborum nulla eveniet optio debitis, officiis.
                <!-- Add more fields as needed -->

                
                    <!-- Product Reviews -->
                    <div class="mt-5">

                        @if($averageRating > 0)
                    <div class="mb-3">
                    </div>
                        <div class="card mb-4">
                    <div class="card-body">
                    <h5 class="card-title">Rating & Reviews</h5>
                    <div class="d-flex align-items-center">
                        @php
                            $avgRating = round($averageRating, 1); // e.g., 4.3
                            $filledStars = floor($avgRating);      // e.g., 4
                            $halfStar = ($avgRating - $filledStars) >= 0.25 && ($avgRating - $filledStars) < 0.75;
                            $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);
                        @endphp

                        {{-- Full stars --}}
                        @for($i = 0; $i < $filledStars; $i++)
                            <i class="fa fa-star text-success"></i>
                        @endfor

                        {{-- Half star --}}
                        @if($halfStar)
                            <i class="fa fa-star-half-alt text-success"></i>
                        @endif

                        {{-- Empty stars --}}
                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="far fa-star text-muted"></i>
                        @endfor

                        {{-- Optional rating display --}}
                        <span class="ms-2 {{ $avgRating < 3 ? 'text-danger' : 'text-secondary' }}">({{ $avgRating }}/5)</span>
                    </div>

                    <p class="card-text">
                        <strong>{{ number_format($averageRating, 1) }} / 5</strong> 
                        <span class="badge bg-primary">{{ $product->reviews->count() }} Reviews</span>
                    </p>
                </div>

                    </div>

                    @else
                        <p class="text-danger">No ratings yet.</p>
                    @endif


                        {{-- @auth --}}
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
                        {{-- @endauth --}}
                        
                        
                    @php
                            $totalReviews = $product->reviews->count();
                        @endphp

                        <div class="mt-4">
                            <h5 class="mb-3">Customer Reviews <span class="badge bg-primary">{{ $totalReviews }}</span></h5>

                            <div id="review-container">
                                @foreach($product->reviews as $index => $review)
                                @php
                                    $userVote = $review->userVote;
                                @endphp
                                <div class="review p-3 rounded mb-3 bg-white shadow-sm border {{ $index >= 3 ? 'd-none extra-review' : '' }}" data-index="{{ $index }}">
                                    @if (isset($review->user->name))                                        
                                    <p class="mb-1"><strong>{{ $review->user->name }}</strong></p>
                                    @endif
                                    <div class="d-flex align-items-start justify-content-between stars mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="{{ $review->rating == '1' ? 'badge bg-danger me-2' : 'badge bg-success me-2' }}">{{ $review->rating }}★</span>
                                            <strong class="{{ $review->rating == '1' ? 'badge text-danger me-2' : '' }} fs-6">
                                                {{ ucfirst(
                                                    match((int)$review->rating) {
                                                        5 => 'Super',
                                                        4 => 'Good choice',
                                                        3 => 'Good',
                                                        2 => 'Recommended',
                                                        default => 'Not recommended at all'
                                                    }
                                                ) }}
                                            </strong>
                                        </div>
                                    </div>
                                    <p class="text-muted">{{ $review->review }}</p>

                                    <div class="mt-2 d-flex align-items-center gap-3 text-secondary vote-section">
                                        <span>👍 <span id="likes-{{ $review->id }}">{{ $review->likes_count }}</span></span>
                                        <span>👎 <span id="dislikes-{{ $review->id }}">{{ $review->dislikes_count }}</span></span>
                                        @auth
                                        @if (!$review->userVote)
                                            <button class="btn btn-outline-success btn-sm vote-btn" data-review-id="{{ $review->id }}" data-vote="like">Like</button>
                                            <button class="btn btn-outline-danger btn-sm vote-btn" data-review-id="{{ $review->id }}" data-vote="dislike">Dislike</button>
                                        @elseif ($review->userVote->vote === 'like')
                                            <button class="btn btn-success btn-sm vote-btn" data-review-id="{{ $review->id }}" data-vote="dislike">Liked (Undo or Switch)</button>
                                        @elseif ($review->userVote->vote === 'dislike')
                                            <button class="btn btn-danger btn-sm vote-btn" data-review-id="{{ $review->id }}" data-vote="like">Disliked (Undo or Switch)</button>
                                        @endif
                                        @endauth
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($totalReviews > 3)
                            <div class="text-center">
                                <button class="btn btn-outline-primary" id="toggle-reviews" data-state="first">Show More Reviews</button>
                                <div id="review-spinner" class="spinner-border text-primary mt-3 d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
            </ul>
        </div>
    </div>
</div>



    <!-- Related Products -->
    @if($relatedProducts->count())
    <div class="mt-5">
        <h3>Related Products</h3>
        <div class="position-relative">
            <!-- Left Arrow -->
            <button class="scroll-btn left" onclick="scrollRelated(-1)">
                &#9664;
            </button>

            <!-- Scrollable container -->
            <div id="related-scroll" class="d-flex overflow-auto hide-scroll" style="scroll-behavior: smooth;">
                @foreach($relatedProducts as $related)
                    <a href="{{ url('detail', $related->id) }}" class="card-link-wrapper">
                        <div class="card mx-2" style="min-width: 250px;">
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">{{ $related->name }}</h5>
                                <p class="card-text">₹{{ number_format($related->price, 2) }}</p>

                                @if($related->averageRating > 0)
                                    <div class="d-flex align-items-center">
                                        @php
                                            $avgRating = round($related->averageRating, 1);
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.25 && ($avgRating - $fullStars) < 0.75;
                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        @endphp

                                        {{-- Full stars --}}
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <i class="fa fa-star text-success"></i>
                                        @endfor

                                        {{-- Half star --}}
                                        @if ($hasHalfStar)
                                            <i class="fa fa-star-half-alt text-success"></i>
                                        @endif

                                        {{-- Empty stars --}}
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="far fa-star text-muted"></i>
                                        @endfor

                                        <small class="ms-1 text-muted">({{ number_format($avgRating, 1) }})</small>
                                    </div>
                                @else
                                    <small class="text-muted">No ratings yet</small>
                                @endif
                            </div>


                        </div>
                    </a>
                    
                @endforeach
            </div>

            <!-- Right Arrow -->
            <button class="scroll-btn right" onclick="scrollRelated(1)">
                &#9654;
            </button>
        </div>
    </div>
    @endif
    @if($recentlyViewedProducts->count())
    {{-- @dd($recentlyViewedProducts->count()); --}}
    <div class="mt-5">
        <h3>Recently Viewed Products</h3>
        <div class="position-relative">
            <!-- Left Arrow -->
            <button class="scroll-btn left" onclick="scrollRecently(-1)">
                &#9664;
            </button>

            <!-- Scrollable container -->
            <div id="recent-scroll" class="d-flex overflow-auto hide-scroll" style="scroll-behavior: smooth;">
                @foreach($recentlyViewedProducts as $recent)
                    <a href="{{ url('detail', $recent->id) }}" class="card-link-wrapper">
                        <div class="card mx-2" style="min-width: 250px;">
                            {{-- {{ asset('storage/' . $recent->image) }} --}}
                            <img src="{{ asset('storage/' . $recent->image ?? $recent->images->first()->image_path ?? 'placeholder.jpg') }}" alt="{{ $recent->name }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">{{ $recent->name }}</h5>
                                <p class="card-text">₹{{ number_format($recent->price, 2) }}</p>

                                @if($recent->averageRating > 0)
                                    <div class="d-flex align-items-center">
                                        @php
                                            $avgRating = round($recent->averageRating, 1);
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.25 && ($avgRating - $fullStars) < 0.75;
                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        @endphp

                                        {{-- Full stars --}}
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <i class="fa fa-star text-success"></i>
                                        @endfor

                                        {{-- Half star --}}
                                        @if ($hasHalfStar)
                                            <i class="fa fa-star-half-alt text-success"></i>
                                        @endif

                                        {{-- Empty stars --}}
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="far fa-star text-muted"></i>
                                        @endfor

                                        <small class="ms-1 text-muted">({{ number_format($avgRating, 1) }})</small>
                                    </div>
                                @else
                                    <small class="text-muted">No ratings yet</small>
                                @endif
                            </div>


                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Right Arrow -->
            <button class="scroll-btn right" onclick="scrollRecently(1)">
                &#9654;
            </button>
        </div>
    </div>
 @endif
 </div>
 @include('components.footer')
 {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

 {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
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

        function scrollRelated(direction) {
        const container = document.getElementById('related-scroll');
        const scrollAmount = 300; // pixels

        container.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('toggle-reviews');
        const spinner = document.getElementById('review-spinner');
        const reviews = document.querySelectorAll('.extra-review');

        button.addEventListener('click', () => {
            const state = button.getAttribute('data-state');

            button.classList.add('d-none');
            spinner.classList.remove('d-none');

            setTimeout(() => {
                if (state === 'initial') {
                    // Show 5 more
                    for (let i = 3; i < 8 && i < reviews.length + 3; i++) {
                        document.querySelector(`[data-index="${i}"]`)?.classList.remove('d-none');
                    }
                    button.setAttribute('data-state', 'partial');
                    button.textContent = 'Show All Reviews';
                } else if (state === 'partial') {
                    // Show all remaining
                    reviews.forEach(r => r.classList.remove('d-none'));
                    button.setAttribute('data-state', 'full');
                    button.textContent = 'Show Less Reviews';
                } else {
                    // Reset to show only first 3
                    reviews.forEach(r => r.classList.add('d-none'));
                    button.setAttribute('data-state', 'initial');
                    button.textContent = 'Show More Reviews';
                }

                spinner.classList.add('d-none');
                button.classList.remove('d-none');
            }, 1500);
        });
    });

    // for like display review start
        document.addEventListener('DOMContentLoaded', function () {
        // Like button
        document.querySelectorAll('.like-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                let id = this.getAttribute('data-id');
                fetch(`/review/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById(`like-count-${id}`).textContent = data.likes;
                });
            });
        });

        // Dislike button
        document.querySelectorAll('.dislike-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                let id = this.getAttribute('data-id');
                fetch(`/review/${id}/dislike`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById(`dislike-count-${id}`).textContent = data.dislikes;
                });
            });
        });
    });

        document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.vote-btn').forEach(button => {
            button.addEventListener('click', async function () {
                const reviewId = this.getAttribute('data-review-id');
                const voteType = this.getAttribute('data-vote');

                try {
                    const response = await fetch('/review/vote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            review_id: reviewId,
                            vote: voteType
                        })
                    });

                    const data = await response.json();

                    // Update likes/dislikes counts
                    document.getElementById(`likes-${reviewId}`).innerText = data.likes;
                    document.getElementById(`dislikes-${reviewId}`).innerText = data.dislikes;

                    // Replace buttons based on current user vote
                    const parent = this.closest('.mt-2');
                    parent.querySelectorAll('.vote-btn').forEach(btn => btn.remove());

                    let newButtons = '';
                    if (data.user_vote === 'like') {
                        newButtons = `
                            <button class="btn btn-success btn-sm vote-btn" data-review-id="${reviewId}" data-vote="dislike">Liked (Undo or Switch)</button>
                        `;
                    } else if (data.user_vote === 'dislike') {
                        newButtons = `
                            <button class="btn btn-danger btn-sm vote-btn" data-review-id="${reviewId}" data-vote="like">Disliked (Undo or Switch)</button>
                        `;
                    } else {
                        newButtons = `
                            <button class="btn btn-outline-success btn-sm vote-btn" data-review-id="${reviewId}" data-vote="like">Like</button>
                            <button class="btn btn-outline-danger btn-sm vote-btn" data-review-id="${reviewId}" data-vote="dislike">Dislike</button>
                        `;
                    }

                    parent.insertAdjacentHTML('beforeend', newButtons);
                    // Re-bind new buttons
                    document.querySelectorAll('.vote-btn').forEach(btn => btn.addEventListener('click', arguments.callee));
                } catch (error) {
                    console.error('Voting failed:', error);
                }
            });
        });
    });

     $(window).on('pageshow', function (event) {
        if (event.originalEvent.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
            // Force full reload to get fresh state
            window.location.reload(true);
        }
    });
    // for like dislike review end
    function scrollRecently(direction) {
        const container = document.getElementById('recent-scroll');
        const scrollAmount = 300;
        container.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
    $(document).ready(function () {
    // Wishlist toggle
    $(document).on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('id');
        const icon = btn.find('i');

        $.ajax({
            url: '{{ route("wishlist.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === 'added') {
                    icon.removeClass('far').addClass('fas');
                } else if (response.status === 'removed') {
                    icon.removeClass('fas').addClass('far');
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Please Login First',
                    timer: 2000,
                    showConfirmButton: false,
                });
                setTimeout(() => window.location.href = '/login', 2000);
            }
        });
    });

    // Share functionality
    window.shareProduct = function (url) {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: url
            })
            .then(() => console.log('Shared successfully'))
            .catch((error) => {
                console.error('Error sharing:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Sharing failed',
                    text: error.message || 'Please try again.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        } else if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Product link copied!',
                    timer: 1500,
                    showConfirmButton: false,
                });
            })
            .catch((error) => {
                console.error('Clipboard write failed:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Copy failed',
                    text: error.message || 'Please copy manually.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Clipboard not supported',
                text: 'Please copy the URL manually.',
                timer: 2000,
                showConfirmButton: false,
            });
        }
    };

    });
    function shareProduct(url) {
    const fallbackInput = () => {
        Swal.fire({
            title: 'Copy Product Link',
            html: `<input type="text" value="${url}" id="shareInput" class="swal2-input" readonly onfocus="this.select()">`,
            showConfirmButton: false,
            didOpen: () => {
                const input = document.getElementById('shareInput');
                input.focus();
                input.select();
            }
        });
    };

    // Optional: Google Analytics tracking
    if (typeof gtag === "function") {
        gtag('event', 'share_product', {
            event_category: 'engagement',
            event_label: url,
        });
    }

    if (navigator.share && window.innerWidth < 768) {
        // Mobile or small screen with native share support
        navigator.share({
            title: document.title,
            url: url
        }).then(() => {
            console.log('Shared successfully');
        }).catch((error) => {
            console.warn('Native share failed:', error);
            fallbackInput();
        });
    } else if (navigator.clipboard && navigator.clipboard.writeText) {
        // Clipboard fallback
        navigator.clipboard.writeText(url)
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link copied to clipboard!',
                timer: 1500,
                showConfirmButton: false,
            });
        })
        .catch((error) => {
            console.error('Clipboard write failed:', error);
            fallbackInput();
        });
    } else {
        // Manual fallback
        fallbackInput();
    }
}


</script>
<script>
function openShareOptions(encodedUrl) {
    const decodedUrl = decodeURIComponent(encodedUrl);
    const text = encodeURIComponent(document.title);

    const shareLinks = `
        <div class="d-flex flex-column align-items-start gap-2">
            <button class="btn btn-sm btn-success w-100" onclick="shareProduct('${decodedUrl}')">
                📱 Native Share / Copy
            </button>
            <a href="https://wa.me/?text=${text}%20${encodedUrl}" target="_blank" class="btn btn-sm btn-success w-100">
                🟢 WhatsApp
            </a>
            <a href="https://twitter.com/intent/tweet?text=${text}&url=${encodedUrl}" target="_blank" class="btn btn-sm btn-info w-100">
                🔵 Twitter
            </a>
            <a href="https://t.me/share/url?url=${encodedUrl}&text=${text}" target="_blank" class="btn btn-sm btn-primary w-100">
                🔷 Telegram
            </a>
        </div>
    `;

    Swal.fire({
        title: 'Share This Product',
        html: shareLinks,
        showConfirmButton: false,
        showCloseButton: true,
        width: 350
    });
}

function shareProduct(url) {
    if (navigator.share && window.innerWidth < 768) {
        navigator.share({
            title: document.title,
            url: url
        }).then(() => {
            console.log('Shared successfully');
        }).catch((err) => {
            fallbackCopy(url);
        });
    } else {
        fallbackCopy(url);
    }
}

function fallbackCopy(url) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Link copied to clipboard!',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch((error) => {
                console.error('Clipboard failed:', error);
                Swal.fire({
                    icon: 'info',
                    title: 'Copy failed',
                    html: `<input value="${url}" readonly class="swal2-input" onfocus="this.select()">`,
                    showConfirmButton: false
                });
            });
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Copy not supported',
            html: `<input value="${url}" readonly class="swal2-input" onfocus="this.select()">`,
            showConfirmButton: false
        });
    }
}

 document.addEventListener("DOMContentLoaded", function() {
        const lightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            zoomable: true,
        });
    });
</script>
@endsection
