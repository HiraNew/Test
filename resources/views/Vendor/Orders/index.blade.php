@extends('layouts.vendor')
@section('title', 'Orders')
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
        {{-- @if (isset($orders)) --}}
        <tbody>
            @forelse ($orders as $payment)
            <tr class="order-row" data-payment='@json($payment)'>
                <td>{{ $payment->orderid }}</td>
                <td>{{ $payment->created_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                @if(!in_array($payment->status, ['pending', 'confirmed', 'shipped']))
                <td>{{ $payment->updated_at->timezone('Asia/Kolkata')->format('d M, Y h:i:s A') }}</td>
                                                                        
                @else
                <td>Not Delivered Yet.</td>
                @endif
                <td>{{ $payment->product->name ?? 'N/A' }}</td>
                <td>{{ $payment->qty }}</td>
                <td>₹{{ $payment->amount }}</td>
                <td>
                    <span class="badge bg-{{ $payment->status == 'pending' || $payment->status == 'cancelled'  ? 'danger' : ($payment->status == 'confirmed' ? 'info' : 'success') }}">
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
                          <button class="btn btn-sm btn-secondary text-warning sendNotificationBtn"
                            data-bs-toggle="modal"
                            data-id="{{ $payment->id }}"
                            data-user-id="{{ $payment->user_id }}"
                            data-product-id="{{ $payment->product_id }}"
                            data-product-name="{{ $payment->product->name ?? 'N/A' }}"
                            data-user-name="{{ $payment->user->name ?? 'N/A' }}"
                            data-status="cancelled"
                            data-bs-target="#notificationModal">
                            Send Cancelled Notification
                        </button>

                      @elseif ($payment->status === 'delivered')
                          <button class="btn btn-sm btn-success text-white sendNotificationBtn"
                              data-bs-toggle="modal" 
                              data-id="{{ $payment->id }}"
                              data-user-id="{{ $payment->user_id }}"
                              data-product-id="{{ $payment->product_id }}"
                              data-product-name="{{ $payment->product->name ?? 'N/A' }}"
                              data-user-name="{{ $payment->user->name ?? 'N/A' }}"
                              data-status="delivered"
                              data-bs-target="#notificationModal">
                              Send Delivered Notification
                          </button>
                      @endif

                    @endif
                </td>
            </tr>
              @empty
              <tr>
                  <td colspan="8" class="text-center text-muted">Nobody ordered your product yet. Keep patience 😊</td>
              </tr>
            @endforelse
        </tbody>
       
          {{-- @endif --}}
    </table>
</div>

<!-- Notification Modal (Used for both Cancelled and Delivered) -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('vendor.orders.sendNotification') }}">
      @csrf
      <input type="hidden" name="payment_id" id="notification_payment_id">
      <input type="hidden" name="user_id" id="notification_user_id">
      <input type="hidden" name="product_id" id="notification_product_id">
      <input type="hidden" name="product_name" id="notification_product_name">
      <input type="hidden" name="user_name" id="user_name">
      <input type="hidden" name="status" id="notification_status">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notificationModalTitle">Send Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="notification_message" class="form-label">Notification Message</label>
            <textarea name="message" class="form-control" id="notification_message" rows="4" required placeholder="Write notification for this order..."></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send Notification</button>
        </div>
      </div>
    </form>
  </div>
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
    @if (isset($payment_id))
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
    @endif
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
            // alert(payment.product.name)
            
            
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


    // for send notification
      document.querySelectorAll('.sendNotificationBtn').forEach(button => {
          button.addEventListener('click', function () {
              const paymentId = this.dataset.id;
              const userId = this.dataset.userId;
              const productId = this.dataset.productId;
              const productName = this.dataset.productName;
              const userName = this.dataset.userName;
              // console.log(productName);
              const status = this.dataset.status;

              // Set hidden fields
              document.getElementById('notification_payment_id').value = paymentId;
              document.getElementById('notification_user_id').value = userId;
              document.getElementById('notification_product_id').value = productId;
              document.getElementById('notification_product_name').value = productName;
              document.getElementById('user_name').value = userName;
              document.getElementById('notification_status').value = status;

              // Change modal title
              const titleMap = {
                  'cancelled': 'Send Cancelled Notification',
                  'delivered': 'Send Delivered Notification'
              };
              document.getElementById('notificationModalTitle').textContent = titleMap[status] || 'Send Notification';
              // Set default message
              const defaultMessage = {
                  'cancelled': `Dear "${userName}", your order for product "${productName}" has been cancelled. We apologize for the inconvenience.`,
                  'delivered': `Dear "${userName}", your order for "${productName}" has been delivered successfully. Thank you for shopping with us!`
              };


              // Optional: clear previous message
               document.getElementById('notification_message').value = defaultMessage[status] || '';
          });
      });
</script>

@endsection
