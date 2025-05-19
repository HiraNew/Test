@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('insert'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('insert') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white fs-5">
                    <i class="fas fa-shopping-cart me-2"></i>Your Items in Cart
                </div>

                <div class="card-body p-0">
                    @if ($carts->isEmpty())
                        <div class="p-4 text-center text-muted">Your cart is currently empty. <a href="{{ route('products') }}">Continue Soping</a></div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Item Name</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($carts as $item)
                                        @php
                                            $itemTotal = $item->quantity * $item->product->price;
                                            $total += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td><img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="rounded shadow-sm"></td>
                                            <td>₹{{ $item->product->price }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ url('removeTocart', $item->product->id) }}" class="btn btn-sm btn-outline-warning">−</a>
                                                    <span>{{ $item->quantity }}</span>
                                                    <a href="{{ url('addTocart', $item->product->id) }}" class="btn btn-sm btn-outline-success">+</a>
                                                </div>
                                            </td>
                                            <td>{{ $item->product->name }}</td>
                                            <td>₹{{ $itemTotal }}</td>
                                            <td>
                                                <a href="{{ url('removeItemTocart', $item->id) }}" class="btn btn-sm btn-danger">Remove</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php $totalWithGST = $total * 1.18; @endphp

                        <div class="bg-light p-4 mt-3 border-top">
                            <h5 class="mb-3 text-center text-primary">Cart Summary</h5>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Subtotal:</strong><br>
                                        ₹{{ number_format($total, 2) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>GST (18%):</strong><br>
                                        ₹{{ number_format($total * 0.18, 2) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Total Amount:</strong><br>
                                        ₹{{ number_format($totalWithGST, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center my-4">
                            <a href="{{ url('updateAddress') }}" class="btn btn-lg btn-primary px-5 shadow-sm">
                                Proceed to Checkout
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
