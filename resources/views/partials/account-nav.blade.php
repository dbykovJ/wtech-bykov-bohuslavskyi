<div class="account-menu-toggle">
    <button class="account-menu-toggle__btn" type="button">
        <span class="account-menu-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </button>
    <span class="account-menu-toggle__label">Account menu</span>
</div>

<aside class="account-nav">
    <div class="account-nav__title">Account menu</div>
    <div class="account-nav__list">
        <a href="{{ route('account') }}" class="account-nav__link {{ request()->routeIs('account') ? 'account-nav__link--active' : '' }}">
            Overview
        </a>
        <a href="{{ route('account.personal-data') }}" class="account-nav__link {{ request()->routeIs('account.personal-data') ? 'account-nav__link--active' : '' }}">
            Personal data
        </a>
        <a href="{{ route('account.cart') }}" class="account-nav__link {{ request()->routeIs('account.cart') ? 'account-nav__link--active' : '' }}">
            Cart &amp; checkout
        </a>
        <a href="{{ route('account.orders') }}" class="account-nav__link {{ request()->routeIs('account.orders') ? 'account-nav__link--active' : '' }}">
            Orders
        </a>
        <a href="{{ route('login') }}" class="account-nav__link">
            Sign out
        </a>
    </div>
</aside>
