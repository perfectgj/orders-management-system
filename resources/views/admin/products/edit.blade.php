@extends('layouts.admin')

@section('content')
    <h3>Edit Product</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $product->name) }}">
                </div>

                @if (auth()->user()->isAdmin())
                    <div class="mb-3">
                        <label>Price</label>
                        <input name="price" type="number" class="form-control" step="0.01"
                            value="{{ old('price', $product->price) }}">
                    </div>
                @else
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="text" class="form-control" value="{{ number_format($product->price, 2) }}" disabled>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Stock Quantity</label>
                    <input name="stock_quantity" type="number" class="form-control"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}">
                </div>

                <div class="mb-3">
                    <label>Current Image</label><br>
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail mb-2" style="height:120px">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Upload New Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
            </form>

        </div>
    </div>
@endsection
