@extends('layouts.app')

@section('title', 'Look of Today')

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
                            Жіночий одяг, що підкреслює твій стиль
                        </h1>
                        <p class="hero__desc">
                            Перегляньте наш різноманітний асортимент ретельно
                            виготовленого одягу, створеного, щоб підкреслити вашу
                            індивідуальність і відповідати вашому відчуттю стилю.
                        </p>
                        <div class="hero__actions">
                            <a href="{{ route('category') }}" class="hero__cta">До покупок</a>
                            <a href="{{ route('account') }}" class="hero__loyalty-cta">
                                Створити картку лояльності
                                <span class="hero__loyalty-badge" aria-hidden="true">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="19" y1="5" x2="5" y2="19"></line>
                                        <circle cx="6.5" cy="6.5" r="2.5"></circle>
                                        <circle cx="17.5" cy="17.5" r="2.5"></circle>
                                    </svg>
                                </span>
                            </a>
                        </div>

                        <div class="hero__stats">
                            <div>
                                <div class="hero__stat-num">30+</div>
                                <div class="hero__stat-label">Міжнародних брендів</div>
                            </div>
                            <div class="hero__stat-divider"></div>
                            <div>
                                <div class="hero__stat-num">1k+</div>
                                <div class="hero__stat-label">Якісних товарів</div>
                            </div>
                            <div class="hero__stat-divider"></div>
                            <div>
                                <div class="hero__stat-num">2k+</div>
                                <div class="hero__stat-label">Задоволених клієнтів</div>
                            </div>
                        </div>
                    </div>

                    <div class="hero__media">
                        <video class="hero__video" autoplay muted loop playsinline>
                            <source src="https://res.cloudinary.com/dhecryc7b/video/upload/q_auto/f_auto/v1780518381/Screen_Recording_2026-05-14_175039_eps40w.mp4" type="video/mp4">
                            <img src="{{ asset('assets/ishowspeed.png') }}" alt="Найкращий сайт" class="hero__image" />
                        </video>
                    </div>
                </div>
            </div>
        </section>

        <section class="brands-section">
            <div class="container">
                <h2 id="top-brand-seller" class="section-heading">ТОП ПРОДАЖІВ</h2>

                <div class="brands-grid">
                    <div class="brands-grid__center">
                        @php
                            $brandImages = [
                                [
                                    'path' =>'assets/icons/brands/fortnite.svg',
                                    'name' => 'Fortnite'
                                ],
                                [
                                    'path' => 'assets/icons/brands/brawlstars.svg',
                                    'name' => 'Brawl Stars'
                                ],
                                [
                                    'path' => 'assets/icons/brands/clashroyal.svg',
                                    'name' => 'Clash Royal'
                                ],
                                [
                                    'path' => 'assets/icons/brands/minecraft.svg',
                                    'name' => 'Minecraft'
                                ],
                                [
                                    'path' => 'assets/icons/brands/brand5.svg',
                                    'name' => 'Brand 5'
                                ],
                                [
                                    'path' => 'assets/icons/brands/brand6.svg',
                                    'name' => 'Brand 6'
                                ],
                            ]
                        @endphp

                        @foreach($brandImages as $brandImage)
                            <div class="brand-logo">
                                <img src="{{ asset($brandImage['path']) }}" alt="{{ $brandImage['name'] }}" class="brand-logo__image" />
                            </div>

                        @endforeach
                </div>
            </div>
            </div>
        </section>


        <section class="products-section">
            <div class="container">
                <h2 id="on-sale" class="section-heading">РОЗПРОДАЖ</h2>
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
                <h2 id="new-arrivals" class="section-heading">НОВИНКИ</h2>
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
