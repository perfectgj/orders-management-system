<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\customer;
use App\Models\order;
use App\Models\orderItem;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $query = Order::with(['customer', 'items.product']);

        // SEARCH: by order id OR customer name OR amount
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', (int) $search)
                        ->orWhere('total_amount', (float) $search);
                }
                $q->orWhereHas('customer', function ($qc) use ($search) {
                    $qc->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // FILTER: status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // FILTER: customer
        if ($customerId = $request->query('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        // DATE RANGE
        if ($from = $request->query('date_from')) {
            $query->whereDate('order_date', '>=', $from);
        }
        if ($to = $request->query('date_to')) {
            $query->whereDate('order_date', '<=', $to);
        }

        // SORTING
        $allowedSort = ['id', 'total_amount', 'order_date', 'status'];
        $sortBy = $request->query('sort_by', 'order_date');
        $sortDir = strtolower($request->query('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, $allowedSort))
            $sortBy = 'order_date';
        $query->orderBy($sortBy, $sortDir);

        // pagination
        $perPage = (int) $request->query('per_page', 15);
        $orders = $query->paginate($perPage)->appends($request->query());

        // customers list for filter dropdown
        $customers = Customer::orderBy('name')->get();

        return view('orders.index', compact('orders', 'customers'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        return view('orders.create', [
            'customers' => Customer::all(),
            'products' => Product::all(),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $data = $request->validated();

        DB::beginTransaction();
        try {

            $total = 0;

            foreach ($data['products'] as $row) {
                $product = Product::lockForUpdate()->findOrFail($row['product_id']);

                if ($row['quantity'] > $product->stock_quantity) {
                    return back()->withErrors([
                        'stock' => "Not enough stock for {$product->name}"
                    ]);
                }

                $total += $product->price * $row['quantity'];
            }

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'total_amount' => $total,
                'order_date' => now(),
                'status' => 'Pending'
            ]);

            foreach ($data['products'] as $row) {
                $product = Product::findOrFail($row['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $row['quantity'],
                    'price' => $product->price,
                ]);

                $product->decrement('stock_quantity', $row['quantity']);
            }

            DB::commit();
            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Order created successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $order = Order::with('customer', 'items.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated');
    }

    public function ajaxUpdateStatus(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Completed', 'Cancelled'])]
        ]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $order->status = $request->input('status');
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated',
            'new_status' => $order->status,
            'order_id' => $order->id
        ]);
    }
}
