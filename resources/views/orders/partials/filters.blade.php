{{-- Orders Filters Component --}}
<div class="card mb-3">
    <div class="card-body">
        <form id="filtersForm" method="GET"
            action="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('staff.orders.index') }}"
            class="row g-2">

            {{-- Search --}}
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search Order ID / Customer / Amount">
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All status</option>

                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
            </div>

            {{-- Customer --}}
            <div class="col-md-3">
                <select name="customer_id" class="form-select">
                    <option value="">All customers</option>

                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="col-md-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"
                    placeholder="From">
            </div>

            {{-- Date To --}}
            <div class="col-md-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"
                    placeholder="To">
            </div>

            {{-- Sort By --}}
            <div class="col-md-2 mt-2">
                <select name="sort_by" class="form-select">
                    <option value="order_date" {{ request('sort_by') == 'order_date' ? 'selected' : '' }}>Sort: Date
                    </option>
                    <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>Sort: ID</option>
                    <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Sort:
                        Amount</option>
                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Sort: Status</option>
                </select>
            </div>

            {{-- Sort Direction --}}
            <div class="col-md-2 mt-2">
                <select name="sort_dir" class="form-select">
                    <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Desc</option>
                    <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Asc</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="col-md-2 mt-2">
                <button type="submit" class="btn btn-primary w-100">Apply</button>
            </div>

            <div class="col-md-2 mt-2">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('staff.orders.index') }}"
                    class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>

        </form>
    </div>
</div>
