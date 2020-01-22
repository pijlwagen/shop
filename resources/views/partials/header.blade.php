<nav class="navbar fixed-top navbar-expand-md navbar-light bg-light shadow-sm{{ $admin ?? false ? ' mt-5' : ''}}">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            {{ config('app.name', 'Store') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav" aria-controls="nav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link">Products</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="account-dropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu" aria-labelledby="account-dropdown">
                            <a class="dropdown-item" href="{{ route('account.index') }}">Personal information</a>
                            <a class="dropdown-item" href="{{ route('account.orders') }}">Orders</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link"
                           onclick="event.preventDefault(); $('#logout-form').submit()">Logout</a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" hidden>
                            @csrf
                        </form>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('cart.index') }}" class="nav-link">Cart
                        ({{ Cart::count() }})</a>
                </li>
                @if (Auth::check())
                    @if (Auth::user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="admin-dropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Administrator
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="admin-dropdown">
                                <a class="dropdown-item" href="{{ route('admin.orders.index') }}">Orders</a>
                                <a class="dropdown-item" href="{{ route('admin.products.index') }}">Products</a>
                                <a class="dropdown-item" href="{{ route('admin.categories.index') }}">Categories</a>
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a>
                            </div>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>
