@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>Products</h3>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
        @endif
    </div>

    <div class="row">
        @foreach ($products as $p)
            <div class="col-md-3">
                <div class="card mb-3">
                    @if ($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top"
                            style="height:170px;object-fit:cover">
                    @endif

                    <div class="card-body">
                        <h5>{{ $p->name }}</h5>
                        <p>₹{{ number_format($p->price, 2) }}</p>
                        <p>Stock: {{ $p->stock_quantity }}</p>

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form class="d-inline" method="POST" action="{{ route('admin.products.destroy', $p->id) }}">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @else
                            <span class="text-muted">Read only</span>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $products->links() }}
@endsection
