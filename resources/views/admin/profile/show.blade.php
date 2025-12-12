@extends('layouts.admin')

@section('content')
    <h3>Profile</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="img-thumbnail mb-2"
                                style="width:150px;height:150px;object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                class="img-thumbnail mb-2" style="width:150px;height:150px;">
                        @endif

                        <div class="mb-3">
                            <label>Avatar</label>
                            <input type="file" name="avatar" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label>Name</label>
                            <input name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>

                        <hr>

                        <h5>Change Password</h5>
                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button class="btn btn-success">Update</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
