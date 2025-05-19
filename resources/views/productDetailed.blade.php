@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
        </div>

        <!-- Product Information -->
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p><strong>Size:</strong> {{ $product->size }}</p>
            <p><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
            <p><strong>Availability:</strong> {{ $product->availability ? 'In Stock' : 'Out of Stock' }}</p>
            <p><strong>Description:</strong> {!! nl2br(e($product->description)) !!}</p>

            <!-- Add to Cart Button -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Product Reviews -->
    <div class="mt-5">
        <h3>Customer Reviews</h3>
        @foreach($product->reviews as $review)
        <div class="review">
            <p><strong>{{ $review->user->name }}</strong> ({{ $review->created_at->format('d M Y') }})</p>
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                @endfor
            </div>
            <p>{{ $review->comment }}</p>
        </div>
        @endforeach
    </div>

    <!-- Related Products -->
    <div class="mt-5">
        <h3>Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/products/' . $related->image) }}" alt="{{ $related->name }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ $related->name }}</h5>
                        <p class="card-text">₹{{ number_format($related->price, 2) }}</p>
                        <a href="{{ route('product.show', $related->id) }}" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
