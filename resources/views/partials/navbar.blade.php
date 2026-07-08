<nav class="navbar">
    <div class="container navbar__inner">
        <a href="{{ route('home') }}" class="navbar__logo heading">
            <img draggable="false" src="{{ asset('assets/logo.svg') }}" alt="Look of Today" class="navbar__logo-image" />
        </a>

        <div class="navbar__links">
            <a href="{{ route('category') }}" class="navbar__link">Магазин</a>
            <a href="{{ route('home') }}#top-brand-seller" class="navbar__link">Бренди</a>
            <a href="{{ route('home') }}#on-sale" class="navbar__link">Знижки</a>
            <a href="{{ route('home') }}#new-arrivals" class="navbar__link">Новинки</a>
        </div>

        <div class="navbar__search">
            <form action="{{ route('category') }}" method="GET" class="navbar__search-inner">
                <img draggable="false" src="{{ asset('assets/icons/search.svg') }}" alt="пошук" />
                <input
                    id="search-input"
                    type="search"
                    name="search"
                    placeholder="Пошук товарів"
                    value="{{ request('search') }}"
                />
            </form>
        </div>

        <div class="navbar__icons">
            <a href="{{ route('cart') }}">
                <img draggable="false" src="{{ asset('assets/icons/shopping-cart.svg') }}" alt="Кошик" />
            </a>
            @auth
                <a href="{{ route('account') }}">
                    <img draggable="false" class="navbar__user-icon" src="{{ asset('assets/icons/user.svg') }}"
                         alt="Акаунт" />
                </a>
            @else
                <a href="{{ route('login') }}">
                    <img draggable="false" class="navbar__user-icon" src="{{ asset('assets/icons/user.svg') }}"
                         alt="Акаунт" />
                </a>
            @endauth
            <button class="navbar__burger" aria-label="Меню">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>


@push('scripts')
    <script>
        const searchInput = document.getElementById("search-input");

        if (searchInput) {
            let searchTimer;

            searchInput.addEventListener("input", function () {
                clearTimeout(searchTimer);

                const query = this.value.trim();

                // if (query.length < 2) {
                //     return;
                // }

                searchTimer = setTimeout(() => {
                    const baseUrl = "{{ route('category') }}";
                    const url = baseUrl + "?search=" + encodeURIComponent(query);

                    window.location.href = url;
                }, 400);
            });
        }
    </script>
@endpush

