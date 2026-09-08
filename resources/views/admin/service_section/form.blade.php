@extends('admin.layout')
@section('title', 'Service Section')
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
        <i class="bi bi-file-text"></i>
        Service Section
    </h4>
</div>

<form action="{{ route('admin.home.services.section.store') }}"
      method="POST" class="banner-form" id="serviceSectionForm" enctype="multipart/form-data">
    @csrf

    <div class="container-fluid px-0">
        <div class="row">

            <div class="col-md-8">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type-h1"></i> Heading</label>
                        <textarea name="heading" rows="3"
                                  class="{{ $errors->has('heading') ? 'input-error' : '' }}"
                                  placeholder="Specialized services for critical oilfield equipment">{{ old('heading', $serviceSection->heading) }}</textarea>
                        <p class="hint-text">Main title. Line breaks will show as line breaks on the site.</p>
                        @error('heading')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-image"></i> Section Image</label>

                        <div class="image-box-wrap">
                            @if (!empty($serviceSection->image))
                                <div class="image-box" id="currentImageBox">
                                    <img src="{{ asset('storage/' . $serviceSection->image) }}" alt="Current section image">
                                </div>
                                <span class="current-image-label">Current image</span>
                            @endif

                            <div class="image-box" id="imagePreviewBox" style="display:none;">
                                <img id="imagePreview" src="" alt="New image preview">
                            </div>
                            <span class="current-image-label" id="previewLabel" style="display:none;">New image preview</span>
                        </div>

                        <input type="file" name="image" id="serviceImageInput" accept="image/*"
                            class="{{ $errors->has('image') ? 'input-error' : '' }}">

                        <p class="hint-text">Recommended: JPG or PNG, at least 1200px wide. Leave empty to keep the current image.</p>
                        @error('image')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-card-text"></i> Description</label>
                        <textarea name="description" rows="6"
                                  class="{{ $errors->has('description') ? 'input-error' : '' }}"
                                  placeholder="EGTS provides machining and inspection services for the oil and gas industry...">{{ old('description', $serviceSection->description) }}</textarea>
                        @error('description')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
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

<style>
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; display:flex; align-items:center; gap:8px; }
    .alert-error { background: #fdecea; color: #c0392b; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .form-header h4 { display: flex; align-items: center; gap: 8px; color: #1e1e2d; }
    .btn-back { display: flex; align-items: center; gap: 6px; color: #3b3b58; text-decoration: none; font-size: 14px; }
    .btn-back:hover { text-decoration: underline; }
    .banner-form { width: 100%; }
    .form-card { background: #fff; padding: 22px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 18px; }
    .form-group label { display: flex; align-items: center; gap: 6px; margin-bottom: 7px; font-weight: 600; font-size: 14px; color: #333; }
    .form-group input[type="text"],
    .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; font-family: inherit; resize: vertical; }
    .form-group input[type="file"] { width: 100%; padding: 9px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13.5px; background:#fafafa; }
    .form-group input:focus,
    .form-group textarea:focus { border-color: #3b3b58; }
    .hint-text { font-size: 12.5px; color: #888; margin-top: 6px; margin-bottom: 0; }
    .input-error { border-color: #e74c3c !important; background: #fff8f8; }
    .field-error { display: flex; align-items: center; gap: 5px; color: #e74c3c; font-size: 12.5px; margin-top: 6px; }
    .field-error i { font-size: 13px; }
    .form-actions { display: flex; gap: 12px; margin-top: 6px; }
    .btn-cancel { padding: 11px 22px; border-radius: 6px; border: 1px solid #ddd; color: #555; text-decoration: none; font-size: 14px; }
    .btn-cancel:hover { background: #f4f6f9; }
    .btn-submit { display: flex; align-items: center; gap: 7px; background: #3b3b58; color: #fff; border: none; padding: 11px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #2b2b42; }

    .image-box-wrap { margin-bottom: 12px; }

.image-box {
    width: 100%;
    max-width: 260px;
    aspect-ratio: 4 / 3;
    border-radius: 8px;
    border: 1px solid #e2e2e2;
    background: #f7f7f8;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
}

.image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.current-image-label {
    display: block;
    font-size: 12px;
    color: #888;
    margin-bottom: 10px;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('serviceImageInput');
        const currentBox = document.getElementById('currentImageBox');
        const previewBox = document.getElementById('imagePreviewBox');
        const previewLabel = document.getElementById('previewLabel');
        const preview = document.getElementById('imagePreview');

        if (input) {
            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        previewBox.style.display = 'flex';
                        previewLabel.style.display = 'block';
                        if (currentBox) currentBox.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewBox.style.display = 'none';
                    previewLabel.style.display = 'none';
                    preview.src = '';
                    if (currentBox) currentBox.style.display = 'flex';
                }
            });
        }
    });
</script>

@endsection