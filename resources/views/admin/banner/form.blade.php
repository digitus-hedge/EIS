@extends('admin.layout')
@section('title', 'Banner Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: @json(session('success')),
            confirmButtonColor: '#EF7B2E',
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

@if ($errors->any())
<div class="notice caution" style="margin-bottom:20px;">
    <i class="bi bi-exclamation-triangle" style="font-size:15px;flex-shrink:0;margin-top:1px;"></i>
    <p>Please fix the highlighted fields below before submitting.</p>
</div>
@endif

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Banner Section</b>
    </div>

    <div class="header">
        <div>
            <h1>Banner Section</h1>
            <p>This is the first thing visitors see on your homepage. Use either up to 3 images, or a single video — not both.</p>
        </div>
    </div>

    <form action="{{ route('admin.home.banner.store') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
        @csrf

        {{-- Title + Description --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type"></i></span> Banner Details</h2>
            </div>

            <div class="field">
                <div class="field-top">
                    <label class="field-label">Title <span class="req">*</span></label>
                </div>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                       class="{{ $errors->has('title') ? 'input-error' : '' }}"
                       placeholder="Enter banner title">
                @error('title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <div class="field-top">
                    <label class="field-label">Description<span class="req">*</span></label>
                </div>
                <textarea name="description" rows="4"
                          class="{{ $errors->has('description') ? 'input-error' : '' }}"
                          placeholder="Enter banner description">{{ old('description', $banner->description) }}</textarea>
                @error('description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Images --}}
        <div class="card" id="imageSection">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-images"></i></span> Images <span class="req">*</span></h2>
                <span class="section-sub" style="margin:0;">1 to 3 images required</span>
            </div>

                     <div class="notice caution">
                        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                        <p><b>Recommended size:</b> {{ $imageWidth ?? 1200 }} &times; {{ $imageHeight ?? 600 }}px &middot; 16:9 landscape &middot; <b>Suppoted Type and Size:</b>JPG, PNG, WEBP &middot; up to 10MB each.</p>
                    </div>

            @error('image_1')
                @if (str_contains($message, 'at least 1 image'))
                    <div class="notice caution" style="margin-bottom:16px;">
                        <i class="bi bi-exclamation-circle" style="margin-top:1px;"></i>
                        <p>{{ $message }}</p>
                    </div>
                @endif
            @enderror

            <div class="images-row">
                @foreach (['image_1' => 'Image 1', 'image_2' => 'Image 2', 'image_3' => 'Image 3'] as $field => $label)
                    <div class="image-slot">
                        <div class="slot-top">
                            <span class="slot-label">{{ $label }}</span>
                        </div>
                        <div class="drop img-slot {{ $banner->{$field} ? 'filled' : '' }}" data-file-input="file-{{ $field }}" onclick="handleDropClick(this)">
                            @if ($banner->{$field})
                                <img src="{{ Storage::url($banner->{$field}) }}" id="preview-{{ $field }}" alt="{{ $label }}">
                                <button type="button" class="remove-img-btn" onclick="removeUploadedImage(event, this, '{{ $field }}', 'preview-{{ $field }}')" title="Remove image">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <div class="uploaded-tag"><i class="bi bi-check-circle"></i> Uploaded</div>
                            @else
                                <div class="preview-placeholder" id="preview-{{ $field }}">
                                    <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                                    <div class="drop-title">Click to upload</div>
                                    <div class="drop-sub">or drag &amp; drop</div>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="file-{{ $field }}" name="{{ $field }}" accept="image/*" data-max-size="10" hidden
                               onchange="previewImage(this,'preview-{{ $field }}'); showFileSize(this,'size-{{ $field }}'); enforceMutualExclusivity()">
                        <input type="hidden" name="remove_{{ $field }}" id="remove-{{ $field }}" value="0">
                        @if (!$banner->{$field})
                            <button type="button" class="choose-btn" onclick="document.getElementById('file-{{ $field }}').click()">Choose file</button>
                        @endif
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

        {{-- Video --}}
        <div class="card" id="videoSection">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-camera-video"></i></span> Video <span class="optional">(optional)</span></h2>
                <span class="section-sub" style="margin:0;">MP4, MOV, AVI, WMV, WEBM &middot; up to 20MB</span>
            </div>


                <div class="notice caution">
                        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                        <p>1920 × 1080px, 16:9 landscape, under 30 seconds, and compressed to keep it under 20MB. Videos play muted and looped in the banner.</p>
                    </div>



            <div class="video-slot-wrap">
                @if ($banner->video)
                    <div class="video-drop-filled" id="videoPreviewWrap">
                        <video src="{{ Storage::url($banner->video) }}" controls></video>
                        <button type="button" class="remove-img-btn" onclick="removeUploadedVideo(event)" title="Remove video">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @else
                    <div class="video-drop" id="videoDrop" onclick="document.getElementById('file-video').click()">
                        <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload a video</div>
                        <div class="drop-sub">Autoplays muted on the homepage banner</div>
                    </div>
                @endif
                <input type="file" id="file-video" name="video" accept="video/*" data-max-size="20" hidden
                       onchange="showFileSize(this,'size-video'); enforceMutualExclusivity()">
                <input type="hidden" name="remove_video" id="remove-video" value="0">
                <span class="file-size-info" id="size-video"></span>
                @error('video')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- SEO Meta --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-search"></i></span> SEO Meta</h2>
            </div>
            <p class="section-sub" style="margin:0 0 16px;">Used for search engine results and social share previews.</p>

            <div class="field">
                <div class="field-top">
                    <label class="field-label">Meta Title</label>
                    <span class="field-hint">Recommended under 60 chars</span>
                </div>
                <input type="text" name="meta_title" value="{{ old('meta_title', $banner->meta_title) }}" maxlength="60"
                       class="{{ $errors->has('meta_title') ? 'input-error' : '' }}"
                       placeholder="Enter meta title">
                @error('meta_title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <div class="field-top">
                    <label class="field-label">Meta Description</label>
                    <span class="field-hint">Recommended under 160 chars</span>
                </div>
                <textarea name="meta_description" rows="3" maxlength="160"
                          class="{{ $errors->has('meta_description') ? 'input-error' : '' }}"
                          placeholder="Enter meta description">{{ old('meta_description', $banner->meta_description) }}</textarea>
                @error('meta_description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">All changes save to the live homepage banner</span>
                <div class="btn-group">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        Save Banner
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function handleDropClick(el) {
        if (el.classList.contains('filled')) return;
        const inputId = el.getAttribute('data-file-input');
        const input = document.getElementById(inputId);
        if (input) input.click();
    }

    function showFileSize(input, displayId) {
        const display = document.getElementById(displayId);
        if (!display) return;
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
        if (!preview) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = previewId;
                preview.replaceWith(img);

                const drop = img.closest('.drop');
                if (drop) {
                    drop.classList.add('filled');
                    const chooseBtn = drop.parentElement.querySelector('.choose-btn');
                    if (chooseBtn) chooseBtn.style.display = 'none';

                    const removeInput = drop.parentElement.querySelector('input[type="hidden"][id^="remove-"]');
                    if (removeInput) removeInput.value = '0';

                    if (!drop.querySelector('.remove-img-btn')) {
                        const fieldName = removeInput ? removeInput.id.replace('remove-', '') : null;
                        if (fieldName) {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'remove-img-btn';
                            btn.title = 'Remove image';
                            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
                            btn.onclick = (ev) => removeUploadedImage(ev, btn, fieldName, previewId);
                            drop.appendChild(btn);
                        }
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeUploadedImage(event, btn, fieldName, previewId) {
        event.stopPropagation();
        const drop = btn.closest('.drop');
        const wrapper = drop.parentElement;
        const fileInput = wrapper.querySelector('input[type="file"]');
        const removeInput = document.getElementById(`remove-${fieldName}`);

        if (removeInput) removeInput.value = '1';
        if (fileInput) fileInput.value = '';

        drop.classList.remove('filled');
        drop.innerHTML = `
            <div class="preview-placeholder" id="${previewId}">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        `;

        let chooseBtn = wrapper.querySelector('.choose-btn');
        if (!chooseBtn && fileInput) {
            chooseBtn = document.createElement('button');
            chooseBtn.type = 'button';
            chooseBtn.className = 'choose-btn';
            chooseBtn.textContent = 'Choose file';
            chooseBtn.onclick = () => fileInput.click();
            wrapper.appendChild(chooseBtn);
        } else if (chooseBtn) {
            chooseBtn.style.display = 'block';
        }

        const sizeInfo = wrapper.querySelector('.file-size-info');
        if (sizeInfo) sizeInfo.textContent = '';

        enforceMutualExclusivity();
    }

    function removeUploadedVideo(event) {
        event.stopPropagation();
        document.getElementById('remove-video').value = '1';
        document.getElementById('file-video').value = '';

        const wrap = document.getElementById('videoPreviewWrap');
        wrap.outerHTML = `
            <div class="video-drop" id="videoDrop" onclick="document.getElementById('file-video').click()">
                <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload a video</div>
                <div class="drop-sub">Autoplays muted on the homepage banner</div>
            </div>
        `;
        document.getElementById('size-video').textContent = '';
        enforceMutualExclusivity();
    }

    // ===== Images and video are mutually exclusive: selecting one disables the other =====
    function enforceMutualExclusivity() {
        const imageInputs = document.querySelectorAll('input[name="image_1"], input[name="image_2"], input[name="image_3"]');
        const videoInput = document.querySelector('input[name="video"]');
        const imageSection = document.getElementById('imageSection');
        const videoSection = document.getElementById('videoSection');

        function anyImageActive() {
            const anyNewFile = Array.from(imageInputs).some(input => input.files && input.files.length > 0);
            const anyExistingKept = document.querySelectorAll('#imageSection .drop.filled').length > 0;
            return anyNewFile || anyExistingKept;
        }

        function videoActive() {
            const newFile = videoInput.files && videoInput.files.length > 0;
            const existingKept = document.getElementById('videoPreviewWrap') !== null;
            return newFile || existingKept;
        }

        function clearNote(section) {
            const note = section.querySelector('.exclusivity-note');
            if (note) note.remove();
        }

        function addNote(section, message) {
            let note = section.querySelector('.exclusivity-note');
            if (!note) {
                note = document.createElement('div');
                note.className = 'notice caution exclusivity-note';
                note.innerHTML = `<i class="bi bi-info-circle" style="margin-top:1px;"></i><p></p>`;
                section.querySelector('.section-title').insertAdjacentElement('afterend', note);
            }
            note.querySelector('p').textContent = message;
        }

        if (videoActive()) {
            imageSection.style.opacity = '0.45';
            imageSection.style.pointerEvents = 'none';
            addNote(imageSection, 'Images are disabled because a video is selected. Remove the video to enable images.');
            videoSection.style.opacity = '1';
            videoSection.style.pointerEvents = 'auto';
            clearNote(videoSection);
        } else if (anyImageActive()) {
            videoSection.style.opacity = '0.45';
            videoSection.style.pointerEvents = 'none';
            addNote(videoSection, 'Video is disabled because an image is selected. Remove all images to enable video.');
            imageSection.style.opacity = '1';
            imageSection.style.pointerEvents = 'auto';
            clearNote(imageSection);
        } else {
            imageSection.style.opacity = '1';
            imageSection.style.pointerEvents = 'auto';
            videoSection.style.opacity = '1';
            videoSection.style.pointerEvents = 'auto';
            clearNote(imageSection);
            clearNote(videoSection);
        }
    }

    document.addEventListener('DOMContentLoaded', enforceMutualExclusivity);

    document.addEventListener('DOMContentLoaded', function () {
    // ===== Scroll to the first validation error on page load =====
    const firstErrorField = document.querySelector('.input-error, .upload-btn-error');
    const firstErrorMsg = document.querySelector('.field-error');

    if (firstErrorField) {
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Give a brief highlight so the eye lands exactly on the right field
        firstErrorField.classList.add('error-flash');
        setTimeout(() => firstErrorField.classList.remove('error-flash'), 1500);
    } else if (firstErrorMsg) {
        // Fallback: some errors (like the "at least 1 image" group error) don't
        // sit on an input directly — scroll to the message itself instead.
        firstErrorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<style>
    .req{ color: var(--orange, #EF7B2E); }

    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap; }
    .header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; line-height:1.55; }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:4px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .section-title .optional{ font-size:11px; font-weight:400; color: var(--faint,#9AA1B2); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }
    .section-sub{ font-size:12px; color: var(--faint,#9AA1B2); margin:0 0 16px; }

    .field{ margin-bottom:28px; }
    .field:last-child{ margin-bottom:0; }
    .field-top{ display:flex; align-items:baseline; justify-content:space-between; margin-bottom:8px; }
    .field-label{ display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color: var(--ink,#171B2C); }
    .field-hint{ font-size:11.5px; color: var(--faint,#9AA1B2); }

    input[type=text], textarea{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s;
    }
    input[type=text]:focus, textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    textarea{ resize:vertical; line-height:1.5; }
    .input-error{ border-color:#e74c3c !important; background:#fff8f8; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12.5px; margin-top:6px; }

    .notice{ margin-top:16px; display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; }
    .notice p{ font-size:12px; color: var(--muted,#667085); margin:0; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; margin-top:0; margin-bottom:16px; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; }
    .notice.caution p b{ color:#6B4A0E; font-weight:700; }
    .exclusivity-note{ margin-bottom:0; }

    .images-row{ display:flex; gap:16px; flex-wrap:wrap; }
    .image-slot{ flex:1; min-width:160px; }
    .slot-top{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
    .slot-label{ font-size:12px; font-weight:500; color: var(--muted,#667085); }

    .drop{
        position:relative; aspect-ratio:4/3; border-radius:12px;
        border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; overflow:hidden; transition:border-color .15s, background .15s; text-align:center;
    }
    .drop:hover{ border-color: var(--orange,#EF7B2E); background: var(--orange-tint,#FFF8F3); }
    .drop.filled{ border:2px solid transparent; background:#0F1220; cursor:default; }
    .drop img{ width:100%; height:100%; object-fit:cover; display:block; }
    .ico-circle{ width:40px; height:40px; border-radius:999px; background:#EEF0F6; display:flex; align-items:center; justify-content:center; margin-bottom:8px; }
    .drop-title{ font-size:12px; font-weight:500; color: var(--muted,#667085); }
    .drop-sub{ font-size:11px; color:#B0B5C4; margin-top:2px; }
    .uploaded-tag{
        position:absolute; left:0; right:0; bottom:0; padding:8px 12px;
        background:linear-gradient(to top, rgba(0,0,0,0.55), transparent);
        color:rgba(255,255,255,0.9); font-size:11px; display:flex; align-items:center; gap:4px;
    }
    .choose-btn{
        margin-top:8px; width:100%; font-size:12px; font-weight:600; color: var(--orange,#EF7B2E);
        background:#fff; border:1px solid var(--orange-border,#F3D8C2); border-radius:8px;
        padding:7px 0; cursor:pointer; transition:background .15s;
    }
    .choose-btn:hover{ background: var(--orange-tint,#FFF8F3); }

    .remove-img-btn{
        position:absolute; top:8px; right:8px; width:28px; height:28px; border-radius:999px;
        background:rgba(0,0,0,0.6); border:none; color:#fff; display:flex; align-items:center;
        justify-content:center; cursor:pointer; transition:background .15s; z-index:3; font-size:13px;
    }
    .remove-img-btn:hover{ background:rgba(0,0,0,0.85); }

    .video-slot-wrap{ max-width:360px; }
    .video-drop{
        aspect-ratio:16/9; border-radius:12px;
        border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; transition:border-color .15s, background .15s;
    }
    .video-drop:hover{ border-color: var(--orange,#EF7B2E); background: var(--orange-tint,#FFF8F3); }
    .video-drop-filled{ position:relative; border-radius:12px; overflow:hidden; background:#000; }
    .video-drop-filled video{ width:100%; display:block; }

    .file-size-info{ display:block; font-size:12px; color:#1e8449; margin-top:6px; }
    .file-size-info.size-error{ color:#e74c3c; font-weight:600; }

    .savebar{
        position:sticky; bottom:0; border-top:1px solid var(--line,#E9EBF2);
        background:rgba(255,255,255,0.92); backdrop-filter:blur(6px);
        margin:24px -32px -32px; padding:0 32px;
        box-shadow:0 -4px 16px -8px rgba(15,21,38,0.06);
    }
    .savebar-inner{ padding:16px 0; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .savebar-status{ font-size:12px; color: var(--faint,#9AA1B2); }
    .btn-group{ display:flex; align-items:center; gap:12px; }
    .btn-cancel{
        font-size:13px; font-weight:600; color: var(--muted,#667085); background:none; border:none;
        padding:10px 16px; border-radius:8px; cursor:pointer; text-decoration:none; transition:color .15s, background .15s;
    }
    .btn-cancel:hover{ color: var(--ink,#171B2C); background: var(--canvas,#F6F7FB); }
    .btn-save{
        display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }
</style>

@endsection