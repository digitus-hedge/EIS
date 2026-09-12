@extends('admin.layout')

@section('title', 'Operations')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<div class="notice caution" style="margin-bottom:20px;">
    <i class="bi bi-exclamation-triangle" style="font-size:15px;flex-shrink:0;margin-top:1px;"></i>
    <p>Please fix the highlighted fields below before submitting.</p>
</div>
@endif

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
            confirmButtonColor: '#D5392F'
        });
    });
</script>
@endif

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Operations</b>
    </div>

    <div class="header">
        <div>
            <h1>Operations</h1>
            <p>Add and manage operation videos with a title, description, thumbnail, and video file.</p>
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-camera-video"></i></span> Add New Video</h2>
        </div>

        <form action="{{ route('admin.about.operation.videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <div class="field-top"><label class="field-label">Title<span class="req">*</span></label></div>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="{{ $errors->has('title') ? 'input-error' : '' }}"
                       placeholder="Enter video title">
                @error('title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="field" style="margin-top:16px;">
                <div class="field-top"><label class="field-label">Description<span class="req">*</span></label></div>
                <textarea name="description" rows="3"
                          class="{{ $errors->has('description') ? 'input-error' : '' }}"
                          placeholder="Enter video description">{{ old('description') }}</textarea>
                @error('description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="op-two-col">
                <div class="field">
                    <div class="field-top"><label class="field-label">Thumbnail Image<span class="req">*</span></label></div>
                    <div class="notice caution">
                        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                        <p><b>Recommended Size:</b>280 × 220px ·JPG, PNG, WEBP &middot; up to 10MB.</p>
                    </div>
                    <div class="image-slot" style="max-width:100%;">
                        <div class="drop img-slot {{ $errors->has('thumbnail') ? 'input-error' : '' }}" data-file-input="op-thumb-input" onclick="handleDropClick(this)">
                            <div class="preview-placeholder" id="op-thumb-preview">
                                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                                <div class="drop-title">Click to upload</div>
                                <div class="drop-sub">or drag &amp; drop</div>
                            </div>
                        </div>
                        <input type="file" id="op-thumb-input" name="thumbnail" accept="image/*" hidden
                               onchange="previewImage(this, 'op-thumb-preview')">
                    </div>
                    @error('thumbnail')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <div class="field-top"><label class="field-label">Video File<span class="req">*</span></label></div>
                    <div class="notice caution">
                        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                        <p><b>Featured Size:</b>1920 × 1080px,  MP4, MOV, WEBM &middot; up to 20MB.</p>
                    </div>
                    <div class="video-drop {{ $errors->has('video') ? 'input-error' : '' }}" onclick="document.getElementById('op-video-input').click()" id="op-video-drop">
                        <div class="preview-placeholder" id="op-video-preview">
                            <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    </div>
                    <input type="file" id="op-video-input" name="video" accept="video/*" hidden
                           onchange="showVideoFileName(this)">
                    @error('video')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-save" style="margin-top:20px;">
                <i class="bi bi-check-lg"></i> Add Video
            </button>
        </form>
    </div>

    <div class="card">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-collection-play"></i></span> Existing Videos</h2>
        </div>

        @forelse ($videos as $video)
            <div class="op-video-card">
                @if ($video->thumbnail)
                    <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}">
                @else
                    <div class="op-video-thumb-placeholder"><i class="bi bi-image"></i></div>
                @endif
                <div class="body">
                    <div class="op-video-top">
                        <div>
                            <strong>{{ $video->title }}</strong>
                            @if ($video->video)
                                <span class="op-video-tag ok"><i class="bi bi-camera-video-fill"></i> Video attached</span>
                            @else
                                <span class="op-video-tag error"><i class="bi bi-exclamation-circle"></i> No video file uploaded</span>
                            @endif
                        </div>
                        <form action="{{ route('admin.about.operation.videos.destroy', $video) }}" method="POST"
                              class="delete-form" id="delete-form-{{ $video->id }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="icon-btn icon-delete btn-delete-trigger"
                                    data-form-id="delete-form-{{ $video->id }}"
                                    data-name="{{ $video->title }}" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                    <p>{{ $video->description }}</p>
                </div>
            </div>
        @empty
            <p class="field-hint">No videos added yet.</p>
        @endforelse
    </div>
</div>

<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap; }
    .header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; max-width:560px; line-height:1.55; }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }
    .req{ color: var(--orange, #EF7B2E); }

    .field{ margin-bottom:0; }
    .field-top{ margin-bottom:8px; }
    .field-label{ font-size:13px; font-weight:600; color: var(--ink,#171B2C); }
    .field-hint{ font-size:13px; color: var(--faint,#9AA1B2); }

    input[type=text], textarea{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s; resize:vertical;
    }
    input[type=text]:focus, textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#E9483F !important; background:#FFF5F4; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#D5392F; font-size:12.5px; margin-top:6px; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:9px 12px; margin-bottom:12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; font-size:13px; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:11.5px; }

    .op-two-col{ display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-top:16px; }

    /* ===== Image / video drop slots ===== */
    .drop.img-slot, .video-drop{
        position:relative; width:100%; height:160px;
        border:1.5px dashed var(--input-border,#DBDFEA); border-radius:12px; background:#fff;
        display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden;
        transition:border-color .15s ease;
    }
    .drop.img-slot:hover, .video-drop:hover{ border-color: var(--orange-border,#F3D8C2); }
    .drop.img-slot.filled{ border-style:solid; padding:0; }
    .drop.img-slot img{ width:100%; height:100%; object-fit:cover; }
    .drop.img-slot.input-error, .video-drop.input-error{ border-color:#E9483F; background:#FFF5F4; }

    .preview-placeholder{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; text-align:center; }
    .ico-circle{ width:34px; height:34px; border-radius:50%; background: var(--canvas,#F6F7FB); display:flex; align-items:center; justify-content:center; margin-bottom:4px; }
    .drop-title{ font-weight:600; font-size:13.5px; color: var(--ink,#171B2C); }
    .drop-sub{ font-size:11.5px; color: var(--faint,#9AA1B2); }

    .video-drop.has-file .drop-title{ color: var(--green,#12875A); }
    .video-drop.has-file .ico-circle{ background: var(--green-tint,#E9F8EF); }

    /* ===== Remove/close button on upload slots ===== */
    .remove-img-btn{
        position:absolute; top:8px; right:8px; width:26px; height:26px; border-radius:8px;
        background:rgba(0,0,0,0.55); color:#fff; border:none; display:flex; align-items:center;
        justify-content:center; cursor:pointer; font-size:12px; z-index:2; transition:background .15s ease;
    }
    .remove-img-btn:hover{ background:rgba(0,0,0,0.75); }

    /* ===== Save button ===== */
    .btn-save{
        display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }

    /* ===== Existing video cards ===== */
    .op-video-card{
        display:flex; gap:16px; border:1px solid var(--line,#E9EBF2); border-radius:12px;
        padding:14px; margin-bottom:14px; background:#fff; transition:box-shadow .15s ease;
    }
    .op-video-card:hover{ box-shadow:0 4px 14px -6px rgba(15,21,38,0.12); }
    .op-video-card:last-child{ margin-bottom:0; }
    .op-video-card img, .op-video-thumb-placeholder{
        width:140px; height:90px; object-fit:cover; border-radius:9px; flex-shrink:0; background: var(--canvas,#F6F7FB);
    }
    .op-video-thumb-placeholder{ display:flex; align-items:center; justify-content:center; color: var(--faint,#9AA1B2); font-size:22px; }
    .op-video-card .body{ flex:1; min-width:0; }

    .op-video-top{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:6px; }
    .op-video-top strong{ display:block; font-size:14px; color: var(--ink,#171B2C); margin-bottom:4px; }
    .op-video-card .body p{ font-size:12.5px; color: var(--muted,#667085); margin:0; }

    .op-video-tag{ display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600; }
    .op-video-tag.ok{ color: var(--green,#12875A); }
    .op-video-tag.error{ color:#D5392F; }

    /* ===== Icon delete button (matches Service Cards / Certificates pattern) ===== */
    .icon-btn{
        display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px;
        border-radius:9px; border:none; cursor:pointer; font-size:14px; flex-shrink:0; transition:background .15s ease;
    }
    .icon-delete{ background:#FFF5F4; color:#D5392F; }
    .icon-delete:hover{ background:#E9483F; color:#fff; }

    @media (max-width:700px){
        .op-two-col{ grid-template-columns:1fr; }
        .op-video-card{ flex-direction:column; }
        .op-video-card img, .op-video-thumb-placeholder{ width:100%; height:160px; }
        .op-video-top{ flex-direction:column; gap:6px; }
    }
</style>

<script>
    function handleDropClick(el) {
        if (el.classList.contains('filled')) return;
        const inputId = el.getAttribute('data-file-input');
        const input = document.getElementById(inputId);
        if (input) input.click();
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const drop = preview.closest('.drop');

                drop.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img-btn" title="Remove image"><i class="bi bi-x-lg"></i></button>
                `;
                drop.classList.add('filled');

                drop.querySelector('.remove-img-btn').onclick = function (ev) {
                    ev.stopPropagation();
                    input.value = '';
                    drop.classList.remove('filled', 'input-error');
                    drop.innerHTML = `
                        <div class="preview-placeholder" id="${previewId}">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    `;
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showVideoFileName(input) {
        const drop = document.getElementById('op-video-drop');
        const file = input.files && input.files[0];

        if (!file) return;

        drop.classList.add('has-file');
        drop.innerHTML = `
            <div class="ico-circle"><i class="bi bi-check-circle" style="color:var(--green,#12875A);font-size:18px;"></i></div>
            <div class="drop-title">${file.name}</div>
            <div class="drop-sub">${(file.size / (1024 * 1024)).toFixed(2)} MB</div>
            <button type="button" class="remove-img-btn" title="Remove video"><i class="bi bi-x-lg"></i></button>
        `;

        drop.querySelector('.remove-img-btn').onclick = function (ev) {
            ev.stopPropagation();
            input.value = '';
            drop.classList.remove('has-file', 'input-error');
            drop.innerHTML = `
                <div class="preview-placeholder" id="op-video-preview">
                    <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                    <div class="drop-title">Click to upload</div>
                    <div class="drop-sub">or drag &amp; drop</div>
                </div>
            `;
        };
    }

    // ===== Delete confirmation (matches Service Cards / Certificates pattern) =====
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-trigger');
        if (!btn) return;

        const formId = btn.dataset.formId;
        const name = btn.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `Do you really want to delete <strong>"${name}"</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#E9483F',
            cancelButtonColor: '#9AA1B2',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const firstErrorField = document.querySelector('.input-error, .upload-btn-error');
        const firstErrorMsg = document.querySelector('.field-error');

        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.classList.add('error-flash');
            setTimeout(() => firstErrorField.classList.remove('error-flash'), 1500);
        } else if (firstErrorMsg) {
            firstErrorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

@endsection