@extends('layouts.admin')

@section('content')
    <h3>Edit Customer</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $customer->name) }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back</a>
            </form>

        </div>
    </div>
@endsection
