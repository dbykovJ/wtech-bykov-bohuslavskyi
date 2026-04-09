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
                    @foreach($productsOnSale= App\Models\Product::take(4)->orderBy('rating', 'desc')->get() as $productOnSale)
                        <a href="{{ route('product', ['product' => $productOnSale]) }}" class="product-card">
                            <div class="placeholder product-card__image"></div>
                            <div class="product-meta">
                                <div class="product-name">{{$productOnSale->name}}</div>
                                <div class="star-rating" style="--rating: {{$productOnSale->rating}}">★★★★★</div>
                                <div class="product-price-row">
                                    <span class="product-price">$145</span>
                                    <span class="product-price-original">${{number_format($productOnSale->price, 2)}}</span>
                                    <span class="badge-red">-14%</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="products-section products-section--last">
            <div class="container">
                <h2 id="new-arrivals" class="section-heading">NEW ARRIVALS</h2>
                <div class="products-grid">
                    @foreach($newProducts= App\Models\Product::take(4)->orderBy('created_at', 'desc')->get() as $newProduct)
                        <a href="{{ route('product', ['product' => $newProduct]) }}" class="product-card">
                            <div class="placeholder product-card__image"></div>
                            <div class="product-meta">
                                <div class="product-name">{{$newProduct->name}}</div>
                                <div class="star-rating" style="--rating: {{$newProduct->rating}}">★★★★★</div>
                                <div class="product-price-row">
                                    <span class="product-price">${{number_format($newProduct->price, 2)}}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
