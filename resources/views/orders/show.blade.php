@extends('layouts.admin')

@section('content')
    <h3>Order #{{ $order->id }}</h3>

    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
    <p><strong>Date:</strong> {{ $order->order_date }}</p>
    <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- STATUS FLOW LOGIC --}}
    @if (auth()->user()->isAdmin())
        @if ($order->status === 'Pending')
            {{-- Admin can update only when Pending --}}
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf
                <label>Status</label>
                <select name="status" class="form-select w-auto d-inline-block">
                    <option value="Pending" selected>Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button class="btn btn-primary">Update</button>
            </form>
        @elseif ($order->status === 'Completed')
            <p>
                <span class="badge bg-success">Completed</span>
                <small class="text-muted">Completed orders cannot change status.</small>
            </p>
        @elseif ($order->status === 'Cancelled')
            <p>
                <span class="badge bg-danger">Cancelled</span>
                <small class="text-muted">Cancelled orders cannot change status.</small>
            </p>
        @endif
    @else
        {{-- STAFF READ-ONLY --}}
        @if ($order->status === 'Pending')
            <span class="badge bg-warning text-dark">Pending</span>
        @elseif ($order->status === 'Completed')
            <span class="badge bg-success">Completed</span>
        @else
            <span class="badge bg-danger">Cancelled</span>
        @endif

        <p class="text-muted mt-2">Staff cannot update status.</p>
    @endif

@endsection
