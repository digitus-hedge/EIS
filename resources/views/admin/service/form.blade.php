@extends('admin.layout')
@section('title', $service->exists ? 'Edit Service' : 'Add Service')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle"></i>
        Please fill below fields before submitting
    </div>
@endif

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: @json(session('success')),
                confirmButtonColor: '#3b3b58',
                timer: 2500,
                timerProgressBar: true
            });
        });
    </script>
@endif

<div class="form-header">
    <h4>
        <i class="bi bi-{{ $service->exists ? 'pencil-square' : 'plus-circle' }}"></i>
        {{ $service->exists ? 'Edit Service' : 'Add Service' }}
    </h4>
    <a href="{{ route('admin.home.services') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to list
    </a>
</div>

<form action="{{ $service->exists ? route('admin.home.services.update', $service->id) : route('admin.home.services.store') }}"
      method="POST" enctype="multipart/form-data" class="banner-form" id="serviceForm">
    @csrf
    @if ($service->exists)
        @method('PUT')
    @endif

    <div class="container-fluid px-0">
        <div class="row">

            {{-- Title + Sort Order --}}
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Title</label>
                        <input type="text" name="title" value="{{ old('title', $service->title) }}"
                               class="{{ $errors->has('title') ? 'input-error' : '' }}"
                               placeholder="e.g. API Threading Services">
                        @error('title')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Short Description --}}
            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-card-text"></i> Short Description</label>
                        <p class="hint-text">Shown on the homepage card and listing page under the title.</p>
                        <textarea name="description" rows="3"
                                  class="{{ $errors->has('description') ? 'input-error' : '' }}"
                                  placeholder="e.g. API threading and machining solutions for critical oilfield connections.">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-image"></i> Hero / Card Image</label>
                    <p class="hint-text">Used on listing cards and the service detail page hero. Max <strong>2MB</strong>.</p>

                    <div class="image-upload-box">
                        <div class="preview-wrap">
                            @if ($service->image)
                                <img src="{{ Storage::url($service->image) }}" class="preview-img" id="preview-image">
                            @else
                                <div class="preview-placeholder" id="preview-image">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>
                        <label class="upload-btn {{ $errors->has('image') ? 'upload-btn-error' : '' }}">
                            <i class="bi bi-upload"></i> Choose file
                            <input type="file" name="image" accept="image/*"
                                   onchange="previewImage(this, 'preview-image')" hidden>
                        </label>
                        @error('image')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Process Description --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-file-text"></i> Process Description</label>
                    <p class="hint-text">Detail page — "Process Description" column under Service Overview.</p>
                    <textarea name="process_description" rows="5"
                              class="{{ $errors->has('process_description') ? 'input-error' : '' }}"
                              placeholder="Describe the step-by-step process...">{{ old('process_description', $service->process_description) }}</textarea>
                </div>
            </div>

            {{-- Technical Scope (dynamic list) --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-list-check"></i> Technical Scope &amp; Capabilities</label>
                    <p class="hint-text">Detail page — bullet list next to Process Description.</p>

                    <div id="scopeRows"></div>
                    <button type="button" id="addScopeBtn" class="btn-add-row">
                        <i class="bi bi-plus-circle"></i> Add Point
                    </button>
                </div>
            </div>

            {{-- Specifications Table (dynamic rows) --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-table"></i> Technical Specifications &amp; Features</label>
                    <p class="hint-text">Detail page — Specification / Details / Compliance table.</p>

                    <div class="specs-table-header">
                        <span>Specification</span>
                        <span>Details</span>
                        <span>Compliance</span>
                        <span></span>
                    </div>
                    <div id="specRows"></div>
                    <button type="button" id="addSpecBtn" class="btn-add-row">
                        <i class="bi bi-plus-circle"></i> Add Row
                    </button>
                </div>
            </div>

            {{-- Gallery --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-images"></i> Gallery (up to 3 images)</label>
                    <p class="hint-text">Detail page — Service Images gallery.</p>

                    <div class="gallery-existing">
                        @foreach ($service->gallery ?? [] as $img)
                            <div class="gallery-thumb">
                                <img src="{{ Storage::url($img) }}">
                                <label class="gallery-remove">
                                    <input type="checkbox" name="remove_gallery[]" value="{{ $img }}">
                                    <i class="bi bi-trash3"></i> Remove
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Add gallery images
                        <input type="file" name="gallery[]" accept="image/*" multiple hidden
                               onchange="this.nextElementSibling.textContent = this.files.length + ' file(s) selected'">
                    </label>
                    <span class="file-name"></span>
                    @error('gallery.*')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-actions">
                    <a href="{{ route('admin.home.services') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg"></i>
                        {{ $service->exists ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

<template id="scopeRowTemplate">
    <div class="scope-row">
        <input type="text" name="technical_scope[]" placeholder="e.g. Thread types: Buttress, LTC, STC">
        <button type="button" class="btn-remove-row"><i class="bi bi-trash3"></i></button>
    </div>
</template>

<template id="specRowTemplate">
    <div class="specs-row">
        <input type="text" name="specifications[__INDEX__][specification]" placeholder="e.g. Thread Form">
        <input type="text" name="specifications[__INDEX__][details]" placeholder="e.g. Buttress thread form">
        <input type="text" name="specifications[__INDEX__][compliance]" placeholder="e.g. API 5B">
        <button type="button" class="btn-remove-row"><i class="bi bi-trash3"></i></button>
    </div>
</template>

<script>
    // ===== Technical Scope (simple list) =====
    const existingScope = @json(old('technical_scope', $service->technical_scope ?? []));
    const scopeContainer = document.getElementById('scopeRows');
    const scopeTemplate = document.getElementById('scopeRowTemplate');

    function addScopeRow(value = '') {
        const clone = scopeTemplate.content.cloneNode(true);
        const row = clone.querySelector('.scope-row');
        row.querySelector('input').value = value;
        row.querySelector('.btn-remove-row').addEventListener('click', () => row.remove());
        scopeContainer.appendChild(row);
    }

    document.getElementById('addScopeBtn').addEventListener('click', () => addScopeRow());

    if (existingScope.length > 0) {
        existingScope.forEach(v => addScopeRow(v));
    } else {
        addScopeRow();
    }

    // ===== Specifications (table rows) =====
    const existingSpecs = @json(old('specifications', $service->specifications ?? []));
    const specContainer = document.getElementById('specRows');
    const specTemplate = document.getElementById('specRowTemplate');
    let specIndex = 0;

    function addSpecRow(data = {}) {
        const clone = specTemplate.content.cloneNode(true);
        const row = clone.querySelector('.specs-row');

        row.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace('__INDEX__', specIndex);
        });

        row.querySelector('input[name$="[specification]"]').value = data.specification ?? '';
        row.querySelector('input[name$="[details]"]').value = data.details ?? '';
        row.querySelector('input[name$="[compliance]"]').value = data.compliance ?? '';

        row.querySelector('.btn-remove-row').addEventListener('click', () => row.remove());

        specContainer.appendChild(row);
        specIndex++;
    }

    document.getElementById('addSpecBtn').addEventListener('click', () => addSpecRow());

    if (existingSpecs.length > 0) {
        existingSpecs.forEach(row => addSpecRow(row));
    } else {
        addSpecRow();
    }

    // ===== Image preview =====
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-img';
                    img.id = previewId;
                    preview.replaceWith(img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; display:flex; align-items:center; gap:8px; }
    .alert-error { background: #fdecea; color: #c0392b; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .form-header h4 { display: flex; align-items: center; gap: 8px; color: #1e1e2d; }
    .btn-back { display: flex; align-items: center; gap: 6px; color: #3b3b58; text-decoration: none; font-size: 14px; }
    .btn-back:hover { text-decoration: underline; }
    .banner-form { width: 100%; }
    .form-card { background: #fff; padding: 22px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 18px; }
    .form-group { margin-bottom: 0; }
    .form-group label { display: flex; align-items: center; gap: 6px; margin-bottom: 7px; font-weight: 600; font-size: 14px; color: #333; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; font-family: inherit; resize: vertical; }
    .form-group input:focus, .form-group textarea:focus { border-color: #3b3b58; }
    .section-label { display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 14px; color: #333; margin-bottom: 8px; }
    .hint-text { font-size: 12.5px; color: #888; margin-bottom: 12px; }
    .image-upload-box { display: flex; flex-direction: column; align-items: flex-start; width: 100%; max-width: 300px; }
    .preview-wrap { width: 100%; }
    .preview-img { width: 100%; height: 180px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; margin-bottom: 10px; }
    .preview-placeholder { width: 100%; height: 180px; display: flex; align-items: center; justify-content: center; background: #f4f6f9; border-radius: 6px; border: 1px dashed #ddd; color: #bbb; font-size: 28px; margin-bottom: 10px; }
    .upload-btn { display: inline-flex; align-items: center; gap: 6px; background: #f4f6f9; color: #3b3b58; padding: 7px 14px; border-radius: 6px; font-size: 13px; cursor: pointer; border: 1px solid #ddd; }
    .upload-btn:hover { background: #e9ecf2; }
    .upload-btn-error { border-color: #e74c3c !important; }
    .field-error { display: flex; align-items: center; gap: 5px; color: #e74c3c; font-size: 12.5px; margin-top: 6px; }
    .input-error { border-color: #e74c3c !important; background: #fff8f8; }
    .file-name { font-size: 13px; color: #666; margin-left: 10px; }

    .scope-row { display: flex; gap: 10px; margin-bottom: 10px; }
    .scope-row input { flex: 1; }

    .specs-table-header {
        display: grid; grid-template-columns: 1fr 1.5fr 1fr 40px; gap: 12px;
        font-size: 12px; font-weight: 700; text-transform: uppercase; color: #888;
        padding: 0 4px 10px; border-bottom: 1px solid #eee; margin-bottom: 12px;
    }
    .specs-row {
        display: grid; grid-template-columns: 1fr 1.5fr 1fr 40px; gap: 12px;
        margin-bottom: 10px; align-items: center;
    }

    .btn-remove-row {
        display: flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 6px; border: 1px solid #f0d0d0;
        background: #fdecea; color: #c0392b; cursor: pointer;
    }
    .btn-remove-row:hover { background: #c0392b; color: #fff; }

    .btn-add-row {
        display: inline-flex; align-items: center; gap: 6px; background: #f4f6f9;
        color: #3b3b58; border: 1px solid #ddd; padding: 9px 16px; border-radius: 6px;
        font-size: 14px; cursor: pointer; margin-top: 4px;
    }
    .btn-add-row:hover { background: #e9ecf2; }

    .gallery-existing { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 16px; }
    .gallery-thumb { width: 120px; }
    .gallery-thumb img { width: 100%; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; margin-bottom: 6px; }
    .gallery-remove { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #c0392b; cursor: pointer; }

    .form-actions { display: flex; gap: 12px; margin-top: 6px; }
    .btn-cancel { padding: 11px 22px; border-radius: 6px; border: 1px solid #ddd; color: #555; text-decoration: none; font-size: 14px; }
    .btn-cancel:hover { background: #f4f6f9; }
    .btn-submit { display: flex; align-items: center; gap: 7px; background: #3b3b58; color: #fff; border: none; padding: 11px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #2b2b42; }
</style>

@endsection
