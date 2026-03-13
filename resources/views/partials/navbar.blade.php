<nav class="border-b border-[#e5e5e5] py-[13px] bg-white sticky top-0 z-50">
    <div class="max-w-[1160px] mx-auto px-4 md:px-8 flex items-center gap-4 md:gap-7">
        <a href="{{ route('home') }}" class="heading text-[20px] tracking-[2px] whitespace-nowrap">SUPERSELL</a>

        <div class="hidden md:flex items-center gap-5">
            <a href="#" class="nav-link flex items-center gap-[3px]">
                Shop
                <img src="{{ asset('assets/icons/down.svg') }}" alt="Dropdown" width="16" height="16" />
            </a>
            <a href="#" class="nav-link">On Sale</a>
            <a href="#" class="nav-link">New Arrivals</a>
            <a href="#" class="nav-link">Brands</a>
        </div>

        <div class="flex-1 mx-0 md:mx-3">
            <div class="bg-[#f0f0f0] rounded-full flex items-center gap-2 py-2 px-4">
                <img src="{{ asset('assets/icons/search.svg') }}" alt="Search" width="16" height="16" />
                <input type="search" placeholder="Search for Products" class="bg-transparent border-none text-[#000] text-[13px] w-full font-body outline-none placeholder:text-[#888] select-none" />
            </div>
        </div>

        <div class="flex items-center gap-[14px]">
            <img src="{{ asset('assets/icons/shopping-cart.svg') }}" alt="Shopping Cart" width="16" height="16" />
            <img class="hidden md:block" src="{{ asset('assets/icons/user.svg') }}" alt="Account" width="16" height="16" />
            <button class="md:hidden flex flex-col gap-[5px]" aria-label="Menu">
                <span class="block w-5 h-[2px] bg-[#111]"></span>
                <span class="block w-5 h-[2px] bg-[#111]"></span>
                <span class="block w-5 h-[2px] bg-[#111]"></span>
            </button>
        </div>
    </div>
</nav>