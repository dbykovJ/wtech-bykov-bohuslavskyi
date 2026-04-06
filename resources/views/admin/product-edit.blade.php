@extends('layouts.admin')

@section('title', isset($product) ? 'SuperDash — Edit Product' : 'SuperDash — Add Product')

@section('content')
<div class="edit-header">
    <a href="{{ route('admin.products.index') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <h1 class="admin-page-title">{{ isset($product) ? $product->name . ': Edit' : 'Add Product' }}</h1>
</div>

<form class="edit-form-card"
      action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    <div class="photo-upload">
        <div class="photo-upload__previews" id="photo-previews">
            @if(isset($product) && $product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" class="photo-upload__preview" alt="Current image" />
            @endif
        </div>
        <label class="photo-upload__trigger" for="photo-input">
            <div class="photo-upload__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <span class="photo-upload__label">Upload Photo</span>
            <input type="file" id="photo-input" name="image" accept="image/*" hidden />
        </label>
    </div>

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
                previews.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
