@extends('layouts.admin')

@section('title', 'SuperDash — Products')

@section('content')
<div class="products-header">
    <h1 class="admin-page-title">Products</h1>
    <a href="{{ route('admin.product-edit') }}" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Product
    </a>
</div>

<div class="product-grid">
    @foreach([['Item 1', 1], ['Item 2', 2], ['Item 3', 3]] as [$name, $id])
    <div class="product-card">
        <div class="product-card__image">
            <button class="product-card__arrow product-card__arrow--prev" aria-label="Previous">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="product-card__arrow product-card__arrow--next" aria-label="Next">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
        <div class="product-card__body">
            <div class="product-card__row">
                <span class="product-card__name">{{ $name }}</span>
                <button class="product-card__wishlist" aria-label="Wishlist">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
            </div>
            <p class="product-card__price">$120.00</p>
            <div class="product-card__rating">
                <span class="stars">★★★★☆</span>
                <span class="rating-count">(131)</span>
            </div>
            <a href="{{ route('admin.product-edit') }}?id={{ $id }}" class="btn-outline">Edit Product</a>
        </div>
    </div>
    @endforeach
</div>
@endsection
