<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a class="brand-link text-center py-3">
        <span
            class="brand-text font-weight-light">{{ auth()->check() ? (auth()->user()->isAdmin() ? 'Admin' : 'Staff') : 'Panel' }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item"><a
                        href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('staff.dashboard') }}"
                        class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a></li>

                <li class="nav-item"><a
                        href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('staff.orders.index') }}"
                        class="nav-link"><i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Orders</p>
                    </a></li>

                @if (auth()->user()->isAdmin())
                    <li class="nav-item"><a href="{{ route('admin.products.index') }}" class="nav-link"><i
                                class="nav-icon fas fa-box"></i>
                            <p>Products</p>
                        </a></li>
                    <li class="nav-item"><a href="{{ route('admin.customers.index') }}" class="nav-link"><i
                                class="nav-icon fas fa-users"></i>
                            <p>Customers</p>
                        </a></li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
