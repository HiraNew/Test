@extends('layouts.vendor')

@section('title', 'My Products')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>My Products</h3>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Available</th>
                    <th>Return Period</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            {{-- <img src="{{ Storage::url($product->image) ? url($product->image) : '' }}" alt="{{ $product->name }}" width="60"> --}}
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="60">

                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            {{-- <form method="POST" action="{{ route('vendor.products.toggleStatus', $product->id) }}">
                                @csrf
                                @method('PATCH') --}}
                                <button type="submit" class="btn btn-sm {{ $product->status === 'active' ? 'text-success' : 'bg-secondary' }}">
                                    {{-- <span class="{{ $product->status === 'active' ? 'text-success' : 'text-secondary' }}"> --}}
                                        {{ $product->status === 'active' ? 'Live' : 'Inactive' }}
                                    {{-- </span> --}}

                                </button>
                            {{-- </form> --}}
                        </td>
                        <td>{{$product->quantity}}</td>
                        <td>{{$product->extra1}}</td>
                        <td>{{ $product->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('vendor.products.destroy', $product->id) }}" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
