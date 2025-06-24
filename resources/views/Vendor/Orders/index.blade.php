@extends('layouts.vendor')

@section('content')
<div class="container mt-4">
    <h3>Your Orders</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $payment)
            <tr>
                <td>{{ $payment->orderid }}</td>
                <td>{{ $payment->product->name ?? 'N/A' }}</td>
                <td>{{ $payment->qty }}</td>
                <td>₹{{ $payment->amount }}</td>
                <td>
                    <span class="badge bg-{{ $payment->status == 'pending' ? 'warning' : ($payment->status == 'confirmed' ? 'info' : 'success') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>
                    @if($payment->status === 'pending')
                        <form action="{{ route('vendor.orders.confirm', $payment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-primary">Confirm</button>
                        </form>
                    @elseif($payment->status === 'confirmed')
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#shipModal" 
                            data-id="{{ $payment->id }}">Ship Order</button>
                    @else
                        <span class="text-success">Shipped</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Ship Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" aria-labelledby="shipModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('vendor.orders.ship') }}">
      @csrf
      <input type="hidden" name="payment_id" id="modal_payment_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Enter Delivery Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="delivery_partner_id" class="form-label">Delivery Partner</label>
                <select name="delivery_partner_id" class="form-select" required>
                    <option value="">Select Delivery Partner</option>
                    @foreach($deliveryPartners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="delivery_partner_contact" class="form-label">Contact Number</label>
                <input type="text" name="feild1" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ship Order</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
    const shipModal = document.getElementById('shipModal');
    shipModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const paymentId = button.getAttribute('data-id');
        document.getElementById('modal_payment_id').value = paymentId;
    });
</script>
@endsection
