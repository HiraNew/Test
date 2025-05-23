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
                        <div class="p-4 text-center text-muted">Your cart is currently empty. <a href="{{ route('products') }}">Continue Shoping.</a></div>
                    @else
                        <div class="table-responsive-sm">
                            <table class="table table-bordered align-middle mb-0 w-100">

                                
                                <tbody>
                                   @php
                                        $total = 0;
                                        $totalGST = 0;
                                    @endphp

                                    @foreach ($carts as $item)
                                    {{-- @dd($item->product_id); --}}
                                        @php
                                            $itemTotal = $item->quantity * $item->product->price;
                                            $total += $itemTotal;

                                            // Apply GST only if not Fruits
                                            $itemGST = 0;
                                            if ($item->product->category->name !== 'Fruits') {
                                                $itemGST = $itemTotal * 0.18;
                                                $totalGST += $itemGST;
                                            }
                                        @endphp

                                        <tr onclick="showLoaderAndRedirect('{{ url('detail', $item->product_id) }}')" class="cursor-pointer hover:bg-gray-100 transition">
                                            <td data-label="Image" class="text-center py-2">
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="w-40 sm:w-20 md:w-16 h-auto mx-auto rounded shadow-md">
                                            </td>
                                            <td data-label="Price" class="text-center py-2">₹{{ $item->product->price }}</td>
                                            <td data-label="Quantity" class="text-center py-2">
                                                <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation()">
                                                    <a href="{{ url('removeTocart', $item->product->id) }}" class="btn btn-sm btn-outline-warning">−</a>
                                                    <span>{{ $item->quantity }}</span>
                                                    <a href="{{ url('addTocart', $item->product->id) }}" class="btn btn-sm btn-outline-success">+</a>
                                                </div>
                                            </td>
                                            <td data-label="Item Name" class="text-center py-2">{{ $item->product->name }}</td>
                                            <td data-label="Action" class="text-center py-2">
                                                <a href="{{ url('removeItemTocart', $item->id) }}" class="btn btn-sm btn-danger" onclick="event.stopPropagation()">Remove</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- @php
    $totalWithGST = ($item->product->category->name === 'Fruits') ? 0 : $total * 1.18;
@endphp --}}


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
                                        <strong>GST (18% on non-Fruits):</strong><br>
                                        ₹{{ number_format($totalGST, 2) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Total Amount:</strong><br>
                                        ₹{{ number_format($total + $totalGST, 2) }}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLoaderAndRedirect(url) {
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we take you to the product details.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            window.location.href = url;
        }, 1500); // 1.5 seconds delay
    }
</script>

@endsection
