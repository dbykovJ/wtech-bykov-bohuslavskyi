@extends('layouts.admin')

@section('title', isset($product) ? 'SuperDash — Редагування товару' : 'SuperDash — Додавання товару')

@section('content')
    <div class="edit-header">
        <a href="{{ route('admin.products.index') }}" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h1 class="admin-page-title">{{ isset($product) ? $product->name . ': Редагування' : 'Додавання товару' }}</h1>
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
                                     alt="Зображення товару" />
                                <button type="button" class="photo-preview-remove" data-remove-existing aria-label="Видалити зображення">&times;</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div id="new-photo-previews"></div>
            </div>
            <label class="photo-upload__trigger" for="photo-input-0" id="photo-upload-trigger">
                <div class="photo-upload__icon">
                </div>
                <span class="photo-upload__label">Завантажити фото</span>
            </label>
            <div id="photo-inputs" style="display: none;">
                <input type="file"
                       id="photo-input-0"
                       name="images[]"
                       accept="image/*"
                       multiple
                       class="photo-input" />
            </div>
            @error('images')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-grid">
            <div class="form-field">
                <label class="form-label" for="item-name">Назва товару</label>
                <input class="form-input" type="text" id="item-name" name="name"
                       value="{{ old('name', $product->name ?? '') }}"
                       placeholder="Введіть назву товару" required />
            </div>

            <div class="form-field">
                <label class="form-label" for="description">Опис</label>
                <textarea class="form-input form-textarea" id="description" name="description"
                          placeholder="Введіть опис товару">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="form-field">
                <label class="form-label" for="price">Ціна</label>
                <div class="input-prefix-wrap">
                    <span class="input-prefix">$</span>
                    <input class="form-input input-with-prefix" type="number" id="price" name="price"
                           value="{{ old('price', $product->price ?? '') }}"
                           placeholder="0.00" min="0" step="0.01" required />
                </div>
            </div>

            <div class="form-field">
                <label class="form-label" for="category_id">Категорія</label>
                <select class="form-input form-select" id="category_id" name="category_id">
                    <option value="">Оберіть категорію</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>{{-- end form-grid --}}

        <div class="form-field" style="margin-top: 8px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <label class="form-label" style="margin:0;">Варіанти кольору та розміру</label>
                <button type="button" id="add-variant-btn" class="btn-primary" style="padding:6px 14px; font-size:13px;">+ Додати варіант</button>
            </div>

            <div id="variants-container">
                @php
                    $existingVariants = isset($product) ? $product->colorSizes->flatMap(function($color) {
                        return $color->pivot ? [['color_id' => $color->id, 'size' => $color->pivot->size instanceof \App\Enums\Size ? $color->pivot->size->value : $color->pivot->size, 'count' => $color->pivot->count]] : [];
                    }) : collect();
                @endphp

                @if($existingVariants->isNotEmpty())
                    @foreach($existingVariants as $i => $variant)
                        <div class="variant-row" style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:10px; align-items:center; margin-bottom:8px; padding:12px; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <select name="variants[{{ $i }}][color_id]" class="form-input variant-color" required>
                                <option value="">Оберіть колір</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ $variant['color_id'] == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="variants[{{ $i }}][size]" class="form-input variant-size" required>
                                <option value="">Оберіть розмір</option>
                                @foreach(['XS','S','M','L','XL','XXL'] as $size)
                                    <option value="{{ $size }}" {{ $variant['size'] === $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="variants[{{ $i }}][count]" class="form-input variant-count" placeholder="Наявність" min="0" value="{{ $variant['count'] }}" required />
                            <button type="button" class="remove-variant-btn" style="padding:8px 12px; background:#fee2e2; color:#dc2626; border:none; border-radius:8px; cursor:pointer; font-size:16px; font-weight:700;">&times;</button>
                        </div>
                    @endforeach
                @endif
            </div>

            <template id="variant-row-template">
                <div class="variant-row" style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:10px; align-items:center; margin-bottom:8px; padding:12px; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                    <select name="" class="form-input variant-color" required>
                        <option value="">Оберіть колір</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                    <select name="" class="form-input variant-size" required>
                        <option value="">Оберіть розмір</option>
                        @foreach(['XS','S','M','L','XL','XXL'] as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="" class="form-input variant-count" placeholder="Наявність" min="0" required />
                    <button type="button" class="remove-variant-btn" style="padding:8px 12px; background:#fee2e2; color:#dc2626; border:none; border-radius:8px; cursor:pointer; font-size:16px; font-weight:700;">&times;</button>
                </div>
            </template>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn-ghost">Скасувати</a>
            <button type="submit" class="btn-primary btn-primary--large">
                {{ isset($product) ? 'Зберегти зміни' : 'Додати товар' }}
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

        // Variant rows
        const variantsContainer = document.getElementById('variants-container');
        const addVariantBtn     = document.getElementById('add-variant-btn');
        const variantTemplate   = document.getElementById('variant-row-template');
        let variantIndex        = {{ isset($existingVariants) ? $existingVariants->count() : 0 }};

        function reindexVariants() {
            variantsContainer.querySelectorAll('.variant-row').forEach((row, i) => {
                row.querySelector('.variant-color').name = `variants[${i}][color_id]`;
                row.querySelector('.variant-size').name  = `variants[${i}][size]`;
                row.querySelector('.variant-count').name = `variants[${i}][count]`;
            });
            variantIndex = variantsContainer.querySelectorAll('.variant-row').length;
        }

        addVariantBtn.addEventListener('click', () => {
            const clone = variantTemplate.content.cloneNode(true);
            const row   = clone.querySelector('.variant-row');
            row.querySelector('.variant-color').name = `variants[${variantIndex}][color_id]`;
            row.querySelector('.variant-size').name  = `variants[${variantIndex}][size]`;
            row.querySelector('.variant-count').name = `variants[${variantIndex}][count]`;
            variantIndex++;
            variantsContainer.appendChild(row);
        });

        variantsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-variant-btn')) {
                e.target.closest('.variant-row').remove();
                reindexVariants();
            }
        });


    </script>
@endpush
