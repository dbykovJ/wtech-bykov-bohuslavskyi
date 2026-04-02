<nav class="navbar">
    <div class="container navbar__inner">
        <a href="{{ route('home') }}" class="navbar__logo heading">SUPERSELL</a>

        <div class="navbar__links">
            <a href="{{ route('category') }}" class="navbar__link">Shop</a>
            <a href="{{ route('home') }}#top-brand-seller" class="navbar__link">Brands</a>
            <a href="{{ route('home') }}#on-sale" class="navbar__link">On Sale</a>
            <a href="{{ route('home') }}#new-arrivals" class="navbar__link">New Arrivals</a>
        </div>

        <div class="navbar__search">
            <div class="navbar__search-inner">
                <img draggable="false" src="{{ asset('assets/icons/search.svg') }}" alt="search" />
                <input type="search" placeholder="Search for Products" />
            </div>
        </div>

        <div class="navbar__icons">
            <a href="{{ route('cart') }}">
                <img draggable="false" src="{{ asset('assets/icons/shopping-cart.svg') }}" alt="Cart" />
            </a>
            <a href="{{ route('login') }}">
                <img draggable="false" class="navbar__user-icon" src="{{ asset('assets/icons/user.svg') }}" alt="Account" />
            </a>
            <button class="navbar__burger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>
