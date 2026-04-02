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
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">Shop</span>
        </div>

        <div class="category-layout">
            <aside class="filters">
                <div class="filters__header">
                    <span class="filters__title">Filters</span>
                    <button class="filters__reset">Reset All</button>
                </div>

                <div class="filters__section">
                    <div class="filters__section-title">Categories <span>›</span></div>
                    <div class="filters__categories">
                        <div class="filters__category-item active">T-Shirts <span>›</span></div>
                        <div class="filters__category-item">Shorts <span>›</span></div>
                        <div class="filters__category-item">Shirts <span>›</span></div>
                        <div class="filters__category-item">Hoodie <span>›</span></div>
                        <div class="filters__category-item">Jeans <span>›</span></div>
                    </div>
                </div>

                <div class="filters__section">
                    <div class="filters__section-title">Price Range <span>›</span></div>
                    <div class="price-range">
                        <div class="price-range__track">
                            <div class="price-range__fill"></div>
                            <div class="price-range__handle price-range__handle--left"></div>
                            <div class="price-range__handle price-range__handle--right"></div>
                        </div>
                        <div class="price-range__labels">
                            <span>$50</span>
                            <span>$200</span>
                        </div>
                    </div>
                </div>

                <div class="filters__section">
                    <div class="filters__section-title">Colors <span>›</span></div>
                    <div class="colors-grid">
                        <div class="color-swatch active" style="background: #4caf50" title="Green"></div>
                        <div class="color-swatch" style="background: #f44336" title="Red"></div>
                        <div class="color-swatch" style="background: #ffeb3b" title="Yellow"></div>
                        <div class="color-swatch" style="background: #ff9800" title="Orange"></div>
                        <div class="color-swatch" style="background: #03a9f4" title="Blue"></div>
                        <div class="color-swatch" style="background: #9c27b0" title="Purple"></div>
                        <div class="color-swatch" style="background: #f48fb1" title="Pink"></div>
                        <div class="color-swatch" style="background: #ffffff; border: 1px solid #ddd;" title="White"></div>
                        <div class="color-swatch" style="background: #111" title="Black"></div>
                    </div>
                </div>

                <div class="filters__section">
                    <div class="filters__section-title">Size <span>›</span></div>
                    <div class="sizes-grid">
                        <button class="size-btn">XX-Small</button>
                        <button class="size-btn">X-Small</button>
                        <button class="size-btn active">Small</button>
                        <button class="size-btn">Medium</button>
                        <button class="size-btn">Large</button>
                        <button class="size-btn">X-Large</button>
                        <button class="size-btn">XX-Large</button>
                        <button class="size-btn">3X-Large</button>
                        <button class="size-btn">4X-Large</button>
                    </div>
                </div>
            </aside>

            <div class="products-area">
                <div class="products-area__header">
                    <div>
                        <h1 class="products-area__title">Shop</h1>
                        <div class="products-area__meta">Showing 1-9 of 40 Products</div>
                    </div>
                    <div class="products-area__sort">
                        Sort by:
                        <div class="select">
                            <div class="select__selected">
                                <span>Most Popular</span>
                                <svg width="10" height="6" viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <ul class="select__dropdown">
                                <li class="active">Most Popular</li>
                                <li>Newest</li>
                                <li>Price: Low to High</li>
                                <li>Price: High to Low</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="category-products-grid">
                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Gradient Graphic Tee</div>
                            <div class="star-rating" style="--rating: 4">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$145</span>
                                <span class="product-price-original">$168</span>
                                <span class="badge-red">-14%</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Polo with Tipping Details</div>
                            <div class="star-rating" style="--rating: 5">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$180</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Black Striped T-Shirt</div>
                            <div class="star-rating" style="--rating: 5">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$120</span>
                                <span class="product-price-original">$150</span>
                                <span class="badge-red">-20%</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Skinny Fit Jeans</div>
                            <div class="star-rating" style="--rating: 4">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$240</span>
                                <span class="product-price-original">$260</span>
                                <span class="badge-red">-8%</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Checkered Shirt</div>
                            <div class="star-rating" style="--rating: 5">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$180</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Sleeve Striped T-Shirt</div>
                            <div class="star-rating" style="--rating: 5">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$130</span>
                                <span class="product-price-original">$160</span>
                                <span class="badge-red">-19%</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Vertical Striped Shirt</div>
                            <div class="star-rating" style="--rating: 5">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$212</span>
                                <span class="product-price-original">$232</span>
                                <span class="badge-red">-9%</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('product') }}" class="product-card">
                        <div class="placeholder product-card__image"></div>
                        <div class="product-meta">
                            <div class="product-name">Courage Graphic T-Shirt</div>
                            <div class="star-rating" style="--rating: 4">★★★★★</div>
                            <div class="product-price-row">
                                <span class="product-price">$145</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="pagination">
                    <button class="pagination__btn">←</button>
                    <button class="pagination__btn active">1</button>
                    <button class="pagination__btn">2</button>
                    <button class="pagination__btn">3</button>
                    <span class="pagination__dots">...</span>
                    <button class="pagination__btn">10</button>
                    <button class="pagination__btn">→</button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/category/select.js') }}"></script>
@endpush
