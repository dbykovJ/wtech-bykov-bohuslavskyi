<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SuperDash — SUPERSELL')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar__logo">SuperDash</div>
            <nav class="sidebar__nav">
                <a href="{{ route('admin.dashboard') }}" class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'sidebar__link--active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="sidebar__link {{ request()->routeIs('admin.products*') ? 'sidebar__link--active' : '' }}">Products</a>
                <a href="{{ route('admin.orders') }}" class="sidebar__link {{ request()->routeIs('admin.orders') ? 'sidebar__link--active' : '' }}">Order List</a>
            </nav>

            <form action="{{ route('logout') }}" method="POST" style="padding: 16px;">
                @csrf
                <button type="submit" class="sidebar__link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: #ef4444;">
                    Log out
                </button>
            </form>
        </aside>

        <div class="admin-main">
            <header class="topbar">
                <div class="topbar__search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Search" />
                </div>
                <div class="topbar__right">
                    <div class="topbar__user">
                        <div class="topbar__avatar"></div>
                        <div class="topbar__user-info">
                            <span class="topbar__user-name">{{ auth()->user()->name }}</span>
                            <span class="topbar__user-role">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
