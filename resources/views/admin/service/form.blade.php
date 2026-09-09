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
    document.addEventListener('DOMContentLoaded', function() {
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


         <div class="col-md-12">
    <div class="form-card">
        <label class="section-label"><i class="bi bi-image"></i> Banner Image</label>
        <!-- <p class="hint-text">Used on listing cards and the service detail page hero. Max <strong>2MB</strong>.</p> -->

          <div class="upload-guidelines">
                        <span class="guideline-item">
                            <i class="bi bi-file-earmark-image"></i>
                            Accepted: <strong>JPG, PNG, WEBP</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-hdd"></i>
                            Max size: <strong>10MB</strong> per image
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-aspect-ratio"></i>
                            Recommended: <strong>{{ $imageWidth ?? 1200 }} × {{ $imageHeight ?? 600 }}px</strong>
                        </span>
                    </div>


        <div class="image-upload-box">
            <div class="preview-wrap">
                @if ($service->banner_image)
                    <img src="{{ Storage::url($service->banner_image) }}" class="preview-img" id="preview-banner-image">
                @else
                    <div class="preview-placeholder" id="preview-banner-image">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </div>
            <label class="upload-btn {{ $errors->has('banner_image') ? 'upload-btn-error' : '' }}">
                <i class="bi bi-upload"></i> Choose file
                <input type="file" name="banner_image" accept="image/*"
                       onchange="previewImage(this, 'preview-banner-image')" hidden>
            </label>
            @error('banner_image')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>


            {{-- Hero Image --}}
            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-image"></i> Hero Image</label>
                    <p class="hint-text">Used on listing cards and the service detail page hero</p>

                             <div class="upload-guidelines">
                        <span class="guideline-item">
                            <i class="bi bi-file-earmark-image"></i>
                            Accepted: <strong>JPG, PNG, WEBP</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-hdd"></i>
                            Max size: <strong>10MB</strong> per image
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-aspect-ratio"></i>
                            Recommended: <strong>{{ $imageWidth ?? 1100 }} × {{ $imageHeight ?? 340 }}px</strong>
                        </span>
                    </div>
              

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
        <div class="form-group">
            <label class="section-label"><i class="bi bi-file-text"></i> Process Description</label>
            <p class="hint-text">Detail page — "Process Description" column under Service Overview.</p>
            <textarea name="process_description" rows="5"
                class="{{ $errors->has('process_description') ? 'input-error' : '' }}"
                placeholder="Describe the step-by-step process...">{{ old('process_description', $service->process_description) }}</textarea>
            @error('process_description')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>



           {{-- Specifications Table (dynamic rows) --}}
{{-- Inspection Process (dynamic rows: heading + description + image) --}}
<div class="col-md-12">
    <div class="form-card">
        <div class="form-group">
            <label class="section-label"><i class="bi bi-clipboard-check"></i> Inspection Process</label>
            <p class="hint-text">Detail page — step-by-step inspection process with heading, description, and image per step.</p>

            <div id="inspectionRows"></div>
            <button type="button" id="addInspectionBtn" class="btn-add-row">
                <i class="bi bi-plus-circle"></i> Add Row
            </button>

            @error('inspection_process')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @if ($errors->has('inspection_process.*.heading') || $errors->has('inspection_process.*.description') || $errors->has('inspection_process.*.image'))
                <span class="field-error">
                    <i class="bi bi-exclamation-circle"></i> Please fill in all required fields for each inspection step.
                </span>
            @endif
        </div>
    </div>
</div>

            {{-- Technical Scope (dynamic list) --}}
          {{-- Technical Scope (dynamic list) --}}
<div class="col-md-12">
    <div class="form-card">
        <div class="form-group">
            <label class="section-label"><i class="bi bi-list-check"></i> Technical Scope &amp; Capabilities</label>
            <p class="hint-text">Detail page — bullet list next to Process Description.</p>

            <div id="scopeRows"></div>
            <button type="button" id="addScopeBtn" class="btn-add-row">
                <i class="bi bi-plus-circle"></i> Add Point
            </button>

            @error('technical_scope')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @error('technical_scope.*')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>


            {{-- Specifications Table (dynamic rows) --}}
           {{-- Specifications Table (dynamic rows) --}}
<div class="col-md-12">
    <div class="form-card">
        <div class="form-group">
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

            @error('specifications')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @if ($errors->has('specifications.*.specification') || $errors->has('specifications.*.details') || $errors->has('specifications.*.compliance'))
                <span class="field-error">
                    <i class="bi bi-exclamation-circle"></i> Please fill in all Specification, Details, and Compliance fields for each row.
                </span>
            @endif
        </div>
    </div>
</div>



            {{-- Meta Title --}}
<div class="col-md-8">
    <div class="form-card">
        <div class="form-group">
            <label><i class="bi bi-tag"></i> Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}"
                   class="{{ $errors->has('meta_title') ? 'input-error' : '' }}"
                   placeholder="SEO title for search engines">
            @error('meta_title')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

{{-- Meta Description --}}
<div class="col-md-12">
    <div class="form-card">
        <div class="form-group">
            <label><i class="bi bi-card-text"></i> Meta Description</label>
            <textarea name="meta_description" rows="3"
                      class="{{ $errors->has('meta_description') ? 'input-error' : '' }}"
                      placeholder="SEO description shown in search results">{{ old('meta_description', $service->meta_description) }}</textarea>
            @error('meta_description')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>



            {{-- Gallery --}}
            <div class="col-md-12">
                <div class="form-card">

                    <label class="section-label"><i class="bi bi-images"></i> Gallery (up to 6 images)</label>
                    <!-- <p class="hint-text">Detail page — Service Images gallery. Maximum 6 images total.</p> -->

                         <div class="upload-guidelines">
                        <span class="guideline-item">
                            <i class="bi bi-file-earmark-image"></i>
                            Accepted: <strong>JPG, PNG, WEBP</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-hdd"></i>
                            Max size: <strong>10MB</strong> per image
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-aspect-ratio"></i>
                            Recommended: <strong>{{ $imageWidth ?? 220 }} × {{ $imageHeight ?? 140 }}px</strong>
                        </span>
                    </div>

                    {{-- Existing images already saved --}}
                    <div class="gallery-existing" id="existingGallery">
                        @foreach ($service->gallery ?? [] as $img)
                        <div class="gallery-thumb" data-existing="1">
                            <img src="{{ Storage::url($img) }}">
                            <label class="gallery-remove">
                                <input type="checkbox" name="remove_gallery[]" value="{{ $img }}" onchange="updateGalleryCount()">
                                <i class="bi bi-trash3"></i> Remove
                            </label>
                        </div>
                        @endforeach
                    </div>

                    {{-- New images selected but not yet uploaded (live preview) --}}
                    <div class="gallery-new" id="newGalleryPreview"></div>

                    <label class="upload-btn" id="galleryUploadBtn">
                        <i class="bi bi-upload"></i> Add gallery images
                        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple hidden>
                    </label>
                    <span class="file-name" id="galleryFileCount"></span>
                    <p class="hint-text" id="galleryLimitMsg" style="color:#e74c3c; display:none;">
                        You can upload a maximum of 6 images in total.
                    </p>

                    @error('gallery')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
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


<template id="inspectionRowTemplate">
    <div class="inspection-row">
        <div class="inspection-row-fields">
            <div class="form-group">
                <label>Heading</label>
                <input type="text" name="inspection_process[__INDEX__][heading]" placeholder="e.g. Visual Inspection">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="inspection_process[__INDEX__][description]" rows="3" placeholder="Describe this inspection step..."></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <div class="image-upload-box">
                    <div class="preview-wrap">
                        <div class="preview-placeholder inspection-preview">
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Choose image
                        <input type="file" name="inspection_process[__INDEX__][image]" accept="image/*" hidden
                               onchange="previewInspectionImage(this)">
                    </label>
                    {{-- keeps the previously saved image path if admin doesn't re-upload on edit --}}
                    <input type="hidden" name="inspection_process[__INDEX__][existing_image]" class="existing-image-input" value="">
                </div>
            </div>
        </div>
        <button type="button" class="btn-remove-row inspection-remove"><i class="bi bi-trash3"></i></button>
    </div>
</template>

@php
    $inspectionForJs = collect(old('inspection_process', $service->inspection_process ?? []))
        ->map(function ($row) {
            if (!empty($row['image'])) {
                $row['image_url'] = Storage::url($row['image']); // e.g. /storage/services/xxx.webp
            }
            return $row;
        });
@endphp
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


    const MAX_GALLERY_IMAGES = 6;
const galleryInput = document.getElementById('galleryInput');
const newGalleryPreview = document.getElementById('newGalleryPreview');
const galleryFileCount = document.getElementById('galleryFileCount');
const galleryLimitMsg = document.getElementById('galleryLimitMsg');

let selectedFiles = []; // holds File objects the user picked (persists across multiple selections)

function countExistingRemaining() {
    // existing images minus ones checked for removal
    const existingThumbs = document.querySelectorAll('#existingGallery .gallery-thumb');
    let remaining = 0;
    existingThumbs.forEach(thumb => {
        const checkbox = thumb.querySelector('input[type="checkbox"]');
        if (!checkbox.checked) remaining++;
    });
    return remaining;
}

function updateGalleryCount() {
    const remainingExisting = countExistingRemaining();
    const totalCount = remainingExisting + selectedFiles.length;

    galleryFileCount.textContent = totalCount > 0
        ? `${totalCount} / ${MAX_GALLERY_IMAGES} image(s) selected`
        : '';

    galleryLimitMsg.style.display = totalCount > MAX_GALLERY_IMAGES ? 'block' : 'none';

    // disable the upload button once limit is reached
    document.getElementById('galleryUploadBtn').style.opacity = totalCount >= MAX_GALLERY_IMAGES ? '0.5' : '1';
    document.getElementById('galleryUploadBtn').style.pointerEvents = totalCount >= MAX_GALLERY_IMAGES ? 'none' : 'auto';
}

function renderNewPreviews() {
    newGalleryPreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrap = document.createElement('div');
            wrap.className = 'gallery-thumb gallery-thumb-new';

            const img = document.createElement('img');
            img.src = e.target.result;

            const removeLabel = document.createElement('label');
            removeLabel.className = 'gallery-remove';
            removeLabel.innerHTML = `<i class="bi bi-x-circle"></i> Cancel`;
            removeLabel.style.cursor = 'pointer';
            removeLabel.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                syncFileInput();
                renderNewPreviews();
                updateGalleryCount();
            });

            wrap.appendChild(img);
            wrap.appendChild(removeLabel);
            newGalleryPreview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}

// Rebuilds the actual <input type="file"> FileList from selectedFiles array
function syncFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    galleryInput.files = dataTransfer.files;
}

