@extends('admin.layout')
@section('title', 'Why Choose Us Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle"></i>
        <div>
            Please fill below fields before submitting:
            <ul style="margin: 6px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
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
        <i class="bi bi-star"></i>
        Why Choose Us Section
    </h4>
</div>

<form action="{{ route('admin.home.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" class="banner-form">
    @csrf

    <div class="container-fluid px-0">
        <div class="row">

            {{-- Main heading block --}}
            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type-h1"></i> Heading</label>
                        <input type="text" name="heading" value="{{ old('heading', $why->heading) }}"
                               class="{{ $errors->has('heading') ? 'input-error' : '' }}"
                               placeholder="e.g. Quality and accountability in every operation.">
                        @error('heading')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-top:16px;">
                        <label><i class="bi bi-card-text"></i> Description</label>
                        <textarea name="description" rows="3" placeholder="Our approach is built around...">{{ old('description', $why->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Mission --}}
            @php $blocks = [
                'mission' => 'Mission',
                'vision' => 'Vision',
                'values' => 'Core Values',
            ]; @endphp

            @foreach ($blocks as $key => $label)
                <div class="col-md-4">
                    <div class="form-card block-card">
                        <label class="section-label"><i class="bi bi-flag"></i> {{ $label }}</label>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="{{ $key }}_title" value="{{ old($key.'_title', $why->{$key.'_title'}) }}" placeholder="{{ $label }}">
                        </div>

                        <div class="form-group" style="margin-top:12px;">
                            <label>Description</label>
                            <textarea name="{{ $key }}_description" rows="4" placeholder="Describe the {{ strtolower($label) }}...">{{ old($key.'_description', $why->{$key.'_description'}) }}</textarea>
                        </div>

                        <div class="form-group" style="margin-top:12px;">
                            <label>Background Image</label>
                            <p class="hint-text">Max <strong>2MB</strong> — JPG, PNG, WEBP</p>

                            <div class="image-upload-box">
                                <div class="preview-wrap">
                                    @if ($why->{$key.'_image'})
                                        <img src="{{ Storage::url($why->{$key.'_image'}) }}" class="preview-img" id="preview-{{ $key }}">
                                    @else
                                        <div class="preview-placeholder" id="preview-{{ $key }}">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <label class="upload-btn">
                                    <i class="bi bi-upload"></i> Choose file
                                    <input type="file" name="{{ $key }}_image" accept="image/*"
                                           onchange="previewImage(this, 'preview-{{ $key }}')" hidden>
                                </label>
                                @error($key.'_image')
                                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Commitment (text only, no image) --}}
            <div class="col-md-12">
                <div class="form-card block-card commitment-card">
                    <label class="section-label"><i class="bi bi-shield-check"></i> Commitment</label>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="commitment_title" value="{{ old('commitment_title', $why->commitment_title) }}" placeholder="e.g. OUR COMMITMENT">
                    </div>

                    <div class="form-group" style="margin-top:12px;">
                        <label>Description</label>
                        <textarea name="commitment_description" rows="4" placeholder="We are committed to providing...">{{ old('commitment_description', $why->commitment_description) }}</textarea>
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

<script>
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
    .banner-form { width: 100%; }
    .form-card { background: #fff; padding: 22px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 18px; height: calc(100% - 18px); }
    .form-group label { display: block; margin-bottom: 7px; font-weight: 600; font-size: 13px; color: #333; }
    .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; font-family: inherit; resize: vertical; }
    .form-group input:focus, .form-group textarea:focus { border-color: #3b3b58; }
    .section-label { display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 15px; color: #1e1e2d; margin-bottom: 14px; }
    .hint-text { font-size: 12px; color: #888; margin: 4px 0 10px; }
    .input-error { border-color: #e74c3c !important; background: #fff8f8; }
    .field-error { display: flex; align-items: center; gap: 5px; color: #e74c3c; font-size: 12.5px; margin-top: 6px; }

    .block-card { border-top: 3px solid #b40707; }
    .commitment-card { border-top: 3px solid #3b3b58; }

    .image-upload-box { display: flex; flex-direction: column; align-items: flex-start; }
    .preview-wrap { width: 100%; }
    .preview-img { width: 100%; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; margin-bottom: 10px; }
    .preview-placeholder { width: 100%; height: 140px; display: flex; align-items: center; justify-content: center; background: #f4f6f9; border-radius: 6px; border: 1px dashed #ddd; color: #bbb; font-size: 26px; margin-bottom: 10px; }
    .upload-btn { display: inline-flex; align-items: center; gap: 6px; background: #f4f6f9; color: #3b3b58; padding: 7px 14px; border-radius: 6px; font-size: 13px; cursor: pointer; border: 1px solid #ddd; }
    .upload-btn:hover { background: #e9ecf2; }

    .form-actions { display: flex; gap: 12px; margin-top: 6px; }
    .btn-cancel { padding: 11px 22px; border-radius: 6px; border: 1px solid #ddd; color: #555; text-decoration: none; font-size: 14px; }
    .btn-cancel:hover { background: #f4f6f9; }
    .btn-submit { display: flex; align-items: center; gap: 7px; background: #3b3b58; color: #fff; border: none; padding: 11px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #2b2b42; }
</style>

@endsection
