<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\customer;
use App\Models\order;
use App\Models\orderItem;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isStaff()) {
            abort(403);
        }

        $query = Order::with(['customer', 'items.product']);

        // Search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', (int) $search)
                        ->orWhere('total_amount', (float) $search);
                }

                $q->orWhereHas('customer', function ($qc) use ($search) {
                    $qc->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Staff can filter only their orders OR all?
        // Normally staff sees ALL, but no status update permission.
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($customerId = $request->query('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        if ($from = $request->query('date_from')) {
            $query->whereDate('order_date', '>=', $from);
        }

        if ($to = $request->query('date_to')) {
            $query->whereDate('order_date', '<=', $to);
        }

        // sorting
        $sort = $request->query('sort_by', 'order_date');
        $dir = $request->query('sort_dir', 'desc');
        $query->orderBy($sort, $dir);

        $orders = $query->paginate(15)->appends($request->query());
        $customers = Customer::orderBy('name')->get();

        return view('orders.index', compact('orders', 'customers'));
    }

    public function create()
    {
        if (!auth()->user()->isStaff())
            abort(403);

        return view('orders.create', [
            'customers' => Customer::all(),
            'products' => Product::all(),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        if (!auth()->user()->isStaff())
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
                ->route('staff.orders.show', $order->id)
                ->with('success', 'Order created successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        if (!auth()->user()->isStaff())
            abort(403);

        $order = Order::with('customer', 'items.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}
