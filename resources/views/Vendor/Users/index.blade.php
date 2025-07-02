@extends('layouts.vendor')

@section('title', 'Your Customers')

@section('content')
<div class="container mt-4">
    <h3>Customers Who Ordered Your Products</h3>

    @if($customers->isEmpty())
        <div class="alert alert-warning">No customers found yet.</div>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Products Ordered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['user']->name }}</td>
                        <td>{{ $data['user']->email }}</td>
                        <td>{{ $data['user']->number ?? 'N/A' }}</td>
                        <td>
                            {{ $data['products']->count() }}
                            {{-- {{$data['payments']->id}} --}}
                            {{-- @foreach($data['products'] as $product)
                                <span class="badge bg-info text-dark">{{ $product->name }}</span>
                            @endforeach --}}
                        </td>
                        <td>
                            <!-- 3-dots menu here (same as before) -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    ⋮
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item remove-charge-btn"
                                           {{-- href="#" --}}
                                           data-user-id="{{ $data['user']->id }}"
                                           data-user-name="{{ $data['user']->name }}"
                                           data-bs-toggle="modal"
                                           data-bs-target="#removeChargeModal">
                                           Remove Extra Charge
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item send-user-notification-btn"
                                        {{-- href="#" --}}
                                        data-user-id="{{ $data['user']->id }}"
                                        data-user-name="{{ $data['user']->name }}"
                                        data-payment-id="{{ $data['payments']->first()->id ?? '' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userNotificationModal">
                                        Send Notification
                                        </a>

                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
    <!-- Remove Extra Charge Modal -->
        <div class="modal fade" id="removeChargeModal" tabindex="-1" aria-labelledby="removeChargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            {{-- {{ route('vendor.users.removeCharge') }} --}}
            <form method="POST" action="#">
            @csrf
            <input type="hidden" name="payment_id" id="notification_payment_id">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Remove Extra Charge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                Are you sure you want to remove extra charges for <strong id="remove_charge_user_name"></strong>?
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Remove Charge</button>
                </div>
            </div>
            </form>
        </div>
        </div>

        <!-- Send Notification Modal -->
        <div class="modal fade" id="userNotificationModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('vendor.orders.sendNotification') }}">
            @csrf
            <input type="hidden" name="user_id" id="notification_user_id">
            <input type="hidden" name="payment_id" id="notification_payment_id"> <!-- FIXED -->

            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Send Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                    <label for="vendor_product_select" class="form-label">Bind Product with notification.</label>
                    <select name="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach($vendorProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notification_message" class="form-label">Message</label>
                    <textarea name="message" class="form-control" id="notification_message" rows="4" required></textarea>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
            </form>
        </div>
        </div>


    <script>
    document.querySelectorAll('.remove-charge-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            document.getElementById('remove_charge_user_id').value = userId;
            document.getElementById('remove_charge_user_name').textContent = userName;
        });
    });

    document.querySelectorAll('.send-user-notification-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const paymentId = this.dataset.paymentId;

            document.getElementById('notification_user_id').value = userId;
            document.getElementById('notification_payment_id').value = paymentId;
            document.getElementById('notification_message').value = ''; // reset
        });
    });

    </script>

@endsection
