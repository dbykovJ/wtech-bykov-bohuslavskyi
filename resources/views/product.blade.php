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
                <span class="breadcrumb__current">{{ $product->name }}</span>
            </nav>

            <section class="product-detail">
                @php
                    $images = $product->images;
                    $mainImageUrl = $images->first()?->public_url;
                @endphp
                <div class="product-gallery">
                    <div class="product-gallery__thumbs" id="product-gallery-thumbs">
                        @forelse($images as $image)
                            <img src="{{ $image->public_url }}" alt="{{ $product->name }}"
                                 data-gallery-image
                                 class="product-gallery__thumb {{ $loop->first ? 'product-gallery__thumb--active' : '' }}" />
                        @empty
                            <div class="product-gallery__thumb placeholder"></div>
                            <div class="product-gallery__thumb placeholder"></div>
                            <div class="product-gallery__thumb placeholder"></div>
                        @endforelse
                    </div>
                    <div class="product-gallery__main">
                        @if($mainImageUrl)
                            <img src="{{ $mainImageUrl }}" alt="{{ $product->name }}"
                                 id="product-gallery-main-image"
                                 class="product-gallery-image__main" />
                        @else
                            <div class="product-gallery-image__main placeholder"></div>
                        @endif
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-info__name heading">{{ $product->name }}</h1>

                    <div class="product-info__rating">
                        <span class="star-rating" style="--rating: {{ $product->rating }}">★★★★★</span>
                        <span class="product-info__rating-count">{{ $product->rating }}/5</span>
                    </div>

                    <div class="product-info__price-row">
                        @if ($product->sales->isNotEmpty())
                            @php $total_discount = $product->sales->sum('discount') @endphp
                            <span
                                class="product-price">${{ number_format($product->price - $product->price * ($total_discount / 100), 2) }}</span>
                            <span class="product-price-original">${{ number_format($product->price, 2) }}</span>
                            <span class="badge-red">-{{ number_format($total_discount) }}%</span>
                        @else
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <p class="product-info__desc">
                        {{ $product->description }}
                    </p>

                    <div class="product-info__divider"></div>

                    @if (isset($product->colorGroups) && $product->colorGroups->isNotEmpty())
                        @php
                            $defaultColorId = $product->defaultColorId ?? $product->colorGroups->keys()->first();
                            $defaultGroup =
                                $defaultColorId !== null ? $product->colorGroups[$defaultColorId] ?? null : null;
                            $defaultSizes = $defaultGroup['sizes'] ?? collect();
                            // Do not pre-select any size; customer must choose
                            $defaultSizeCode = null;
                        @endphp

                        <div class="product-info__section">
                            <span class="product-info__label">Choose Colors</span>
                            <div class="product-colors" id="product-colors" data-color-sizes='@json($product->colorGroups)'
                                data-default-color-id="{{ $defaultColorId }}">
                                @foreach ($product->colorGroups as $colorId => $group)
                                    @php
                                        $isActive = $colorId === $defaultColorId;
                                        $hasInStock =
                                            $group['has_in_stock'] ??
                                            collect($group['sizes'])->contains(fn($s) => $s['in_stock'] ?? false);
                                    @endphp
                                    <button
                                        class="product-color{{ $isActive ? ' product-color--active' : '' }}{{ !$hasInStock ? ' product-color--disabled' : '' }}"
                                        style="background: {{ $group['hex_code'] }};"
                                        aria-label="{{ $group['name'] }}" data-color-id="{{ $colorId }}"
                                        @if (!$hasInStock) disabled aria-disabled="true" @endif></button>
                                @endforeach
                            </div>
                        </div>

                        <div class="product-info__divider"></div>

                        <div class="product-info__section">
                            <span class="product-info__label">Choose Size</span>
                            <div class="product-sizes" id="product-sizes">
                                @forelse($defaultSizes as $size)
                                    @php
                                        $isDisabled = !($size['in_stock'] ?? false);
                                        $isActive = !$isDisabled && $size['code'] === $defaultSizeCode;
                                    @endphp
                                    <button
                                        class="product-size{{ $isActive ? ' product-size--active' : '' }}{{ $isDisabled ? ' product-size--disabled' : '' }}"
                                        data-size-code="{{ $size['code'] }}"
                                        @if ($isDisabled) disabled aria-disabled="true" @endif>
                                        {{ $size['label'] }}
                                    </button>
                                @empty
                                    <span class="product-sizes__empty">No sizes available for this color.</span>
                                @endforelse
                            </div>

                            <div id="stock-info" class="product-stock-info">
                                Select a size to see availability
                            </div>
                        </div>
                    @endif

                    <div class="product-info__divider"></div>

                    <div class="product-info__actions">
                        <div class="product-qty">
                            <button class="product-qty__btn" onclick="changeQty(-1)">−</button>
                            <span class="product-qty__val" id="qty">1</span>
                            <button class="product-qty__btn" onclick="changeQty(1)">+</button>
                        </div>
                        <form method="POST" action="/cart/add" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="color_id" id="input-color-id" value="{{ $defaultColorId ?? '' }}">
                            <input type="hidden" name="size" id="input-size" value="">
                            <input type="hidden" name="count" id="input-count" value="1">
                            <button type="submit" class="add-to-cart-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                Add to Cart
                            </button>
                        </form>

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
                    {{--                    <a href="{{ route('product') }}" class="product-card"> --}}
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
                    {{--                    <a href="{{ route('product') }}" class="product-card"> --}}
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
                    {{--                    <a href="{{ route('product') }}" class="product-card"> --}}
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
                    {{--                    <a href="{{ route('product') }}" class="product-card"> --}}
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
        let currentMaxQty = Infinity;
        let colorsContainer = document.getElementById('product-colors');
        let sizesContainer = document.getElementById('product-sizes');
        let stockInfoEl = document.getElementById('stock-info');

        let selectedColorBtn = colorsContainer.querySelector('.product-color--active');
        let selectedSizeBtn = sizesContainer.querySelector('.product-size--active');

        function changeQty(delta) {
            const el = document.getElementById("qty");
            let val = parseInt(el.textContent) + delta;
            if (val < 1) val = 1;

            if (Number.isFinite(currentMaxQty) && currentMaxQty > 0 && val > currentMaxQty) {
                val = currentMaxQty;
            }

            el.textContent = val;
            const countInput = document.getElementById('input-count');
            if (countInput) countInput.value = val;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const galleryThumbs = document.querySelectorAll('[data-gallery-image]');
            const mainGalleryImage = document.getElementById('product-gallery-main-image');

            if (mainGalleryImage && galleryThumbs.length > 0) {
                galleryThumbs.forEach(function (thumb) {
                    thumb.addEventListener('click', function () {
                        galleryThumbs.forEach(function (img) {
                            img.classList.remove('product-gallery__thumb--active');
                        });

                        thumb.classList.add('product-gallery__thumb--active');
                        mainGalleryImage.src = thumb.src;
                        mainGalleryImage.alt = thumb.alt;
                    });
                });
            }

            if (!colorsContainer || !sizesContainer) {
                return;
            }

            const colorSizesRaw = colorsContainer.dataset.colorSizes;
            if (!colorSizesRaw) {
                return;
            }

            let colorSizes;
            try {
                colorSizes = JSON.parse(colorSizesRaw);
            } catch (e) {
                console.error('Failed to parse color sizes data', e);
                return;
            }

            let selectedColorId = colorsContainer.dataset.defaultColorId || null;
            // No default size; user must actively choose one
            let selectedSizeCode = null;

            function getSelectedSize() {
                if (!selectedColorId || !selectedSizeCode) {
                    return null;
                }

                const group = colorSizes[selectedColorId];
                if (!group || !Array.isArray(group.sizes)) {
                    return null;
                }

                return group.sizes.find(function(s) {
                    return s.code === selectedSizeCode;
                }) || null;
            }

            function updateStockInfo() {
                if (!stockInfoEl) {
                    return;
                }

                const size = getSelectedSize();

                if (!size) {
                    stockInfoEl.textContent = 'Select a size to see availability';
                    currentMaxQty = Infinity;
                    return;
                }

                stockInfoEl.textContent = 'In stock: ' + size.count;
                currentMaxQty = size.count;

                const qtyEl = document.getElementById('qty');
                if (qtyEl) {
                    let currentQty = parseInt(qtyEl.textContent) || 1;
                    if (currentQty > currentMaxQty) {
                        qtyEl.textContent = currentMaxQty;
                    }
                }
            }

            function bindSizeButtons() {
                const sizeButtons = sizesContainer.querySelectorAll('.product-size');
                sizeButtons.forEach(function(btn) {
                    if (btn.classList.contains('product-size--disabled')) {
                        return;
                    }

                    btn.addEventListener('click', function() {
                        sizeButtons.forEach(function(b) {
                            b.classList.remove('product-size--active');
                        });

                        btn.classList.add('product-size--active');
                        selectedSizeCode = btn.dataset.sizeCode || null;

                        document.getElementById('input-size').value = selectedSizeCode || '';
                        updateStockInfo();
                    });
                });
            }

            function renderSizesForColor(colorId) {
                const group = colorSizes[colorId];
                if (!group || !Array.isArray(group.sizes) || group.sizes.length === 0) {
                    sizesContainer.innerHTML =
                        '<span class="product-sizes__empty">No sizes available for this color.</span>';
                    return;
                }

                let html = '';

                group.sizes.forEach(function(size) {
                    const isDisabled = !size.in_stock;
                    const isActive = !isDisabled && selectedSizeCode && selectedSizeCode === size.code;

                    html += '<button class="product-size' +
                        (isActive ? ' product-size--active' : '') +
                        (isDisabled ? ' product-size--disabled' : '') +
                        '" data-size-code="' + size.code + '"' +
                        (isDisabled ? ' disabled aria-disabled="true"' : '') +
                        '>' + size.label + '</button>';
                });

                sizesContainer.innerHTML = html;
                bindSizeButtons();
            }

            // Initial binding for server-rendered buttons
            bindSizeButtons();

            const colorButtons = colorsContainer.querySelectorAll('.product-color');
            colorButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    if (btn.disabled || btn.classList.contains('product-color--disabled')) {
                        return;
                    }

                    colorButtons.forEach(function(b) {
                        b.classList.remove('product-color--active');
                    });

                    btn.classList.add('product-color--active');
                    selectedColorId = btn.dataset.colorId;
                    selectedSizeCode = null;

                    document.getElementById('input-color-id').value = selectedColorId || '';
                    document.getElementById('input-size').value = '';
                    renderSizesForColor(selectedColorId);

                    updateStockInfo();
                });
            });

            // Render sizes if we have a default color
            if (selectedColorId && colorSizes[selectedColorId]) {
                renderSizesForColor(selectedColorId);
            }

            updateStockInfo();
        });

        function getSelectedOptions() {
            return {
                color_id: selectedColorBtn ? selectedColorBtn.dataset.colorId : null,
                size: selectedSizeBtn ? selectedSizeBtn.dataset.sizeCode : null,
            };
        }
    </script>
@endpush
