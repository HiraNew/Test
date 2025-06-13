@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animated fadeInDown shadow-sm mt-3 text-center" role="alert" style="font-size: 1.1rem; position: relative;">
    <div class="d-flex flex-column align-items-center">
        <div class="mb-2">
            <i class="fas fa-check-circle" style="font-size: 14.5rem; color: #28a745;"></i>
        </div>
        <strong>🎉 {{ session('success') }}</strong>
        <a href="{{route('payments.index')}}" class="btn btn-outline-light btn-sm mt-3 px-4 py-2 rounded-pill shadow-sm">
            🚚 Track Your Order
        </a>
    </div>
</div>

<script>
    // Hide alert after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.style.display = 'none';
    }, 5000);

    // Redirect after 6 seconds
    setTimeout(() => {
        window.location.href = "{{ route('payments.index') }}";
    }, 6000);
</script>
@endif
@endsection
