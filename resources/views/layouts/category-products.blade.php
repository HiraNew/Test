@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container mb-4">
            <h5 class="mb-3 fw-bold">You Might Also Interested.</h5>
            {{-- Categories --}}
    <div class="row justify-content-center mt-3 mb-5"> {{-- Added mt-3 --}}
        <div class="col-auto">
            <div class="card shadow-sm border-0 p-3 bg-primary text-center">
                <h5 class="mb-3 fw-bold text-white">Look into Categories</h5>
                <div class="row row-cols-auto justify-content-center g-3">
                    @foreach($categories->take(7) as $category)
                        <div class="col">
                            <a href="{{ route('category.view', $category->slug) }}" class="text-decoration-none text-dark d-flex flex-column align-items-center">
                                <div class="rounded-circle border bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                                    <img src="{{ url($category->icon) }}" alt="{{ $category->name }}" class="img-fluid" style="width: 28px; height: 28px; object-fit: contain;">
                                </div>
                                {{-- <small class="mt-2 text-truncate fw-bold" style="max-width: 70px;">{{ $category->name }}</small> --}}
                            </a>
                        </div>
                    @endforeach

                    @if($categories->count() > 7)
                        <div class="col">
                            <a href="#" class="text-decoration-none text-dark d-flex flex-column align-items-center">
                                <div class="rounded-circle border bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-th-large fs-5 text-secondary"></i>
                                </div>
                                <small class="mt-2 fw-bold">All</small>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        </div>
    {{-- <h4 class="fw-bold mb-3">{{ $category->name }}</h4> --}}
    
    <div class="row">
        @forelse ($Products as $Product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ url($Product->image) }}" class="card-img-top" alt="{{ $Product->name }}">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $Product->name }}</h6>
                        <p class="mb-1 text-primary">₹{{ number_format($Product->price, 2) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No products found in this category.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $Products->links() }}
    </div>
</div>
@endsection
