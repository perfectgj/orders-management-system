<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ensure staff only
        if (!auth()->user()->isStaff())
            abort(403);

        $total_orders = order::count();
        return view('staff.dashboard', compact('total_orders'));
    }
}
