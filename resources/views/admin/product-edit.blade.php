@extends('layouts.admin')

@section('title', isset($product) ? 'SuperDash — Edit Product' : 'SuperDash — Add Product')

@section('content')
<div class="edit-header">
    <a href="{{ route('admin.products.index') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <h1 class="admin-page-title">{{ isset($product) ? $product->name . ': Edit' : 'Add Product' }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;padding:.75rem 1rem;background:#d1fae5;border-radius:8px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

<form class="edit-form-card"
      action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    {{-- Existing images --}}
    @if(isset($product) && $product->images->count())
        <div style="margin-bottom:1.5rem;">
            <p class="form-label" style="margin-bottom:.75rem;">Current Images</p>
            <div class="photo-upload__previews" id="existing-previews" style="flex-wrap:wrap;gap:.75rem;display:flex;">
                @foreach($product->images as $image)
                    <div style="position:relative;display:inline-block;">
                        <img src="{{ asset('storage/' . $image->path) }}"
                             class="photo-upload__preview"
                             alt="Product image"
                             style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:2px solid {{ $product->image_url === $image->path ? '#6366f1' : '#e5e7eb' }};" />
                        @if($product->image_url === $image->path)
                            <span style="position:absolute;top:4px;left:4px;background:#6366f1;color:#fff;font-size:10px;padding:1px 5px;border-radius:4px;">Primary</span>
                        @endif
                        <form action="{{ route('admin.products.images.destroy', [$product->id, $image->id]) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Remove this image?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;background:rgba(239,68,68,0.9);border:none;color:#fff;font-size:14px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                &times;
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Upload new images --}}
    <div class="photo-upload">
        <div class="photo-upload__previews" id="photo-previews"></div>
        <label class="photo-upload__trigger" for="photo-input">
            <div class="photo-upload__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <span class="photo-upload__label">Upload Photos</span>
            <input type="file" id="photo-input" name="images[]" accept="image/*" multiple hidden />
        </label>
    </div>

    @error('images.*')
        <p style="color:#ef4444;font-size:.875rem;margin-top:.25rem;">{{ $message }}</p>
    @enderror

    <div class="form-grid">
        <div class="form-field">
            <label class="form-label" for="item-name">Item Name</label>
            <input class="form-input" type="text" id="item-name" name="name"
                   value="{{ old('name', $product->name ?? '') }}"
                   placeholder="Enter item name" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-input form-textarea" id="description" name="description"
                      placeholder="Enter item description">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="form-field">
            <label class="form-label" for="price">Price</label>
            <div class="input-prefix-wrap">
                <span class="input-prefix">$</span>
                <input class="form-input input-with-prefix" type="number" id="price" name="price"
                       value="{{ old('price', $product->price ?? '') }}"
                       placeholder="0.00" min="0" step="0.01" required />
            </div>
        </div>

        <div class="form-field">
            <label class="form-label" for="stock">Amount of Product</label>
            <input class="form-input" type="number" id="stock" name="stock"
                   value="{{ old('stock', $product->stock ?? '') }}"
                   placeholder="Enter amount of product" min="0" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="category_id">Category</label>
            <select class="form-input form-select" id="category_id" name="category_id">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary btn-primary--large">
            {{ isset($product) ? 'Save Changes' : 'Add Product' }}
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('photo-input').addEventListener('change', function() {
        const previews = document.getElementById('photo-previews');
        previews.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'photo-upload__preview';
                img.style.cssText = 'width:100px;height:100px;object-fit:cover;border-radius:8px;';
                previews.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