galleryInput.addEventListener('change', function () {
    const remainingExisting = countExistingRemaining();
    const newlyPicked = Array.from(galleryInput.files);

    // combine with already-selected files (in case user opens the picker multiple times)
    let combined = selectedFiles.concat(newlyPicked);

    const allowedNewCount = MAX_GALLERY_IMAGES - remainingExisting;

    if (combined.length > allowedNewCount) {
        combined = combined.slice(0, Math.max(allowedNewCount, 0));
        galleryLimitMsg.style.display = 'block';
    } else {
        galleryLimitMsg.style.display = 'none';
    }

    selectedFiles = combined;
    syncFileInput();
    renderNewPreviews();
    updateGalleryCount();
});

// Recalculate whenever an existing-image "remove" checkbox is toggled
document.addEventListener('DOMContentLoaded', updateGalleryCount);


    // ===== Image preview =====
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
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


    // ===== Inspection Process (heading + description + image rows) =====
    const existingInspection = @json($inspectionForJs);

const inspectionContainer = document.getElementById('inspectionRows');
const inspectionTemplate = document.getElementById('inspectionRowTemplate');
let inspectionIndex = 0;

function addInspectionRow(data = {}) {
    const clone = inspectionTemplate.content.cloneNode(true);
    const row = clone.querySelector('.inspection-row');

    // Replace __INDEX__ placeholders in all field names
    row.querySelectorAll('input, textarea').forEach(field => {
        field.name = field.name.replace('__INDEX__', inspectionIndex);
    });

    row.querySelector('input[name$="[heading]"]').value = data.heading ?? '';
    row.querySelector('textarea[name$="[description]"]').value = data.description ?? '';

    // If editing and this step already has a saved image, show it + store its path
    if (data.image) {
        const previewWrap = row.querySelector('.preview-wrap');
        const img = document.createElement('img');
        img.src = data.image_url ?? data.image; // pass full URL if available
        img.className = 'preview-img inspection-preview';
        previewWrap.querySelector('.inspection-preview').replaceWith(img);

        row.querySelector('.existing-image-input').value = data.image;
    }

    row.querySelector('.inspection-remove').addEventListener('click', () => row.remove());

    inspectionContainer.appendChild(row);
    inspectionIndex++;
}

