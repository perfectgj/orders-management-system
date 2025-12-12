@extends('layouts.admin')

@section('content')
    <h3>Add Product</h3>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name') }}">
                </div>

                @if (auth()->user()->isAdmin())
                    <div class="mb-3">
                        <label>Price</label>
                        <input name="price" type="number" class="form-control" step="0.01"
                            value="{{ old('price') }}">
                    </div>
                @else
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="text" class="form-control" value="(Only admin can set price)" disabled>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Stock Quantity</label>
                    <input name="stock_quantity" type="number" class="form-control" value="{{ old('stock_quantity', 0) }}">
                </div>

                <div class="mb-3">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button class="btn btn-success">Save</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
            </form>

        </div>
    </div>
@endsection
