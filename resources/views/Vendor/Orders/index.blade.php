@extends('layouts.vendor')

@section('content')
<div class="container mt-4">
    <h3>Your Orders</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Delivered Date</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $payment)
            <tr class="order-row" data-payment='@json($payment)'>
                <td>{{ $payment->orderid }}</td>
                <td>{{ $payment->created_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                @if(!in_array($payment->status, ['pending', 'confirmed', 'shipped']))
                <td>{{ $payment->updated_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                                                                        
                @else
                <td>No Delivered Yet.</td>
                @endif
                <td>{{ $payment->product->name ?? 'N/A' }}</td>
                <td>{{ $payment->qty }}</td>
                <td>₹{{ $payment->amount }}</td>
                <td>
                    <span class="badge bg-{{ $payment->status == 'pending' || $payment->status == 'cancelled'  ? 'warning' : ($payment->status == 'confirmed' ? 'info' : 'success') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>
                    @if($payment->status === 'pending')
                        <form action="{{ route('vendor.orders.confirm', $payment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-primary">Confirm</button>
                        </form>
                        <button class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-id="{{ $payment->id }}"
                                    data-bs-target="#cancelModal">
                                Cancel
                            </button>
                    @elseif($payment->status === 'confirmed')
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#shipModal" 
                            data-id="{{ $payment->id }}">Ship Order</button>
                        <button class="btn btn-sm btn-warning" 
                                data-bs-toggle="modal" 
                                data-id="{{ $payment->id }}"
                                data-bs-target="#cancelModal">
                            Cancel
                        </button>
                    @elseif ($payment->status !== 'delivered' && $payment->status !== 'cancelled')
                      <button class="btn btn-sm btn-warning" 
                              data-bs-toggle="modal" 
                              data-id="{{ $payment->id }}"
                              data-bs-target="#cancelModal">
                          Cancel
                      </button>

                    @else
                        @if ($payment->status === 'cancelled')
                        <span class="text-primary">Item Cancelled No Action Reuired</span>
                        @elseif ($payment->status === 'delivered')
                        <span class="text-primary">Item Delivered No Action Reuired</span>
                        @endif
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
      <input type="hidden" value="" name="payment_id" id="modal_payment_id">
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

<!-- Address & User Info Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="addressModalBody">
        <h6>User Info</h6>
        <p><strong>Name:</strong> <span id="modal_user_name"></span></p>
        <p><strong>Email:</strong> <span id="modal_user_email"></span></p>
        <p><strong>Phone:</strong> <span id="modal_user_phone"></span></p>

        <hr>
        <h6>Shipping Address</h6>
        <p><strong>Address:</strong> <span id="modal_address_full"></span></p>
        <p><strong>Landmark:</strong> <span id="modal_address_landmark"></span></p>
        <p><strong>Phone:</strong> <span id="modal_address_phone"></span></p>
        <p><strong>Pincode/Postal Code:</strong> <span id="modal_address_pincode"></span></p>
        <p><strong>Alt Mobile Numer:</strong> <span id="modal_address_alt"></span></p>       
      </div>
    </div>
  </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    {{-- {{ route('vendor.orders.cancel') }} --}}
    <form method="POST" action="{{ route('order.cancel', $payment->id) }}">
      @csrf
      @method('PUT')
      <input type="hidden" name="payment_id" id="cancel_payment_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cancel Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="cancel_reason" class="form-label">Reason for Cancellation</label>
            <select class="form-select" name="cancel_reason" id="cancel_reason" required>
              <option value="">Select a reason</option>
              <option value="Canceled by Vendor Admin Due to Out of stock">Out of stock</option>
              <option value="Canceled by Vendor Admin Due to Incorrect price">Incorrect price</option>
              <option value="Canceled by Vendor Admin Due to Cannot deliver on time">Cannot deliver on time</option>
              <option value="Canceled by Vendor Admin Due to Customer request">Customer request</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="mb-3 d-none" id="other_reason_box">
            <label for="other_reason" class="form-label">Other Reason</label>
            <input type="text" class="form-control" name="other_reason" id="other_reason" placeholder="Specify reason">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Confirm Cancel</button>
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
    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('click', function (e) {
            // Prevent modal from showing if clicked on Action or Status column
            if (e.target.closest('td:nth-last-child(-n+2)')) return;

            const payment = JSON.parse(this.dataset.payment);
            
            
            // User info
            const user = payment.user;
            // console.log(user);
            // return;
            document.getElementById('modal_user_name').textContent = user?.name || 'N/A';
            document.getElementById('modal_user_email').textContent = user?.email || 'N/A';
            document.getElementById('modal_user_phone').textContent = user?.number || 'N/A';

            // Address info
            const address = payment.address;
            document.getElementById('modal_address_full').textContent = address?.address || 'N/A';
            document.getElementById('modal_address_landmark').textContent = address?.landmark || 'N/A';
            document.getElementById('modal_address_phone').textContent = address?.mobile_number || 'N/A';
            document.getElementById('modal_address_pincode').textContent = address?.pincode ?? address?.postal_code;
            document.getElementById('modal_address_alt').textContent = address?.alt_mobile_number || 'N/A';

            const addressModal = new bootstrap.Modal(document.getElementById('addressModal'));
            addressModal.show();
        });
    });
    // Show cancel modal and set payment_id
    const cancelModal = document.getElementById('cancelModal');
    cancelModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const paymentId = button.getAttribute('data-id');
        document.getElementById('cancel_payment_id').value = paymentId;
    });

    // Toggle "Other Reason" field
    const reasonSelect = document.getElementById('cancel_reason');
    const otherReasonBox = document.getElementById('other_reason_box');

    reasonSelect.addEventListener('change', function () {
        if (this.value === 'Other') {
            otherReasonBox.classList.remove('d-none');
        } else {
            otherReasonBox.classList.add('d-none');
            document.getElementById('other_reason').value = '';
        }
    });
</script>

@endsection
