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

    @if ($errors->any())
        <div class="form-errors" style="margin-bottom: 16px; color: #dc2626;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="photo-upload">
        <div class="photo-upload__previews" id="photo-previews">
            <div id="existing-photo-previews">
                @if(isset($product))
                    @foreach($product->images ?? [] as $image)
                        <div class="photo-preview-slot" data-image-id="{{ $image->id }}">
                            <img src="{{ $image->public_url }}"
                                 class="photo-upload__preview"
                                 alt="Product image" />
                            <button type="button" class="photo-preview-remove" data-remove-existing aria-label="Remove image">&times;</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <div id="new-photo-previews"></div>
        </div>
        <label class="photo-upload__trigger" for="photo-input-0" id="photo-upload-trigger">
            <div class="photo-upload__icon">
            </div>
            <span class="photo-upload__label">Upload Photos</span>
        </label>
        <div id="photo-inputs" style="display: none;">
            <input type="file"
                   id="photo-input-0"
                   name="images[]"
                   accept="image/*"
                   multiple
                   class="photo-input" />
        </div>
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
    const editForm = document.querySelector('.edit-form-card');
    const newPreviews = document.getElementById('new-photo-previews');
    const existingPreviews = document.getElementById('existing-photo-previews');
    const inputsContainer = document.getElementById('photo-inputs');
    const uploadTrigger = document.getElementById('photo-upload-trigger');
    const selectedSignatures = new Set();
    let inputIndex = 0;

    function fileSignature(file) {
        return `${file.name}__${file.size}__${file.lastModified}`;
    }

    function addPreview(file) {
        const signature = fileSignature(file);

        if (selectedSignatures.has(signature)) {
            return;
        }

        selectedSignatures.add(signature);

        const slot = document.createElement('div');
        slot.className = 'photo-preview-slot';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'photo-upload__preview';
        img.alt = file.name;
        img.onload = () => URL.revokeObjectURL(img.src);

        slot.appendChild(img);
        newPreviews.appendChild(slot);
    }

    function bindInput(input) {
        input.addEventListener('change', function () {
            Array.from(this.files || []).forEach(addPreview);

            if ((this.files || []).length > 0) {
                createNextInput();
            }
        });
    }

    function createNextInput() {
        inputIndex += 1;

        const nextInput = document.createElement('input');
        nextInput.type = 'file';
        nextInput.id = `photo-input-${inputIndex}`;
        nextInput.name = 'images[]';
        nextInput.accept = 'image/*';
        nextInput.multiple = true;
        nextInput.className = 'photo-input';

        inputsContainer.appendChild(nextInput);
        bindInput(nextInput);

        uploadTrigger.setAttribute('for', nextInput.id);
    }

    existingPreviews?.addEventListener('click', function (event) {
        const button = event.target.closest('[data-remove-existing]');
        if (!button) {
            return;
        }

        const slot = button.closest('[data-image-id]');
        const imageId = slot?.dataset.imageId;

        if (!slot || !imageId) {
            return;
        }

        slot.remove();

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_image_ids[]';
        hiddenInput.value = imageId;
        editForm.appendChild(hiddenInput);
    });

    const firstInput = document.getElementById('photo-input-0');
    bindInput(firstInput);
</script>
@endpush
