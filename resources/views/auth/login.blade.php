@extends('layouts.app')

@section('title', 'Login')

{{-- Modern gradient background --}}
@section('body-class', 'min-vh-100 d-flex justify-content-center align-items-center bg-gradient')

@section('content')
    <div class="login-wrapper d-flex justify-content-center align-items-center w-100">

        <div class="card shadow-lg p-4" style="width: 420px; border-radius: 15px;">

            {{-- App Title / Logo --}}
            <div class="text-center mb-3">
                <h3 class="fw-bold">Welcome Back</h3>
                <p class="text-muted small">Sign in to continue</p>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg"
                        placeholder="Enter your password" required>
                </div>

                <button class="btn btn-primary btn-lg w-100 mt-2 shadow-sm">
                    Login
                </button>
            </form>
        </div>

    </div>

    {{-- Custom CSS --}}
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }

        .card {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
