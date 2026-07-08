@extends('layouts.app')

@section('title', 'Товар — Look of Today')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" />
@endpush


@section('content')
    <main>

        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Головна</a>
                <span>›</span>
                <a href="{{ route('category') }}">Магазин</a>
                <span>›</span>
                <a href="{{ route('category') }}">Футболки</a>
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

                            // Pre-select the first in-stock size for this color.
                            $defaultSize = null;
                            foreach ($defaultSizes as $size) {
                                if ($size['in_stock'] ?? false) {
                                    $defaultSize = $size;
                                    break;
                                }
                            }
                            $defaultSizeCode = $defaultSize['code'] ?? null;
                        @endphp

                        <div class="product-info__section">
                            <span class="product-info__label">Оберіть колір</span>
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
                            <span class="product-info__label">Оберіть розмір</span>
                            <div class="product-sizes" id="product-sizes" data-default-size-code="{{ $defaultSizeCode }}">
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
                                    <span class="product-sizes__empty">Для цього кольору немає доступних розмірів.</span>
                                @endforelse
                            </div>

                            <div id="stock-info" class="product-stock-info">
                                @if ($defaultSize)
                                    В наявності: {{ $defaultSize['count'] }}
                                @else
                                    Оберіть розмір, щоб побачити наявність
                                @endif
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
                            <input type="hidden" name="size" id="input-size" value="{{ $defaultSizeCode ?? '' }}">
                            <input type="hidden" name="count" id="input-count" value="1">
                            <button type="submit" class="add-to-cart-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                Додати в кошик
                            </button>
                        </form>

                    </div>
                </div>
            </section>

            <div class="reviews-header">
                <div class="reviews-header__line"></div>
                <span class="reviews-header__title">Рейтинг і відгуки</span>
                <div class="reviews-header__line"></div>
                <div class="reviews-header__nav">
                    <button class="reviews-nav-btn">&#8592;</button>
                    <button class="reviews-nav-btn">&#8594;</button>
                </div>
            </div>

            <section class="reviews">
                <div class="reviews__toolbar">
                    <span class="reviews__count">Усі відгуки <span class="reviews__count-num">({{ $product->reviews->count() }})</span></span>
                    <div class="reviews__toolbar-right">
                        <div class="reviews__sort">
                            Найновіші
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="reviews__grid">
                    @forelse ($product->reviews->sortByDesc('created_at') as $review)
                        <div class="review-card">
                            <div class="review-card__header">
                                <span class="star-rating" style="--rating: {{ $review->rating }}">★★★★★</span>
                                @if (Auth::check() && Auth::id() === $review->user_id)
                                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="review-card__more" onclick="return confirm('Видалити цей відгук?')">×</button>
                                    </form>
                                @endif
                            </div>
                            <div class="review-card__author">{{ $review->user->name }} <span class="review-card__verified">✔</span></div>
                            @if ($review->body)
                                <p class="review-card__text">"{{ $review->body }}"</p>
                            @endif
                            <span class="review-card__date">Опубліковано {{ $review->created_at->format('d.m.Y') }}</span>
                        </div>
                    @empty
                        @if (Auth::check())
                            <p style="grid-column: 1/-1; text-align: center; color: #999;">Відгуків поки немає. Станьте першим, хто залишить відгук про цей товар!</p>
                        @else
                            <p style="grid-column: 1/-1; text-align: center; color: #999;">
                                <a href="{{ route('login') }}">Увійдіть</a>, щоб переглядати та писати відгуки.
                            </p>
                        @endif
                    @endforelse
                </div>

                @if (Auth::check())
                    <div style="margin-top: 20px; padding: 16px; border-radius: 8px; background-color: #f9f9f9;">
                        <h3 style="margin-top: 0; font-size: 16px;">Написати відгук</h3>
                        <p style="margin: 10px 0; font-size: 14px; color: #666;">Ви придбали цей товар? Поділіться своїми враженнями!</p>
                        <a href="{{ route('account.orders') }}" class="account-btn" style="display: inline-block; padding: 10px 20px;">Залишити відгук</a>
                    </div>
                @else
                    <div style="margin-top: 20px; padding: 16px; border-radius: 8px; background-color: #f0f0f0;">
                        <p style="margin: 0; font-size: 14px; color: #666;">
                            <a href="{{ route('login') }}" style="color: #333; font-weight: 500;">Увійдіть</a>, щоб написати відгук на основі вашої покупки.
                        </p>
                    </div>
                @endif
            </section>

            <section class="also-like">
                <h2 class="section-heading">ВАМ ТАКОЖ МОЖЕ СПОДОБАТИСЬ</h2>
                <div class="also-like__grid">
                    @foreach ($similar as $item)
                    @php
                        $imageUrl      = $item->getFirstImage()?->public_url;
                        $activeSales   = $item->sales;
                        $totalDiscount = $activeSales->sum('discount');
                        $salePrice     = $totalDiscount > 0
                            ? $item->price * (1 - $totalDiscount / 100)
                            : null;
                    @endphp
                    <a href="{{ route('product', ['id' => $item->id]) }}" class="product-card">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="product-card__img" />
                        @else
                            <div class="placeholder product-card__img"></div>
                        @endif
                        <div class="product-meta">
                            <div class="product-name">{{ $item->name }}</div>
                            <div class="star-rating" style="--rating: {{ $item->rating ?? 0 }}">★★★★★</div>
                            <div class="product-price-row">
                                @if ($salePrice)
                                    <span class="product-price">${{ number_format($salePrice, 2) }}</span>
                                    <span class="product-price-original">${{ number_format($item->price, 2) }}</span>
                                    <span class="badge-red">-{{ number_format($totalDiscount) }}%</span>
                                @else
                                    <span class="product-price">${{ number_format($item->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
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

        let selectedColorBtn = colorsContainer?.querySelector('.product-color--active') ?? null;
        let selectedSizeBtn = sizesContainer?.querySelector('.product-size--active') ?? null;

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

        // Gallery swap — runs immediately since script is at bottom of body
        (function () {
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
        })();

        document.addEventListener('DOMContentLoaded', function() {
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
            let selectedSizeCode = sizesContainer.dataset.defaultSizeCode || null;

            function firstInStockSizeCode(colorId) {
                const group = colorSizes[colorId];
                if (!group || !Array.isArray(group.sizes)) {
                    return null;
                }

                const match = group.sizes.find(function(s) { return s.in_stock; });
                return match ? match.code : null;
            }

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
                    stockInfoEl.textContent = 'Оберіть розмір, щоб побачити наявність';
                    currentMaxQty = Infinity;
                    return;
                }

                stockInfoEl.textContent = 'В наявності: ' + size.count;
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
                        '<span class="product-sizes__empty">Для цього кольору немає доступних розмірів.</span>';
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
                    selectedSizeCode = firstInStockSizeCode(selectedColorId);

                    document.getElementById('input-color-id').value = selectedColorId || '';
                    document.getElementById('input-size').value = selectedSizeCode || '';
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
