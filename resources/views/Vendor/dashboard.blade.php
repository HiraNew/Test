@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
    <h2>Welcome, {{ Auth::guard('vendor')->user()->name }}</h2>
    <p class="text-muted">Here’s an overview of your store performance.</p>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <h3>{{ $productCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3>{{ $orderCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <h3>{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Total Earnings</h5>
                    <h3>${{ number_format($earnings, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Recent Orders</h5>
        </div>
        <div class="card-body p-0">
            @if($recentOrders->isEmpty())
                <p class="text-center m-3">No recent orders.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $payment)
                                <tr>
                                    <td>{{ $payment->orderid }}</td>
                                    <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->product->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($payment->status == 'pending') bg-warning
                                            @elseif($payment->status == 'delivered') bg-success
                                            @else bg-secondary @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->created_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                                    @if(!in_array($payment->status, ['pending', 'confirmed', 'shipped']))
                                    <td>{{ $payment->updated_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                                                                        
                                    @else
                                    <td>No Delivered Yet.</td>
                                    @endif
                                </tr>
                                @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Optional: Recent Products --}}
    <div class="card">
        <div class="card-header">
            <h5>Latest Products</h5>
        </div>
        <div class="card-body p-0">
            @if($recentProducts->isEmpty())
                <p class="text-center m-3">No products added yet.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($recentProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $product->name }}</span>
                            <span class="badge bg-primary">${{ number_format($product->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
