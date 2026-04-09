@extends('layouts.app')

@section('title', 'Product — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" />
@endpush


@section('content')
    <main>

        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>›</span>
                <a href="{{ route('category') }}">Shop</a>
                <span>›</span>
                <a href="{{ route('category') }}">T-shirts</a>
                <span>›</span>
                <span class="breadcrumb__current">{{$product->name}}</span>
            </nav>

            <section class="product-detail">
                <div class="product-gallery">
                    <div class="product-gallery__thumbs">
                        <img src="{{asset('storage/' . $product->image_url)}}" alt="{{$product->name}}"
                             class="product-gallery__thumb product-gallery__thumb--active" />
                        <div class="product-gallery__thumb placeholder"></div>
                        <div class="product-gallery__thumb placeholder"></div>
                    </div>
                    <div class="product-gallery__main">
                        <img src="{{asset('storage/' . $product->image_url)}}" alt="{{$product->name}}"
                             class="product-gallery-image__main" />
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-info__name heading">{{$product->name}}</h1>

                    <div class="product-info__rating">
                        <span class="star-rating" style="--rating: {{$product->rating}}">★★★★★</span>
                        <span class="product-info__rating-count">{{$product->rating}}/5</span>
                    </div>

                    <div class="product-info__price-row">
                        @if($product->sales->isNotEmpty())
                            @php $total_discount = $product->sales->sum('discount') @endphp
                            <span
                                class="product-price">${{number_format($product->price - ($product->price*($total_discount/100)), 2)}}</span>
                            <span
                                class="product-price-original">${{number_format($product->price, 2)}}</span>
                            <span
                                class="badge-red">-{{number_format($total_discount)}}%</span>

                        @else
                            <span class="product-price">${{number_format($product->price, 2)}}</span>
                        @endif
                    </div>

                    <p class="product-info__desc">
                        {{ $product->description }}
                    </p>

                    <div class="product-info__divider"></div>

                    <div class="product-info__section">
                        <span class="product-info__label">Choose Colors</span>
                        <div class="product-colors">
                            <button class="product-color" style="background:#6b7c5e;" aria-label="Olive"></button>
                            <button class="product-color product-color--active" style="background:#2a2a2a;"
                                    aria-label="Black"></button>
                            <button class="product-color" style="background:#3a5a8a;" aria-label="Blue"></button>
                        </div>
                    </div>

                    <div class="product-info__divider"></div>

                    <div class="product-info__section">
                        <span class="product-info__label">Choose Size</span>
                        <div class="product-sizes">
                            <button class="product-size">Small</button>
                            <button class="product-size">Medium</button>
                            <button class="product-size product-size--active">Large</button>
                            <button class="product-size">X-Large</button>
                        </div>
                    </div>

                    <div class="product-info__divider"></div>

                    <div class="product-info__actions">
                        <div class="product-qty">
                            <button class="product-qty__btn" onclick="changeQty(-1)">−</button>
                            <span class="product-qty__val" id="qty">1</span>
                            <button class="product-qty__btn" onclick="changeQty(1)">+</button>
                        </div>
                        <button class="product-add-btn" onclick="window.location.href='{{ route('cart') }}'">Add to
                            Cart
                        </button>
                    </div>
                </div>
            </section>

            <div class="reviews-header">
                <div class="reviews-header__line"></div>
                <span class="reviews-header__title">Rating &amp; Reviews</span>
                <div class="reviews-header__line"></div>
                <div class="reviews-header__nav">
                    <button class="reviews-nav-btn">&#8592;</button>
                    <button class="reviews-nav-btn">&#8594;</button>
                </div>
            </div>

            <section class="reviews">
                <div class="reviews__toolbar">
                    <span class="reviews__count">All Reviews <span class="reviews__count-num">(13)</span></span>
                    <div class="reviews__toolbar-right">
                        <button class="reviews__filter-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <line x1="4" y1="6" x2="20" y2="6" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                                <line x1="11" y1="18" x2="13" y2="18" />
                            </svg>
                        </button>
                        <div class="reviews__sort">
                            Latest
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </div>
                        <button class="reviews__write-btn">Write a Review</button>
                    </div>
                </div>

                <div class="reviews__grid">
                    <div class="review-card">
                        <div class="review-card__header">
                            <span class="star-rating" style="--rating: 4">★★★★★</span>
                            <button class="review-card__more">···</button>
                        </div>
                        <div class="review-card__author">Olivia P. <span class="review-card__verified">✔</span></div>
                        <p class="review-card__text">"As a UI/UX enthusiast, I value simplicity and functionality. This
                            T-shirt not only represents those principles but also feels great to wear."</p>
                        <span class="review-card__date">Posted on August 17, 2023</span>
                    </div>

                    <div class="review-card">
                        <div class="review-card__header">
                            <span class="star-rating" style="--rating: 4">★★★★★</span>
                            <button class="review-card__more">···</button>
                        </div>
                        <div class="review-card__author">Ethan R. <span class="review-card__verified">✔</span></div>
                        <p class="review-card__text">"This T-shirt is a must-have for anyone who appreciates good
                            design. The minimalistic yet stylish pattern caught my eye, and the fit is perfect."</p>
                        <span class="review-card__date">Posted on August 16, 2023</span>
                    </div>

                    <div class="review-card">
                        <div class="review-card__header">
                            <span class="star-rating" style="--rating: 5">★★★★★</span>
                            <button class="review-card__more">···</button>
                        </div>
                        <div class="review-card__author">Alex M. <span class="review-card__verified">✔</span></div>
                        <p class="review-card__text">"I exceeded my expectations! The colors are vibrant and the print
                            quality is top-notch."</p>
                        <span class="review-card__date">Posted on August 15, 2023</span>
                    </div>
                </div>

                <div class="reviews__pagination">
                    <button class="reviews__page-btn">← Previous</button>
                    <span class="reviews__page-info">Page 1 of 2</span>
                    <button class="reviews__page-btn reviews__page-btn--active">Next →</button>
                </div>
            </section>

            <section class="also-like">
                <h2 class="section-heading">YOU MIGHT ALSO LIKE</h2>
                <div class="also-like__grid">
                    {{--                    <a href="{{ route('product') }}" class="product-card">--}}
                    <div class="product-card__img placeholder"></div>
                    <div class="product-card__info">
                        <div class="product-name">Item 1</div>
                        <div class="product-meta">
                            <span class="star-rating" style="--rating: 4">★★★★★</span>
                            <span class="product-info__rating-count">4/5</span>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-20%</span>
                        </div>
                    </div>
                    </a>
                    {{--                    <a href="{{ route('product') }}" class="product-card">--}}
                    <div class="product-card__img placeholder"></div>
                    <div class="product-card__info">
                        <div class="product-name">Item 2</div>
                        <div class="product-meta">
                            <span class="star-rating" style="--rating: 5">★★★★★</span>
                            <span class="product-info__rating-count">5/5</span>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-20%</span>
                        </div>
                    </div>
                    </a>
                    {{--                    <a href="{{ route('product') }}" class="product-card">--}}
                    <div class="product-card__img placeholder"></div>
                    <div class="product-card__info">
                        <div class="product-name">Item 3</div>
                        <div class="product-meta">
                            <span class="star-rating" style="--rating: 4">★★★★★</span>
                            <span class="product-info__rating-count">4/5</span>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-20%</span>
                        </div>
                    </div>
                    </a>
                    {{--                    <a href="{{ route('product') }}" class="product-card">--}}
                    <div class="product-card__img placeholder"></div>
                    <div class="product-card__info">
                        <div class="product-name">Item 4</div>
                        <div class="product-meta">
                            <span class="star-rating" style="--rating: 4.5">★★★★★</span>
                            <span class="product-info__rating-count">4.5/5</span>
                        </div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-20%</span>
                        </div>
                    </div>
                    </a>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function changeQty(delta) {
            const el = document.getElementById("qty");
            let val = parseInt(el.textContent) + delta;
            if (val < 1) val = 1;
            el.textContent = val;
        }
    </script>
@endpush
