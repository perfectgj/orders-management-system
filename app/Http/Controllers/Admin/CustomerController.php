<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        Customer::create($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer created');
    }

    public function edit(Customer $customer)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated');
    }

    public function destroy(Customer $customer)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        $customer->delete();
        return back()->with('success', 'Customer deleted');
    }
}