document.getElementById('addInspectionBtn').addEventListener('click', () => addInspectionRow());

if (existingInspection.length > 0) {
    existingInspection.forEach(row => addInspectionRow(row));
} else {
    addInspectionRow();
}

// Live preview for a newly selected inspection step image
function previewInspectionImage(input) {
    const box = input.closest('.image-upload-box');
    const preview = box.querySelector('.preview-wrap > *');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img inspection-preview';
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-error {
        background: #fdecea;
        color: #c0392b;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .form-header h4 {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1e1e2d;
    }

    .btn-back {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #3b3b58;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-back:hover {
        text-decoration: underline;
    }

    .banner-form {
        width: 100%;
    }

    .form-card {
        background: #fff;
        padding: 22px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 18px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 8px;
    }

    .hint-text {
        font-size: 12.5px;
        color: #888;
        margin-bottom: 12px;
    }

    .image-upload-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        max-width: 300px;
    }

    .preview-wrap {
        width: 100%;
    }

    .preview-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
        margin-bottom: 10px;
    }

    .preview-placeholder {
        width: 100%;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f6f9;
        border-radius: 6px;
        border: 1px dashed #ddd;
        color: #bbb;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f4f6f9;
        color: #3b3b58;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        border: 1px solid #ddd;
    }

    .upload-btn:hover {
        background: #e9ecf2;
    }

    .upload-btn-error {
        border-color: #e74c3c !important;
    }

    .field-error {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #e74c3c;
        font-size: 12.5px;
        margin-top: 6px;
    }

    .input-error {
        border-color: #e74c3c !important;
        background: #fff8f8;
    }

    .file-name {
        font-size: 13px;
        color: #666;
        margin-left: 10px;
    }

    .scope-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .scope-row input {
        flex: 1;
    }

    .specs-table-header {
        display: grid;
        grid-template-columns: 1fr 1.5fr 1fr 40px;
        gap: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #888;
        padding: 0 4px 10px;
        border-bottom: 1px solid #eee;
        margin-bottom: 12px;
    }

    .specs-row {
        display: grid;
        grid-template-columns: 1fr 1.5fr 1fr 40px;
        gap: 12px;
        margin-bottom: 10px;
        align-items: center;
    }

    .btn-remove-row {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 1px solid #f0d0d0;
        background: #fdecea;
        color: #c0392b;
        cursor: pointer;
    }

    .btn-remove-row:hover {
        background: #c0392b;
        color: #fff;
    }

    .btn-add-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f4f6f9;
        color: #3b3b58;
        border: 1px solid #ddd;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        margin-top: 4px;
    }

    .btn-add-row:hover {
        background: #e9ecf2;
    }

    .gallery-existing {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .gallery-thumb {
        width: 120px;
    }

    .gallery-thumb img {
        width: 100%;
        height: 90px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
        margin-bottom: 6px;
    }

    .gallery-remove {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #c0392b;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 6px;
    }

    .btn-cancel {
        padding: 11px 22px;
        border-radius: 6px;
        border: 1px solid #ddd;
        color: #555;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-cancel:hover {
        background: #f4f6f9;
    }

    .btn-submit {
        display: flex;
        align-items: center;
        gap: 7px;
        background: #3b3b58;
        color: #fff;
        border: none;
        padding: 11px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #2b2b42;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 7px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        font-family: inherit;
        resize: vertical;
        background: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #3b3b58;
    }

    .gallery-new { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 16px; }
.gallery-thumb-new { border: 1px dashed #3b3b58; }


.inspection-row {
    display: flex;
    gap: 14px;
    align-items: stretch;
    background: #fafbfc;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 14px;
    position: relative;
}

.inspection-row-fields {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr 220px;
    gap: 16px;
    align-items: start;
}

.inspection-row-fields .form-group {
    display: flex;
    flex-direction: column;
}

.inspection-row-fields .form-group label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 7px;
    color: #333;
}

.inspection-row-fields input[type="text"],
.inspection-row-fields textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
}

