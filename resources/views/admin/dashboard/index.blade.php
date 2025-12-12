@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info text-white">
                    <div class="inner">
                        <h3>{{ $total_orders }}</h3>
                        <p>Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success text-white">
                    <div class="inner">
                        <h3>{{ $total_customers }}</h3>
                        <p>Customers</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning text-white">
                    <div class="inner">
                        <h3>{{ $total_products }}</h3>
                        <p>Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
