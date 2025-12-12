@extends('layouts.admin')

@section('content')
    @php
        $statuses = ['Pending', 'Completed', 'Cancelled'];
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Orders</h3>

        <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.create') : route('staff.orders.create') }}"
            class="btn btn-primary">
            <i class="fa fa-plus"></i> Create Order
        </a>
    </div>

    @include('orders.partials.filters')

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr id="order-row-{{ $order->id }}">
                                <td>{{ $order->id }}</td>

                                <td>
                                    {{ $order->customer->name }}
                                    <div class="text-muted small">{{ $order->customer->email }}</div>
                                </td>

                                <td>₹{{ number_format($order->total_amount, 2) }}</td>

                                <td>
                                    {{-- ADMIN STATUS FLOW --}}
                                    @if (auth()->user()->isAdmin())
                                        @if ($order->status === 'Pending')
                                            {{-- Editable only when Pending --}}
                                            <select class="form-select form-select-sm status-select"
                                                data-order-id="{{ $order->id }}">
                                                <option value="Pending" selected>Pending</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        @elseif ($order->status === 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                            <small class="text-muted d-block">Final state</small>
                                        @elseif ($order->status === 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                            <small class="text-muted d-block">Final state</small>
                                        @endif

                                        {{-- STAFF VIEW --}}
                                    @else
                                        @if ($order->status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($order->status === 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    @endif
                                </td>

                                <td>{{ $order->order_date }}</td>

                                <td>
                                    <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.show', $order->id) : route('staff.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-secondary">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <span>Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}
                    orders</span>
                {{ $orders->links() }}
            </div>
        </div>

    </div>
@endsection


{{-- SCRIPTS --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const token = document.querySelector('meta[name="csrf-token"]').content;

            document.querySelectorAll('.status-select').forEach(select => {

                select.addEventListener('change', function() {
                    const newStatus = this.value;
                    const orderId = this.dataset.orderId;

                    Swal.fire({
                            title: "Update Status?",
                            text: `Set order #${orderId} to "${newStatus}"?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, update",
                        })
                        .then(result => {
                            if (!result.isConfirmed) {
                                window.location.reload();
                                return;
                            }

                            fetch(`/admin/orders/${orderId}/status-ajax`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": token,
                                    },
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                })
                                .then(r => r.json())
                                .then(res => {
                                    if (res.status === "success") {
                                        Swal.fire({
                                            toast: true,
                                            position: "top-end",
                                            icon: "success",
                                            title: res.message,
                                            showConfirmButton: false,
                                            timer: 1800
                                        });
                                        window.location.reload();
                                    } else {
                                        Swal.fire("Error", res.message, "error");
                                        window.location.reload();
                                    }
                                })
                                .catch(() => {
                                    Swal.fire("Error", "Request failed", "error");
                                    window.location.reload();
                                });

                        });

                });
            });

        });
    </script>
@endpush
