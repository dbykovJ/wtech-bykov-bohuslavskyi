@extends('layouts.admin')

@section('title', 'SuperDash — Edit Product')

@section('content')
<div class="edit-header">
    <a href="{{ route('admin.products') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <h1 class="admin-page-title" id="page-title">Item 1: Edit</h1>
</div>

<form class="edit-form-card" id="product-form" onsubmit="handleSubmit(event)">
    <div class="photo-upload">
        <div class="photo-upload__previews" id="photo-previews"></div>
        <label class="photo-upload__trigger" for="photo-input">
            <div class="photo-upload__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <span class="photo-upload__label">Upload Photo</span>
            <input type="file" id="photo-input" accept="image/*" multiple hidden />
        </label>
    </div>

    <div class="form-grid">
        <div class="form-field">
            <label class="form-label" for="item-name">Item Name</label>
            <input class="form-input" type="text" id="item-name" name="name" placeholder="Enter item name" required />
        </div>

        <div class="form-field">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-input form-textarea" id="description" name="description" placeholder="Enter item description"></textarea>
        </div>

        <div class="form-field">
            <label class="form-label" for="price">Price</label>
            <div class="input-prefix-wrap">
                <span class="input-prefix">$</span>
                <input class="form-input input-with-prefix" type="number" id="price" name="price" placeholder="0.00" min="0" step="0.01" required />
            </div>
        </div>

        <div class="form-field">
            <label class="form-label" for="amount">Amount of Product</label>
            <input class="form-input" type="number" id="amount" name="amount" placeholder="Enter amount of product" min="0" required />
        </div>

        <div class="form-field">
            <label class="form-label">Available Colors</label>
            <div class="color-picker">
                <div class="color-swatches" id="color-swatches">
                    <button type="button" class="color-swatch" style="background:#111827" data-color="#111827" title="Black"></button>
                    <button type="button" class="color-swatch" style="background:#fff;border-color:#d1d5db" data-color="#ffffff" title="White"></button>
                    <button type="button" class="color-swatch" style="background:#ef4444" data-color="#ef4444" title="Red"></button>
                    <button type="button" class="color-swatch" style="background:#3b82f6" data-color="#3b82f6" title="Blue"></button>
                    <button type="button" class="color-swatch" style="background:#10b981" data-color="#10b981" title="Green"></button>
                    <button type="button" class="color-swatch" style="background:#f59e0b" data-color="#f59e0b" title="Yellow"></button>
                    <button type="button" class="color-swatch color-swatch--add" title="Custom color">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </button>
                </div>
                <p class="color-selected-text" id="color-text">No color selected</p>
            </div>
        </div>

        <div class="form-field">
            <label class="form-label" for="size">Size</label>
            <select class="form-input form-select" id="size" name="size">
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M" selected>M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
                <option value="one-size">One size</option>
            </select>
        </div>

        <div class="form-field">
            <label class="form-label" for="category">Category</label>
            <select class="form-input form-select" id="category" name="category">
                <option value="">Select category</option>
                <option value="clothing">Clothing</option>
                <option value="shoes">Shoes</option>
                <option value="accessories">Accessories</option>
                <option value="electronics">Electronics</option>
            </select>
        </div>

        <div class="form-field">
            <label class="form-label" for="sku">SKU</label>
            <input class="form-input" type="text" id="sku" name="sku" placeholder="e.g. ITEM-001" />
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.products') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary btn-primary--large" id="submit-btn">Save Changes</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function handleSubmit(e) {
        e.preventDefault();
        window.location.href = '{{ route('admin.products') }}';
    }

    document.querySelectorAll('.color-swatch:not(.color-swatch--add)').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('color-text').textContent = this.getAttribute('title');
        });
    });

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
