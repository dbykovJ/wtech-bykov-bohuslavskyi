<div class="account-menu-toggle">
    <button class="account-menu-toggle__btn" type="button">
        <span class="account-menu-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </button>
    <span class="account-menu-toggle__label">Меню акаунта</span>
</div>

<aside class="account-nav">
    <div class="account-nav__title">Меню акаунта</div>
    <div class="account-nav__list">
        <a href="{{ route('account') }}"
           class="account-nav__link {{ request()->routeIs('account') ? 'account-nav__link--active' : '' }}">
            Огляд
        </a>
        <a href="{{ route('account.personal-data') }}"
           class="account-nav__link {{ request()->routeIs('account.personal-data') ? 'account-nav__link--active' : '' }}">
            Особисті дані
        </a>
        <a href="{{ route('account.cart') }}"
           class="account-nav__link {{ request()->routeIs('account.cart') || request()->routeIs('cart') ? 'account-nav__link--active' : '' }}">
            Кошик і оформлення
        </a>
        <a href="{{ route('account.orders') }}"
           class="account-nav__link {{ request()->routeIs('account.orders') ? 'account-nav__link--active' : '' }}">
            Замовлення
        </a>
        @if(auth()->user()?->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="account-nav__link">
                Панель адміністратора
            </a>
        @endif
        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="account-nav__link account-nav__link--button">Вийти</button>
            </form>
        @endauth
    </div>
</aside>
