@extends('admin.layout')
@section('title', $service->exists ? 'Edit Service' : 'Add Service')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    #totalSizeError {
        align-items: flex-start;
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
        margin-left: 0;
        margin-top: 4px;
        display: block;
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
        grid-template-columns: 1fr 200px 220px; /* was: 1fr 220px */
        gap: 16px;
        align-items: start;
    }

    .feature-row-fields {
        grid-template-columns: 140px 1fr 1fr;
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
        height: 110px;
        margin-bottom: 8px;
    }

    .feature-row-fields .preview-img,
    .feature-row-fields .preview-placeholder {
        height: 90px;
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

    .inspection-row .btn-remove-row {
        position: absolute;
        top: 18px;
        right: 14px;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
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

    .inspection-row {
        padding-right: 56px;
    }

    @media (max-width: 900px) {
       .inspection-row-fields,
        .feature-row-fields {
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
@if ($errors->any())
<div class="alert alert-error">
    <i class="bi bi-exclamation-circle"></i>
    Please fill below fields before submitting
</div>
@endif

@if (session('error'))
<div class="alert alert-error">
    <i class="bi bi-exclamation-circle"></i>
    {{ session('error') }}
</div>
@endif

<div class="alert alert-error" id="totalSizeError" style="display:none;">
    <i class="bi bi-exclamation-circle"></i>
    <span id="totalSizeErrorText"></span>
</div>

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

            {{-- ================= BANNER SECTION ================= --}}
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Banner Title</label>
                        <input type="text" name="banner_title" value="{{ old('banner_title', $service->banner_title) }}"
                            class="{{ $errors->has('banner_title') ? 'input-error' : '' }}"
                            placeholder="e.g. API Threading Services">
                        @error('banner_title')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-card-text"></i> Banner Description</label>
                        <p class="hint-text">Shown on the homepage banner and listing page under the title.</p>
                        <textarea name="banner_description" rows="3"
                            class="{{ $errors->has('banner_description') ? 'input-error' : '' }}"
                            placeholder="e.g. API threading and machining solutions for critical oilfield connections.">{{ old('banner_description', $service->banner_description) }}</textarea>
                        @error('banner_description')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-image"></i> Banner Image</label>

                    <div class="upload-guidelines">
                        <span class="guideline-item">
                            <i class="bi bi-file-earmark-image"></i>
                            Accepted: <strong>JPG, PNG, WEBP</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-hdd"></i>
                            Max size: <strong>10MB</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-aspect-ratio"></i>
                            Recommended: <strong>1200 × 600px</strong>
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
                                   onchange="handleImageChange(this, 'preview-banner-image', MAX_IMAGE_BYTES, 'Banner image')" hidden>
                        </label>
                        @error('banner_image')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ================= OVERVIEW SECTION ================= --}}
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Overview Title</label>
                        <input type="text" name="overview_title" value="{{ old('overview_title', $service->overview_title) }}"
                            class="{{ $errors->has('overview_title') ? 'input-error' : '' }}"
                            placeholder="e.g. Service Overview">
                        @error('overview_title')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-card-text"></i> Overview Description</label>
                        <textarea name="overview_description" rows="4"
                            class="{{ $errors->has('overview_description') ? 'input-error' : '' }}"
                            placeholder="Describe the service overview...">{{ old('overview_description', $service->overview_description) }}</textarea>
                        @error('overview_description')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-image"></i> Overview Image</label>

                    <div class="upload-guidelines">
                        <span class="guideline-item">
                            <i class="bi bi-file-earmark-image"></i>
                            Accepted: <strong>JPG, PNG, WEBP</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-hdd"></i>
                            Max size: <strong>10MB</strong>
                        </span>
                        <span class="guideline-divider"></span>
                        <span class="guideline-item">
                            <i class="bi bi-aspect-ratio"></i>
                            Recommended: <strong>1100 × 340px</strong>
                        </span>
                    </div>

                    <div class="image-upload-box">
                        <div class="preview-wrap">
                            @if ($service->overview_image)
                                <img src="{{ Storage::url($service->overview_image) }}" class="preview-img" id="preview-overview-image">
                            @else
                                <div class="preview-placeholder" id="preview-overview-image">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>
                        <label class="upload-btn {{ $errors->has('overview_image') ? 'upload-btn-error' : '' }}">
                            <i class="bi bi-upload"></i> Choose file
                            <input type="file" name="overview_image" accept="image/*"
                                   onchange="handleImageChange(this, 'preview-overview-image', MAX_IMAGE_BYTES, 'Overview image')" hidden>
                        </label>
                        @error('overview_image')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ================= PROCESS SECTION (dynamic: description + video, unlimited) ================= --}}
            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label class="section-label"><i class="bi bi-camera-reels"></i> Process</label>
                        <p class="hint-text">Detail page — step-by-step process, each with a description and a video. Add as many as needed.</p>

                        <div id="processRows"></div>
                        <button type="button" id="addProcessBtn" class="btn-add-row">
                            <i class="bi bi-plus-circle"></i> Add Row
                        </button>

                        @error('process')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                        @if ($errors->has('process.*.description') || $errors->has('process.*.video'))
                            <span class="field-error">
                                <i class="bi bi-exclamation-circle"></i> Please check the description/video for each process step.
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= FEATURES SECTION (dynamic: icon + title + description, max 4) ================= --}}
            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Features Heading</label>
                        <input type="text" name="features_heading" value="{{ old('features_heading', $service->features_heading) }}"
                            class="{{ $errors->has('features_heading') ? 'input-error' : '' }}"
                            placeholder="e.g. Why Choose Us">
                        @error('features_heading')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label class="section-label"><i class="bi bi-grid-3x3-gap"></i> Features (max 4)</label>
                        <p class="hint-text">Each feature has an icon, a title, and a short description. Maximum 4 features.</p>

                        <div id="featureRows"></div>
                        <button type="button" id="addFeatureBtn" class="btn-add-row">
                            <i class="bi bi-plus-circle"></i> Add Feature
                        </button>
                        <p class="hint-text" id="featureLimitMsg" style="color:#e74c3c; display:none;">
                            You can add a maximum of 4 features.
                        </p>

                        @error('features')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                        @if ($errors->has('features.*.title') || $errors->has('features.*.description') || $errors->has('features.*.icon'))
                            <span class="field-error">
                                <i class="bi bi-exclamation-circle"></i> Please check the icon/title/description for each feature.
                            </span>
                        @endif
                    </div>
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

