@extends('admin.layout')
@section('title', 'Contact Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .form-card {
        background: #fff;
        padding: 22px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 18px;
    }

    .form-group { margin-bottom: 0; }

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
    .form-group input[type="email"],
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
    .form-group textarea:focus { border-color: #3b3b58; }

    .input-error { border-color: #e74c3c !important; background: #fff8f8; }

    .field-error {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #e74c3c;
        font-size: 12.5px;
        margin-top: 6px;
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

    .image-upload-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        max-width: 400px;
    }

    .preview-wrap { width: 100%; }

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

    .upload-btn:hover { background: #e9ecf2; }

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
        margin-top: 10px;
    }

    .btn-submit:hover { background: #2b2b42; }
</style>

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

<form action="{{ route('admin.contact.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="form-card">
                <div class="form-group">
                    <label><i class="bi bi-type"></i> Banner Title</label>
                    <input type="text" name="banner_title" value="{{ old('banner_title', $contact->banner_title) }}"
                        class="{{ $errors->has('banner_title') ? 'input-error' : '' }}"
                        placeholder="e.g. Get in Touch">
                    @error('banner_title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-card">
                <label class="section-label"><i class="bi bi-image"></i> Banner Image</label>
                <div class="image-upload-box">
                    <div class="preview-wrap">
                        @if ($contact->banner_image)
                            <img src="{{ Storage::url($contact->banner_image) }}" class="preview-img" id="preview-banner-image">
                        @else
                            <div class="preview-placeholder" id="preview-banner-image">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>
                    <label class="upload-btn">
                        <i class="bi bi-upload"></i> Choose file
                        <input type="file" name="banner_image" accept="image/*" hidden
                               onchange="previewImage(this, 'preview-banner-image')">
                    </label>
                    @error('banner_image')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-group">
                    <label><i class="bi bi-telephone"></i> Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}"
                        class="{{ $errors->has('phone') ? 'input-error' : '' }}"
                        placeholder="e.g. +964 750 000 0000">
                    @error('phone')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-group">
                    <label><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $contact->email) }}"
                        class="{{ $errors->has('email') ? 'input-error' : '' }}"
                        placeholder="e.g. info@eis.com">
                    @error('email')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-card">
                <div class="form-group">
                    <label><i class="bi bi-geo-alt"></i> Address</label>
                    <textarea name="address" rows="3"
                        class="{{ $errors->has('address') ? 'input-error' : '' }}"
                        placeholder="e.g. Erbil, Iraq &amp; Jebel Ali Free Zone, Dubai, UAE">{{ old('address', $contact->address) }}</textarea>
                    @error('address')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-12">
    <div class="form-card">
        <label class="section-label"><i class="bi bi-image"></i> Get in Touch Photo</label>
        <div class="image-upload-box">
            <div class="preview-wrap">
                @if ($contact->contact_image)
                    <img src="{{ Storage::url($contact->contact_image) }}" class="preview-img" id="preview-contact-image">
                @else
                    <div class="preview-placeholder" id="preview-contact-image">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </div>
            <label class="upload-btn">
                <i class="bi bi-upload"></i> Choose file
                <input type="file" name="contact_image" accept="image/*" hidden
                       onchange="previewImage(this, 'preview-contact-image')">
            </label>
            @error('contact_image')
            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
        <div class="col-md-12">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<script>
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
</script>

@endsection