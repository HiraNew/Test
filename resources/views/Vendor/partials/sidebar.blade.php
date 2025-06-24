
{{-- @dd(Auth::guard('vendor')->user()->name) --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<h4 class="text-center"><i class="bi bi-person-badge-fill"></i> Vendor</h4>
<hr>

<a href="{{ route('vendor.dashboard') }}">
    <i class="bi bi-speedometer2 me-2"></i> Dashboard
</a>
{{-- {{ route('vendor.products.index') }} --}}
<a href="{{url('/vendor/products')}}">
    <i class="bi bi-box-seam me-2"></i> Products
    @if($newProductNotifications > 0)
        <span class="badge bg-danger float-end">{{ $newProductNotifications }}</span>
    @endif
</a>
{{-- {{ route('vendor.orders.index') }} --}}
<a href="{{ route('vendor.orders.index') }}">
    <i class="bi bi-bag-check me-2"></i> Orders
    @if($newOrderNotifications > 0)
        <span class="badge bg-danger float-end">{{ $newOrderNotifications }}</span>
    @endif
</a>

{{-- {{ route('vendor.payouts.index') }} --}}
<a href="#">
    <i class="bi bi-cash-coin me-2"></i> Payouts
</a>

{{-- {{ route('vendor.settings') }} --}}
<a href="#">
    <i class="bi bi-gear-fill me-2"></i> Store Settings
</a>

<a href="{{ route('vendor.logout') }}"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="bi bi-box-arrow-right me-2"></i> Logout
</a>

<form id="logout-form" action="{{ route('vendor.logout') }}" method="POST" class="d-none">
    @csrf
</form>
    

