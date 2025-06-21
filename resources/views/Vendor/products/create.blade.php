@extends('layouts.vendor')

@section('title', $product->exists ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container py-4">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <h3 class="mb-4">{{ $product->exists ? 'Edit Product' : 'Add New Product' }}</h3>

    <form action="{{ $product->exists ? route('vendor.products.update', $product->id) : route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" required value="{{ old('slug', $product->slug) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sub-Category</label>
                <select name="subcategory_id" class="form-select" required>
                    <option value="">-- Select Sub-Category --</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-control" step="0.01" required value="{{ old('price', $product->price) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="0" required value="{{ old('quantity', $product->quantity) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Main Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail mt-2" width="100">
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label">Extra Images</label>
                <input type="file" name="extra_images[]" class="form-control" accept="image/*" multiple>

                @if (isset($extraImages) && count($extraImages))
                    <div class="row mt-3">
                        @foreach ($extraImages as $item)
                        {{-- @dd($item->id) --}}
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 text-center mb-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="img-fluid rounded shadow" alt="Extra Image">

                                    {{-- <form action="{{ route('vendor.products.images.destroy', $item->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


            <div class="col-md-6">
                <label class="form-label">Product Return Days <small>(1–10)</small></label>
                <input type="number" name="extra1" class="form-control" min="0" max="10" value="{{ old('extra1', $product->extra1) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Size</label>
                <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Weight</label>
                <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Short Description</label>
                <textarea name="sdescription" class="form-control" rows="3" required>{{ old('sdescription', $product->sdescription) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Long Description</label>
                <textarea name="ldescription" class="form-control" rows="5" required>{{ old('ldescription', $product->ldescription) }}</textarea>
            </div>

            <div class="col-12">
                <hr>
                <h5>Optional Fields</h5>
            </div>

            <div class="col-md-6">
                <label class="form-label">Optional Field 1</label>
                <input type="text" name="feild1" class="form-control" value="{{ old('feild1', $product->feild1) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Optional Field 2</label>
                <input type="text" name="feild2" class="form-control" value="{{ old('feild2', $product->feild2) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Optional Field 3</label>
                <input type="text" name="feild3" class="form-control" value="{{ old('feild3', $product->feild3) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Optional Field 4</label>
                <input type="text" name="feild4" class="form-control" value="{{ old('feild4', $product->feild4) }}">
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary w-100">
                    {{ $product->exists ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
