@extends('admin.layout')
@section('title', 'Banner Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <div class="form-header">
        <h4>
            <i class="bi bi-image"></i>
            Banner Section
        </h4>
    </div>
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

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#e74c3c'
                });
            });
        </script>
    @endif

   <form action="{{ route('admin.home.banner.store') }}"
      method="POST" enctype="multipart/form-data" class="banner-form">
    @csrf

    <div class="container-fluid px-0">
        <div class="row">

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type"></i> Title</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                               class="{{ $errors->has('title') ? 'input-error' : '' }}"
                               placeholder="Enter banner title">
                        @error('title')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><i class="bi bi-text-paragraph"></i> Description</label>
                        <textarea name="description" rows="4"
                                  class="{{ $errors->has('description') ? 'input-error' : '' }}"
                                  placeholder="Enter banner description">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-images"></i> Images (1 to 3 required)</label>
                    <p class="hint-text">Accepted: JPG, PNG, WEBP — Max size: <strong>2MB</strong> per image</p>

                    @error('image_1')
                        @if (str_contains($message, 'at least 1 image'))
                            <span class="field-error group-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @endif
                    @enderror

                    <div class="image-grid">
                        @foreach (['image_1' => 'Image 1', 'image_2' => 'Image 2', 'image_3' => 'Image 3'] as $field => $label)
                            <div class="image-upload-box">
                                <span class="image-label">{{ $label }}</span>
                                <div class="preview-wrap">
                                    @if ($banner->{$field})
                                        <img src="{{ Storage::url($banner->{$field}) }}" class="preview-img" id="preview-{{ $field }}">
                                    @else
                                        <div class="preview-placeholder" id="preview-{{ $field }}">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <label class="upload-btn {{ $errors->has($field) ? 'upload-btn-error' : '' }}">
                                    <i class="bi bi-upload"></i> Choose file
                                    <input type="file" name="{{ $field }}" accept="image/*"
                                           data-max-size="5"
                                           onchange="previewImage(this, 'preview-{{ $field }}'); showFileSize(this, 'size-{{ $field }}')" hidden>
                                </label>
                                <span class="file-size-info" id="size-{{ $field }}"></span>

                                @error($field)
                                    @unless (str_contains($message, 'at least 1 image'))
                                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @endunless
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <label class="section-label"><i class="bi bi-camera-video"></i> Video</label>
                    <p class="hint-text">Accepted: MP4, MOV, AVI, WMV — Max size: <strong>5MB</strong></p>

                    <div class="video-upload-box">
                        @if ($banner->video)
                            <video src="{{ Storage::url($banner->video) }}" controls class="preview-video"></video>
                        @else
                            <div class="preview-placeholder video-placeholder">
                                <i class="bi bi-camera-video"></i>
                            </div>
                        @endif
                        <label class="upload-btn {{ $errors->has('video') ? 'upload-btn-error' : '' }}">
                            <i class="bi bi-upload"></i> Choose video
                            <input type="file" name="video" accept="video/*" hidden
                                   data-max-size="50"
                                   onchange="showFileSize(this, 'size-video'); this.parentElement.nextElementSibling.textContent = this.files[0]?.name || ''">
                        </label>
                        <span class="file-name"></span>
                        <span class="file-size-info" id="size-video"></span>

                        @error('video')
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
                        Save Banner
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

    <script>

        function showFileSize(input, displayId) {
    const display = document.getElementById(displayId);
    if (!input.files || !input.files[0]) {
        display.textContent = '';
        display.classList.remove('size-error');
        return;
    }

    const file = input.files[0];
    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
    const maxMB = parseFloat(input.dataset.maxSize);

    if (sizeMB > maxMB) {
        display.innerHTML = `<i class="bi bi-exclamation-triangle"></i> ${sizeMB} MB — exceeds ${maxMB}MB limit!`;
        display.classList.add('size-error');
    } else {
        display.innerHTML = `<i class="bi bi-check-circle"></i> ${sizeMB} MB`;
        display.classList.remove('size-error');
    }
}

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

        function enforceMutualExclusivity() {
            const imageInputs = document.querySelectorAll('input[name="image_1"], input[name="image_2"], input[name="image_3"]');
            const videoInput = document.querySelector('input[name="video"]');
            const imageSection = document.querySelector('.image-grid').closest('.form-card');
            const videoSection = videoInput.closest('.form-card');

            function anyImageSelected() {
                return Array.from(imageInputs).some(input => input.files && input.files.length > 0);
            }

            function videoSelected() {
                return videoInput.files && videoInput.files.length > 0;
            }

            function updateState() {
                if (videoSelected()) {
                    imageInputs.forEach(input => input.value = '');
                    imageSection.style.opacity = '0.4';
                    imageSection.style.pointerEvents = 'none';

                    let note = imageSection.querySelector('.exclusivity-note');
                    if (!note) {
                        note = document.createElement('p');
                        note.className = 'exclusivity-note';
                        imageSection.insertBefore(note, imageSection.firstChild.nextSibling);
                    }
                    note.innerHTML = '<i class="bi bi-info-circle"></i> Images are disabled because a video is selected. Remove the video to enable images.';

                } else if (anyImageSelected()) {
                    videoInput.value = '';
                    videoSection.style.opacity = '0.4';
                    videoSection.style.pointerEvents = 'none';

                    let note = videoSection.querySelector('.exclusivity-note');
                    if (!note) {
                        note = document.createElement('p');
                        note.className = 'exclusivity-note';
                        videoSection.insertBefore(note, videoSection.firstChild.nextSibling);
                    }
                    note.innerHTML = '<i class="bi bi-info-circle"></i> Video is disabled because an image is selected. Remove all images to enable video.';

                } else {
                    imageSection.style.opacity = '1';
                    imageSection.style.pointerEvents = 'auto';
                    videoSection.style.opacity = '1';
                    videoSection.style.pointerEvents = 'auto';

                    const imgNote = imageSection.querySelector('.exclusivity-note');
                    if (imgNote) imgNote.remove();

                    const vidNote = videoSection.querySelector('.exclusivity-note');
                    if (vidNote) vidNote.remove();
                }
            }

            imageInputs.forEach(input => input.addEventListener('change', updateState));
            videoInput.addEventListener('change', updateState);
        }

        document.addEventListener('DOMContentLoaded', enforceMutualExclusivity);
    </script>

    <style>
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #fdecea;
            color: #c0392b;
            display: flex;
            gap: 10px;
        }
        .alert-error ul {
            margin-left: 16px;
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

     .banner-form { width: 100%; }

        .form-card {
            background: #fff;
            padding: 22px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-group:last-child {
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
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s ease;
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
            margin-bottom: 14px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .image-upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .image-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 8px;
        }
        .preview-wrap {
            width: 100%;
        }
        .preview-img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
            margin-bottom: 10px;
        }
        .preview-placeholder {
            width: 100%;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
            border-radius: 6px;
            border: 1px dashed #ddd;
            color: #bbb;
            font-size: 24px;
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
            transition: background 0.2s ease;
        }
        .upload-btn:hover {
            background: #e9ecf2;
        }

        .video-upload-box {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .preview-video {
            width: 260px;
            height: 140px;
            border-radius: 6px;
            background: #000;
        }
        .video-placeholder {
            width: 260px;
        }
        .file-name {
            font-size: 13px;
            color: #666;
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
            transition: background 0.2s ease;
        }
        .btn-submit:hover {
            background: #2b2b42;
        }

        .input-error {
    border-color: #e74c3c !important;
    background: #fff8f8;
}

.upload-btn-error {
    border-color: #e74c3c !important;
    background: #fff8f8 !important;
    color: #c0392b !important;
}

.field-error {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #e74c3c;
    font-size: 12.5px;
    margin-top: 6px;
}

.field-error i {
    font-size: 13px;
}
.group-error {
    display: flex;
    margin-bottom: 14px;
}

.hint-text {
    font-size: 12.5px;
    color: #888;
    margin-bottom: 14px;
}
.hint-text strong {
    color: #555;
}

.file-size-info {
    display: block;
    font-size: 12px;
    color: #1e8449;
    margin-top: 6px;
}
.file-size-info.size-error {
    color: #e74c3c;
    font-weight: 600;
}

.exclusivity-note {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #fff8e1;
    color: #8a6d00;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12.5px;
    margin-bottom: 14px;
}

    </style>

@endsection
