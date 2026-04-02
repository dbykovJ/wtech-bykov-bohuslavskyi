@extends('layouts.app')

@section('title', 'SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')
<main>
    <section class="hero">
        <div class="container">
            <div class="hero__grid">
                <div>
                    <h1 class="hero__title">
                        FIND CLOTHES<br />THAT MATCHES<br />YOUR STYLE
                    </h1>
                    <p class="hero__desc">
                        Browse through our diverse range of meticulously
                        crafted garments, designed to bring out your
                        individuality and cater to your sense of style.
                    </p>
                    <a href="{{ route('category') }}" class="hero__cta">Shop Now</a>

                    <div class="hero__stats">
                        <div>
                            <div class="hero__stat-num">200+</div>
                            <div class="hero__stat-label">International Brands</div>
                        </div>
                        <div class="hero__stat-divider"></div>
                        <div>
                            <div class="hero__stat-num">2,000+</div>
                            <div class="hero__stat-label">High Quality Products</div>
                        </div>
                        <div class="hero__stat-divider"></div>
                        <div>
                            <div class="hero__stat-num">30,000+</div>
                            <div class="hero__stat-label">Happy Customers</div>
                        </div>
                    </div>
                </div>

                <div class="placeholder hero__image"></div>
            </div>
        </div>
    </section>

    <section class="brands-section">
        <div class="container">
            <h2 id="top-brand-seller" class="section-heading">TOP BRAND SELLER</h2>

            <div class="brands-grid">
                <div class="placeholder brands-grid__main"></div>
                <div class="brands-grid__center">
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                </div>
                <div class="brands-grid__right">
                    <div class="placeholder"></div>
                    <div class="placeholder"></div>
                </div>
            </div>

            <div class="brands-grid--mobile">
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="container">
            <h2 id="on-sale" class="section-heading">ON SALE</h2>
            <div class="products-grid">
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
            </div>
        </div>
    </section>

    <section class="products-section products-section--last">
        <div class="container">
            <h2 id="new-arrivals" class="section-heading">NEW ARRIVALS</h2>
            <div class="products-grid">
                <a href="{{ route('product') }}" class="product-card">
                    <div class="placeholder product-card__image"></div>
                    <div class="product-meta">
                        <div class="product-name">T-Shirt with Tape Details</div>
                        <div class="star-rating" style="--rating: 4">★★★★★</div>
                        <div class="product-price-row">
                            <span class="product-price">$120</span>
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
            </div>
        </div>
    </section>
</main>
@endsection
