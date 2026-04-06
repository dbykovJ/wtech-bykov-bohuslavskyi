@extends('layouts.admin')

@section('title', 'SuperDash — Products')

@section('content')
    <div class="products-header">
        <h1 class="admin-page-title">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Product
        </a>
    </div>

    <div class="product-grid">
        @forelse ($products as $product)
            <div class="product-card">
                <div class="product-card__image" style="background: none;">
                    @if ($product->image_url)
                        <img style="border-radius: 12px; background: node; object-fit:contain;" src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                            class="product-card__img" />
                    @endif
                    {{-- <button class="product-card__arrow product-card__arrow--prev" aria-label="Previous">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </button>
                    <button class="product-card__arrow product-card__arrow--next" aria-label="Next">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </button> --}}
                </div>
                <div class="product-card__body">
                    <div class="product-card__row">
                        <span class="product-card__name">{{ $product->name }}</span>
                    </div>
                    <p class="product-card__price">${{ $product->price }}</p>
                    <p style="color: rgb(118, 97, 97); font-size: 14px; min-height: 40px;">{{ Str::limit($product->description, 100) }}</p>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-outline" style="background: dodgerblue; color: white; flex: 2;">Edit Product</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="btn-outline" style="flex: 1;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none;">Delete</button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <p class="admin-empty">No products yet.</p>
        @endforelse
    </div>
@endsection
