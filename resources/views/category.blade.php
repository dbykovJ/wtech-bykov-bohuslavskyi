@extends('layouts.app')

@section('title', 'Shop — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}" />
@endpush

@section('content')
    <main class="category-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="breadcrumb__current">Shop</span>
            </div>

            <div class="category-layout">
                <form action="{{ route('category') }}" method="GET" id="filter-form">
                    <aside class="filters">
                        <div class="filters__header">
                            <span class="filters__title">Filters</span>
                            <a href="{{ route('category') }}" class="filters__reset">Reset All</a>
                        </div>

                        <div class="filters__section">
                            <div class="filters__section-title">Categories</div>
                            <div class="filters__categories">
                                <div class="filters__category-item {{ !request('category') ? 'active' : '' }}"
                                     onclick="setCategory('')">
                                    <span>All</span>
                                    <span class="filters__category-radio {{ !request('category') ? 'filters__category-radio--active' : '' }}"></span>
                                </div>
                                @foreach($categories as $cat)
                                    <div class="filters__category-item {{ request('category') == $cat->id ? 'active' : '' }}"
                                         onclick="setCategory('{{ $cat->id }}')">
                                        <span>{{ $cat->name }}</span>
                                        <span class="filters__category-radio {{ request('category') == $cat->id ? 'filters__category-radio--active' : '' }}"></span>
                                    </div>
                                @endforeach
                                <input type="hidden" name="category" id="category-input" value="{{ request('category') }}" />
                            </div>
                        </div>

                        <div class="filters__section">
                            <div class="filters__section-title">Price Range</div>
                            <div class="filters__price-inputs">
                                <div class="filters__price-box">
                                    <span class="filters__price-label">Min</span>
                                    <input type="number" name="min_price" placeholder="$0"
                                           value="{{ request('min_price') }}"
                                           min="0" step="1"
                                           class="filters__price-input" />
                                </div>
                                <span class="filters__price-sep">—</span>
                                <div class="filters__price-box">
                                    <span class="filters__price-label">Max</span>
                                    <input type="number" name="max_price" placeholder="$999"
                                           value="{{ request('max_price') }}"
                                           min="0" step="1"
                                           class="filters__price-input" />
                                </div>
                            </div>
                        </div>

                        <div class="filters__section">
                            <button type="submit" class="filters__apply-btn">Apply Filters</button>
                        </div>
                    </aside>
                </form>

                <div class="products-area">
                    <div class="products-area__header">
                        <div>
                            <h1 class="products-area__title">Shop</h1>
                            <div class="products-area__meta">
                                @php
                                    $firstItem = $products->firstItem() ?? 0;
                                    $lastItem = $products->lastItem() ?? 0;
                                @endphp
                                @if(request('search'))
                                    Showing {{ $firstItem }}-{{ $lastItem }}
                                    of {{ $products->total() }} Products for "{{ request('search') }}"
                                @else
                                    Showing {{ $firstItem }}-{{ $lastItem }}
                                    of {{ $products->total() }} Products
                                @endif
                            </div>
                        </div>
                        <div class="products-area__sort">
                            Sort by:
                            <div class="select">
                                <div class="select__selected">
                                <span>{{ match(request('sort')) {
                                    'price_asc'  => 'Price: Low to High',
                                    'price_desc' => 'Price: High to Low',
                                    'newest'     => 'Newest',
                                    default      => 'Newest'
                                } }}</span>
                                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none">
                                        <path d="M1 1l4 4 4-4" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <ul class="select__dropdown">
                                    <li onclick="setSort('newest')" class="{{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">Newest</li>
                                    <li onclick="setSort('price_asc')" class="{{ request('sort') === 'price_asc' ? 'active' : '' }}">Price: Low to High</li>
                                    <li onclick="setSort('price_desc')" class="{{ request('sort') === 'price_desc' ? 'active' : '' }}">Price: High to Low</li>
                                </ul>
                            </div>
                            <input type="hidden" name="sort" id="sort-input" value="{{ request('sort') }}" form="filter-form" />
                        </div>
                    </div>

                    <div class="category-products-grid">
                        @forelse ($products as $product)
                            @php
                                $firstImage = $product->getFirstImage();
                                $imagePath = $firstImage?->public_url;
                            @endphp
                            <a href="{{ route('product', ['id'=>$product->id]) }}" class="product-card">
                                @if($imagePath)
                                    <img src="{{ $imagePath }}"
                                         alt="{{ $product->name }}"
                                         class="product-card__image" style="object-fit: cover;" />
                                @else
                                    <div class="placeholder product-card__image"></div>
                                @endif
                                <div class="product-meta">
                                    <div class="product-name">{{ $product->name }}</div>
                                    @php $discount = $product->sales->sum('discount'); @endphp
                                    <div class="product-price-row">
                                        @if($discount > 0)
                                            <span class="product-price">${{ number_format($product->price - $product->price * ($discount / 100), 2) }}</span>
                                            <span class="product-price product-price--original" style="text-decoration:line-through; color:#aaa; font-size:0.85em;">${{ number_format($product->price, 2) }}</span>
                                            <span class="badge-red">-{{ number_format($discount) }}%</span>
                                        @else
                                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p style="color: #888; font-size: 14px;">No products found.</p>
                        @endforelse
                    </div>

                    @if ($products->hasPages())
                        <div class="pagination">
                            {{ $products->onEachSide(1)->links('vendor.pagination.shop') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/category/select.js') }}"></script>
    <script>
        function setCategory(id) {
            document.getElementById('category-input').value = id;
            document.getElementById('filter-form').submit();
        }

        function setSort(value) {
            document.getElementById('sort-input').value = value;
            document.getElementById('filter-form').submit();
        }
    </script>
@endpush
