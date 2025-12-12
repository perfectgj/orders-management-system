@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>Customers</h3>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Add Customer</a>
        @endif
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($customers as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>

                            <td>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.customers.edit', $c->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form class="d-inline" action="{{ route('admin.customers.destroy', $c->id) }}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Delete?')"
                                            class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @else
                                    <span class="text-muted">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="card-footer">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
