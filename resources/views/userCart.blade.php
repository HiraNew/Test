@extends('layouts.app')

@section('content')
<style>
    @media (max-width: 575.98px) {
        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 0.75rem;
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #495057;
        }

        .table tbody img {
            max-width: 60px;
        }
    }
</style>

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
                        <div class="table-responsive-sm">
                            <table class="table table-bordered align-middle mb-0 w-100">

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
                                            <td data-label="Image"><img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="rounded shadow-sm"></td>
                                            <td data-label="Price">₹{{ $item->product->price }}</td>
                                            <td data-label="Quantity">
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ url('removeTocart', $item->product->id) }}" class="btn btn-sm btn-outline-warning">−</a>
                                                    <span>{{ $item->quantity }}</span>
                                                    <a href="{{ url('addTocart', $item->product->id) }}" class="btn btn-sm btn-outline-success">+</a>
                                                </div>
                                            </td>
                                            <td data-label="Item Name">{{ $item->product->name }}</td>
                                            <td data-label="Total">₹{{ $itemTotal }}</td>
                                            <td data-label="Action">
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
