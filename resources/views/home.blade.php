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
                    @foreach($productsOnSale as $productOnSale)
                        @php
                            $imageUrl = $productOnSale->getFirstImage()?->public_url;
                        @endphp
                        <a href="{{ route('product', ['id' => $productOnSale->id]) }}" class="product-card">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $productOnSale->name }}" class="product-card__image" />
                            @else
                                <div class="placeholder product-card__image"></div>
                            @endif
                            <div class="product-meta">
                                <div class="product-name">{{$productOnSale->name}}</div>
                                <div class="star-rating" style="--rating: {{$productOnSale->rating}}">★★★★★</div>
                                <div class="product-price-row">
                                    <span
                                        class="product-price">${{number_format($productOnSale->price - ($productOnSale->price*$productOnSale->total_discount), 2)}}</span>
                                    <span
                                        class="product-price-original">${{number_format($productOnSale->price, 2)}}</span>
                                    <span
                                        class="badge-red">-{{number_format($productOnSale->total_discount * 100)}}%</span>
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
                    @foreach($newArrivals as $newArrival)
                        @php
                            $imageUrl = $newArrival->getFirstImage()?->public_url;
                        @endphp
                        <a href="{{ route('product', ['id' => $newArrival->id]) }}" class="product-card">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $newArrival->name }}" class="product-card__image" />
                            @else
                                <div class="placeholder product-card__image"></div>
                            @endif
                            <div class="product-meta">
                                <div class="product-name">{{$newArrival->name}}</div>
                                <div class="star-rating" style="--rating: {{$newArrival->rating}}">★★★★★</div>
                                <div class="product-price-row">
                                    @if($newArrival->sales->isNotEmpty())
                                        @php $total_discount = $newArrival->sales->sum('discount') @endphp
                                        <span
                                            class="product-price">${{number_format($newArrival->price - ($newArrival->price*($total_discount/100)), 2)}}</span>
                                        <span
                                            class="product-price-original">${{number_format($newArrival->price, 2)}}</span>
                                        <span
                                            class="badge-red">-{{number_format($total_discount)}}%</span>

                                    @else
                                        <span class="product-price">${{number_format($newArrival->price, 2)}}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
