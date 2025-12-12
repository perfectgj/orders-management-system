<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\order;
use App\Models\product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ensure admin only
        if (!auth()->user()->isAdmin())
            abort(403);

        $total_orders = Order::count();
        $total_customers = Customer::count();
        $total_products = Product::count();

        return view('admin.dashboard.index', compact('total_orders', 'total_customers', 'total_products'));
    }
}