.inspection-row-fields textarea {
    min-height: 110px;
}

/* ===== Compact image upload box just for inspection rows ===== */
.inspection-row .image-upload-box {
    width: 100%;
    max-width: 220px;
}

.inspection-row .preview-wrap {
    width: 100%;
}

.inspection-row .preview-img,
.inspection-row .preview-placeholder {
    width: 100%;
    height: 110px;           /* matches textarea height instead of 300px */
    margin-bottom: 8px;
}

.inspection-row .preview-placeholder {
    font-size: 22px;
}

.inspection-row .upload-btn {
    width: 100%;
    justify-content: center;
    font-size: 12.5px;
    padding: 8px 10px;
}

/* ===== Remove button aligned to top-right of the row, not floating oddly ===== */
.inspection-row .btn-remove-row {
    position: absolute;
    top: 40px;
    right: 14px;
    width: 34px;
    height: 34px;
    flex-shrink: 0;

     /* centering fix */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    line-height: 1;
}

.inspection-row .btn-remove-row i {
    display: block;
    font-size: 15px;
    line-height: 1;
}

/* add right padding to row so trash icon doesn't overlap the image column */
.inspection-row {
    padding-right: 56px;
}

@media (max-width: 900px) {
    .inspection-row-fields {
        grid-template-columns: 1fr;
    }
    .inspection-row .image-upload-box {
        max-width: 100%;
    }
    .inspection-row {
        padding-right: 18px;
    }
    .inspection-row .btn-remove-row {
        position: static;
        margin-top: 12px;
    }
}


  .upload-guidelines {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
        padding: 9px 14px;
        background: #f4f6f9;
        border: 1px solid #e8eaee;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .guideline-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #666;
        white-space: nowrap;
    }

    .guideline-item i {
        font-size: 13px;
        color: #8b93a1;
    }

    .guideline-item strong {
        color: #3b3b58;
        font-weight: 600;
    }

    .guideline-divider {
        width: 1px;
        height: 14px;
        background: #d8dce2;
        flex-shrink: 0;
    }

    @media (max-width: 600px) {
        .upload-guidelines {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .guideline-divider {
            display: none;
        }
    }

</style>

@endsection