{{-- ===== Templates ===== --}}

<template id="processRowTemplate">
    <div class="inspection-row">
        <div class="inspection-row-fields">
            <div class="form-group">
                <label>Description</label>
                <textarea name="process[__INDEX__][description]" rows="3" placeholder="Describe this process step..."></textarea>
            </div>

            <div class="form-group">
                <label>Thumbnail</label>
                <div class="image-upload-box">
                    <div class="preview-wrap">
                        <div class="preview-placeholder process-thumb-preview">
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Choose image
                        <input type="file" name="process[__INDEX__][thumbnail]" accept="image/*" hidden
                               onchange="previewProcessThumbnail(this)">
                    </label>
                    <input type="hidden" name="process[__INDEX__][existing_thumbnail]" class="existing-thumbnail-input" value="">
                </div>
            </div>

            <div class="form-group">
                <label>Video</label>
                <div class="image-upload-box">
                    <div class="preview-wrap">
                        <div class="preview-placeholder process-preview">
                            <i class="bi bi-camera-video"></i>
                        </div>
                    </div>
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Choose video
                        <input type="file" name="process[__INDEX__][video]" accept="video/*" hidden
                               onchange="previewProcessVideo(this)">
                    </label>
                    <span class="file-name process-filename"></span>
                    <input type="hidden" name="process[__INDEX__][existing_video]" class="existing-video-input" value="">
                </div>
            </div>
        </div>
        <button type="button" class="btn-remove-row inspection-remove"><i class="bi bi-trash3"></i></button>
    </div>
</template>

<template id="featureRowTemplate">
    <div class="inspection-row">
        <div class="inspection-row-fields feature-row-fields">
            <div class="form-group">
                <label>Icon</label>
                <div class="image-upload-box">
                    <div class="preview-wrap">
                        <div class="preview-placeholder feature-preview">
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Choose icon
                        <input type="file" name="features[__INDEX__][icon]" accept="image/*" hidden
                               onchange="previewFeatureIcon(this)">
                    </label>
                    <input type="hidden" name="features[__INDEX__][existing_icon]" class="existing-icon-input" value="">
                </div>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="features[__INDEX__][title]" placeholder="e.g. Certified Personnel">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="features[__INDEX__][description]" rows="3" placeholder="Short description..."></textarea>
            </div>
        </div>
        <button type="button" class="btn-remove-row inspection-remove"><i class="bi bi-trash3"></i></button>
    </div>
</template>

@php
    $processForJs = collect(old('process', $service->process ?? []))
    ->values()
    ->map(function ($row, $i) use ($errors) {
        $row = is_array($row) ? $row : [];

        if (!empty($row['video'])) {
            $row['video_url'] = Storage::url($row['video']);
        }

        if (!empty($row['thumbnail'])) {
            $row['thumbnail_url'] = Storage::url($row['thumbnail']);
        }

        $row['errors'] = [
            'description' => $errors->first("process.$i.description"),
            'video' => $errors->first("process.$i.video"),
        ];

        return $row;
    })
    ->values()
    ->toArray();

    $featuresForJs = collect(old('features', $service->features ?? []))
        ->values()
        ->map(function ($row, $i) use ($errors) {
            $row = is_array($row) ? $row : [];

            if (!empty($row['icon'])) {
                $row['icon_url'] = Storage::url($row['icon']);
            }

            $row['errors'] = [
                'icon' => $errors->first("features.$i.icon"),
                'title' => $errors->first("features.$i.title"),
                'description' => $errors->first("features.$i.description"),
            ];

            return $row;
        })
        ->values()
        ->toArray();
