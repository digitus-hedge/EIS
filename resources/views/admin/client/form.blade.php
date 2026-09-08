@extends('admin.layout')
@section('title', 'Client Section')
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
        <i class="bi bi-people"></i>
        Client Section
    </h4>
</div>

<form action="{{ route('admin.home.clients.store') }}" method="POST" enctype="multipart/form-data" class="banner-form" id="clientForm">
    @csrf

    <div class="container-fluid px-0">
        <div class="row">

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Title</label>
                        <input type="text" name="title" value="{{ old('title', $client->title) }}"
                            class="{{ $errors->has('title') ? 'input-error' : '' }}"
                            placeholder="Enter title">
                        @error('title')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-text-paragraph"></i> Description</label>
                        <textarea name="description" rows="4"
                            class="{{ $errors->has('description') ? 'input-error' : '' }}"
                            placeholder="Enter description">{{ old('description', $client->description) }}</textarea>
                        @error('description')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-images"></i> Client Logos / Images</label>
                    <p class="hint-text">Accepted: JPG, PNG, WEBP — Max size: <strong>2MB</strong> per image</p>

                    {{-- Existing saved images --}}
                    @if (!empty($client->images))
                    <div class="existing-images">
                        @foreach ($client->images as $img)
                        <div class="existing-thumb">
                            <img src="{{ Storage::url($img) }}">
                            <label class="remove-checkbox">
                                <input type="checkbox" name="remove_images[]" value="{{ $img }}">
                                <i class="bi bi-trash3"></i> Remove
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Dynamic new image upload rows --}}
                    <div id="imageRows"></div>

                    <button type="button" id="addImageBtn" class="btn-add-row">
                        <i class="bi bi-plus-circle"></i> Add Image
                    </button>

                    {{-- General Array Error (e.g., At least 1 image required) --}}
                    @error('images')
                    <span class="field-error d-block mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror

                    {{-- Individual File Errors (e.g., invalid mime, size > 2MB) --}}
                    @error('images.*')
                    <span class="field-error d-block mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
            

            <div class="col-md-12">
                <div class="form-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg"></i>
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

<template id="imageRowTemplate">
    <div class="image-row">
        <div class="preview-wrap">
            <div class="preview-placeholder">
                <i class="bi bi-image"></i>
            </div>
        </div>
        <label class="upload-btn">
            <i class="bi bi-upload"></i> Choose file
            <input type="file" name="images[]" accept="image/*" hidden>
        </label>
        <span class="file-size-info"></span>
        <button type="button" class="btn-remove-row"><i class="bi bi-trash3"></i> Remove</button>
    </div>
</template>

<script>
    const imageRowsContainer = document.getElementById('imageRows');
    const imageTemplate = document.getElementById('imageRowTemplate');
    const maxSizeMB = 2;

    function addImageRow() {
        const clone = imageTemplate.content.cloneNode(true);
        const row = clone.querySelector('.image-row');
        const input = row.querySelector('input[type="file"]');
        const previewWrap = row.querySelector('.preview-wrap');
        const sizeInfo = row.querySelector('.file-size-info');

        input.addEventListener('change', function() {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (sizeMB > maxSizeMB) {
                sizeInfo.innerHTML = `<i class="bi bi-exclamation-triangle"></i> ${sizeMB} MB — exceeds ${maxSizeMB}MB limit!`;
                sizeInfo.classList.add('size-error');
            } else {
                sizeInfo.innerHTML = `<i class="bi bi-check-circle"></i> ${sizeMB} MB`;
                sizeInfo.classList.remove('size-error');
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewWrap.innerHTML = `<img src="${e.target.result}" class="preview-img">`;
            };
            reader.readAsDataURL(file);
        });

        row.querySelector('.btn-remove-row').addEventListener('click', () => row.remove());

        imageRowsContainer.appendChild(row);
    }

    document.getElementById('addImageBtn').addEventListener('click', addImageRow);

    // Start with 1 empty upload row
    addImageRow();
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
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        font-family: inherit;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #3b3b58;
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
        margin-bottom: 14px;
    }

    .input-error {
        border-color: #e74c3c !important;
        background: #fff8f8;
    }

    .field-error {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #e74c3c;
        font-size: 12.5px;
        margin-top: 6px;
    }

    .existing-images {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .existing-thumb {
        width: 110px;
    }

    .existing-thumb img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
        margin-bottom: 6px;
    }

    .remove-checkbox {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #c0392b;
        cursor: pointer;
    }

    .image-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
        padding: 12px;
        background: #f9f9fb;
        border-radius: 8px;
    }

    .preview-wrap {
        width: 70px;
        flex-shrink: 0;
    }

    .preview-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .preview-placeholder {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f6f9;
        border-radius: 6px;
        border: 1px dashed #ddd;
        color: #bbb;
        font-size: 22px;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
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

    .file-size-info {
        font-size: 12px;
        color: #1e8449;
    }

    .file-size-info.size-error {
        color: #e74c3c;
        font-weight: 600;
    }

    .btn-remove-row {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: auto;
        background: #fdecea;
        color: #c0392b;
        border: 1px solid #f0d0d0;
        padding: 7px 12px;
        border-radius: 6px;
        font-size: 13px;
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
</style>

@endsection