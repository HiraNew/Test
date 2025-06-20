@extends('layouts.vendor')

@section('title', 'Edit Product')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Edit Product</h3>

    <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price ($)</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="{{ asset('storage/' . $product->image) }}" width="100" class="mb-2">
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Product</button>
    </form>
</div>
@endsection