@endphp
<script>
    // ===== Size limits (must match ServiceRequest validation rules) =====
    const MAX_IMAGE_BYTES = 10 * 1024 * 1024; // 10MB - banner/overview images
    const MAX_ICON_BYTES  = 5 * 1024 * 1024;  // 5MB  - feature icons
    const MAX_VIDEO_BYTES = 20 * 1024 * 1024; // 20MB - process videos
    const MAX_TOTAL_BYTES = 95 * 1024 * 1024; // keep under php.ini post_max_size (100M) with a buffer

    function formatBytes(bytes) {
        return (bytes / (1024 * 1024)).toFixed(1) + 'MB';
    }

    // ===== Generic field-error helpers =====
    // Client-side checks add .field-error.client-error spans so they can be
    // cleared/replaced independently without touching server-rendered ones.
    function setFieldError(anchor, message, isClientError = true) {
        if (!anchor) return;
        clearFieldError(anchor);
        if (!message) return;
        anchor.classList.add('input-error');
        const span = document.createElement('span');
        span.className = 'field-error' + (isClientError ? ' client-error' : '');
        span.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        anchor.insertAdjacentElement('afterend', span);
    }

    function clearFieldError(anchor) {
        if (!anchor) return;
        anchor.classList.remove('input-error');
        const next = anchor.nextElementSibling;
        if (next && next.classList.contains('field-error') && next.classList.contains('client-error')) {
            next.remove();
        }
    }

    // Validates a single file input against a max size. Anchor is the element
    // the error message should appear under (usually the visible upload-btn
    // label, since the actual <input type="file"> is hidden).
    function validateFileSize(input, maxBytes, label, anchor) {
        if (input.files && input.files[0] && input.files[0].size > maxBytes) {
            setFieldError(
                anchor,
                `${label} must be smaller than ${formatBytes(maxBytes)} (selected file is ${formatBytes(input.files[0].size)}).`
            );
            input.value = ''; 
            return false;
        }
        clearFieldError(anchor);
        return true;
    }

    // ===== Generic image preview (banner / overview) with size validation =====
    function handleImageChange(input, previewId, maxBytes, label) {
        const anchor = input.closest('label.upload-btn');
        if (!validateFileSize(input, maxBytes, label, anchor)) {
            return;
        }
        previewImage(input, previewId);
    }

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

    // ===== Process rows (description + video, unlimited) =====
    const existingProcess = @json($processForJs);
    const processContainer = document.getElementById('processRows');
    const processTemplate = document.getElementById('processRowTemplate');
    let processIndex = 0;

    function addProcessRow(data = {}) {
    const clone = processTemplate.content.cloneNode(true);
    const row = clone.querySelector('.inspection-row');

    row.querySelectorAll('input, textarea').forEach(field => {
        field.name = field.name.replace('__INDEX__', processIndex);
    });

    const descField = row.querySelector('textarea[name$="[description]"]');
    descField.value = data.description ?? '';

    if (data.thumbnail) {
        const previewWrap = row.querySelectorAll('.preview-wrap')[0];
        const img = document.createElement('img');
        img.src = data.thumbnail_url ?? data.thumbnail;
        img.className = 'preview-img process-thumb-preview';
        previewWrap.querySelector('.process-thumb-preview').replaceWith(img);
        row.querySelector('.existing-thumbnail-input').value = data.thumbnail;
    }

    if (data.video) {
        const filenameSpan = row.querySelector('.process-filename');
        filenameSpan.textContent = data.video.split('/').pop();
        row.querySelector('.existing-video-input').value = data.video;
    }

        // Show server-side validation errors returned for this row, if any
        if (data.errors) {
            if (data.errors.description) {
                setFieldError(descField, data.errors.description, false);
            }
            if (data.errors.video) {
                const videoAnchor = row.querySelector('label.upload-btn');
                setFieldError(videoAnchor, data.errors.video, false);
            }
        }

        row.querySelector('.inspection-remove').addEventListener('click', () => row.remove());

        processContainer.appendChild(row);
        processIndex++;
    }

    document.getElementById('addProcessBtn').addEventListener('click', () => addProcessRow());

    if (existingProcess.length > 0) {
        existingProcess.forEach(row => addProcessRow(row));
    } else {
        addProcessRow();
    }

    function previewProcessVideo(input) {
        const anchor = input.closest('label.upload-btn');
        if (!validateFileSize(input, MAX_VIDEO_BYTES, 'Process video', anchor)) {
            const box = input.closest('.image-upload-box');
            box.querySelector('.process-filename').textContent = '';
            return;
        }
        const box = input.closest('.image-upload-box');
        const filenameSpan = box.querySelector('.process-filename');
        if (input.files && input.files[0]) {
            filenameSpan.textContent = input.files[0].name;
        }
    }

    // ===== Feature rows (icon + title + description, max 4) =====
    const MAX_FEATURES = 4;
    const existingFeatures = @json($featuresForJs);
    const featureContainer = document.getElementById('featureRows');
    const featureTemplate = document.getElementById('featureRowTemplate');
    const addFeatureBtn = document.getElementById('addFeatureBtn');
    const featureLimitMsg = document.getElementById('featureLimitMsg');
    let featureIndex = 0;

    function updateFeatureButtonState() {
        const count = featureContainer.querySelectorAll('.inspection-row').length;
        const atLimit = count >= MAX_FEATURES;
        addFeatureBtn.style.opacity = atLimit ? '0.5' : '1';
        addFeatureBtn.style.pointerEvents = atLimit ? 'none' : 'auto';
        featureLimitMsg.style.display = atLimit ? 'block' : 'none';
    }

    function addFeatureRow(data = {}) {
        if (featureContainer.querySelectorAll('.inspection-row').length >= MAX_FEATURES) {
            updateFeatureButtonState();
            return;
        }

        const clone = featureTemplate.content.cloneNode(true);
        const row = clone.querySelector('.inspection-row');

        row.querySelectorAll('input, textarea').forEach(field => {
            field.name = field.name.replace('__INDEX__', featureIndex);
        });

        const titleField = row.querySelector('input[name$="[title]"]');
        const descField = row.querySelector('textarea[name$="[description]"]');
        titleField.value = data.title ?? '';
        descField.value = data.description ?? '';

        if (data.icon) {
            const previewWrap = row.querySelector('.preview-wrap');
            const img = document.createElement('img');
            img.src = data.icon_url ?? data.icon;
            img.className = 'preview-img feature-preview';
            previewWrap.querySelector('.feature-preview').replaceWith(img);
            row.querySelector('.existing-icon-input').value = data.icon;
        }

        // Show server-side validation errors returned for this row, if any
        if (data.errors) {
            if (data.errors.title) {
                setFieldError(titleField, data.errors.title, false);
            }
            if (data.errors.description) {
                setFieldError(descField, data.errors.description, false);
            }
            if (data.errors.icon) {
                const iconAnchor = row.querySelector('label.upload-btn');
                setFieldError(iconAnchor, data.errors.icon, false);
            }
        }

        row.querySelector('.inspection-remove').addEventListener('click', () => {
            row.remove();
            updateFeatureButtonState();
        });

        featureContainer.appendChild(row);
        featureIndex++;
        updateFeatureButtonState();
    }

    addFeatureBtn.addEventListener('click', () => addFeatureRow());

    if (existingFeatures.length > 0) {
        existingFeatures.slice(0, MAX_FEATURES).forEach(row => addFeatureRow(row));
    } else {
        addFeatureRow();
    }

    function previewFeatureIcon(input) {
        const anchor = input.closest('label.upload-btn');
        if (!validateFileSize(input, MAX_ICON_BYTES, 'Feature icon', anchor)) {
            return;
        }

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
                    img.className = 'preview-img feature-preview';
                    preview.replaceWith(img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== Final guard: block submission if total file size would exceed post_max_size =====
    document.getElementById('serviceForm').addEventListener('submit', function (e) {
        let totalBytes = 0;

        this.querySelectorAll('input[type="file"]').forEach(input => {
            if (input.files && input.files[0]) {
                totalBytes += input.files[0].size;
            }
        });

        const totalErrorBox = document.getElementById('totalSizeError');
        const totalErrorText = document.getElementById('totalSizeErrorText');

        if (totalBytes > MAX_TOTAL_BYTES) {
            e.preventDefault();
            totalErrorText.textContent =
                `Total upload size (${formatBytes(totalBytes)}) exceeds the ${formatBytes(MAX_TOTAL_BYTES)} limit for this form. ` +
                `Please remove or replace some images/videos before saving.`;
            totalErrorBox.style.display = 'flex';
            totalErrorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            totalErrorBox.style.display = 'none';
        }
    });

    function previewProcessThumbnail(input) {
    const anchor = input.closest('label.upload-btn');
    if (!validateFileSize(input, MAX_IMAGE_BYTES, 'Process thumbnail', anchor)) {
        return;
    }

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
                img.className = 'preview-img process-thumb-preview';
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
