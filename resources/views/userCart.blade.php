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
            @if(session('stockMessages'))
                @foreach(session('stockMessages') as $msg)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ $msg }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endforeach
            @endif

            @if(!empty($stockMessages))
                @foreach($stockMessages as $msg)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ $msg }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endforeach
            @endif


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
                                    @foreach ($cartDetails as $item)
                                    {{-- @dd($item['qty']) --}}
                                    {{-- @dd($item); --}}
                                        <tr onclick="showLoaderAndRedirect('{{ url('detail', $item['product_id']) }}')" class="cursor-pointer hover:bg-gray-100 transition">
                                            <td data-label="Image" class="text-center py-2">
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['product_name'] }}" width="60" class="w-40 sm:w-20 md:w-16 h-auto mx-auto rounded shadow-md">
                                            </td>
                                            <td data-label="Price" class="text-center py-2">₹{{ $item['base_price'] }}</td>
                                            <td data-label="Quantity" class="text-center py-2">
                                                <div class="d-flex align-items-center gap-2" onclick="event.stopPropagation()">
                                                    <a href="{{ url('removeTocart', $item['product_id']) }}" class="btn btn-sm btn-outline-warning">−</a>
                                                    <span>{{ $item['qty'] }}</span>
                                                    <a href="{{ url('addTocart', $item['product_id']) }}" class="btn btn-sm btn-outline-success">+</a>
                                                </div>
                                                @if($item['stock_exceeded'])
                                                    <div class="text-danger small mt-1">Only {{ $item['available_stock'] }} in stock</div>
                                                @endif
                                            </td>

                                            <td data-label="Item Name" class="text-center py-2">{{ $item['product_name'] }}</td>
                                            <td data-label="Charges" class="text-center py-2">
                                                @if(!empty($item['extra_charges']))
                                                    <ul class="list-unstyled mb-0 small text-start">
                                                        @foreach($item['extra_charges'] as $type => $amount)
                                                            <li><strong>{{ ucfirst(str_replace('_', ' ', $type)) }}:</strong> ₹{{ number_format($amount, 2) }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-success small">No Extra Charges</span>
                                                @endif
                                            </td>
                                            <td data-label="Total" class="text-center py-2">
                                                ₹{{ number_format($item['total_with_charges'], 2) }}
                                            </td>
                                            <td data-label="Action" class="text-center py-2">
                                                <a href="{{ url('removeItemTocart', $item['cart_id']) }}" class="btn btn-sm btn-danger" onclick="event.stopPropagation()">Remove</a>
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
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Subtotal:</strong><br>
                                        ₹{{ number_format($cartSummary['total_product_amount'], 2) }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Extra Charges:</strong><br>
                                        ₹{{ number_format($cartSummary['total_extra_charges'], 2) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 shadow-sm bg-white">
                                        <strong>Total Amount:</strong><br>
                                        ₹{{ number_format($cartSummary['grand_total'], 2) }}
                                    </div>
                                </div>
                            </div>

                            @if($cartSummary['user_exempt'])
                                <p class="text-center text-muted mt-2"><em>No extra charges applied (Exempt User)</em></p>
                            @endif
                        </div>



                        <div class="d-flex justify-content-center my-4">
                            @if($cartSummary['has_stock_issue'])
                                <button class="btn btn-lg btn-secondary px-5 shadow-sm" disabled>
                                    Out Of Stock
                                </button>
                            @else
                                <a href="{{ url('updateAddress') }}" class="btn btn-lg btn-primary px-5 shadow-sm">
                                    Proceed to Checkout
                                </a>
                            @endif
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
