@extends('layouts.admin')

@section('content')
    <h3>Add Customer</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <button class="btn btn-success">Save</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back</a>
            </form>

        </div>
    </div>
@endsection
