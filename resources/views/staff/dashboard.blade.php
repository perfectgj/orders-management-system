@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="card p-3">
                    <h5>Orders</h5>
                    <p>Total orders: {{ $total_orders }}</p>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card p-3">
                    <h5>Your Tasks</h5>
                    <p>Quick actions for staff.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
