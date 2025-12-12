<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        $products = Product::latest()->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image'))
            $data['image'] = $request->file('image')->store('products', 'public');

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image))
                Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        if ($product->image && Storage::disk('public')->exists($product->image))
            Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('success', 'Product deleted');
    }
}
