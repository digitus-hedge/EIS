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
            <p>Add and manage operation videos with a title, description, thumbnail, and video file or YouTube link.</p>
        </div>
    </div>

    <div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-camera-video"></i></span> <span id="opFormHeading">Add New Video</span></h2>
    </div>

    <form action="{{ route('admin.about.operation.videos.store') }}" id="operation_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="opFormMethod" value="POST">

        <div class="field">
            <div class="field-top"><label class="field-label">Title <span class="req">*</span></label></div>
            <input type="text" name="title" id="opTitleInput" value="{{ old('title') }}"
                   class="{{ $errors->has('title') ? 'input-error' : '' }}"
                   placeholder="Enter video title">
            @error('title')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="field" style="margin-top:16px;">
            <div class="field-top"><label class="field-label">Description <span class="req">*</span></label></div>
            <textarea name="description" id="opDescInput" rows="3"
                      class="{{ $errors->has('description') ? 'input-error' : '' }}"
                      placeholder="Enter video description">{{ old('description') }}</textarea>
            @error('description')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="op-two-col">
            <div class="field">
                <div class="field-top"><label class="field-label">Thumbnail Image <span class="req" id="opThumbReq">*</span></label></div>
                <div class="notice caution">
                    <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                    <p><b>Recommended Size:</b>280 × 220px ·JPG, PNG, WEBP &middot; up to 10MB.</p>
                </div>
                <div class="image-slot" style="max-width:100%;">
                    <div class="drop img-slot" id="op-thumb-drop" data-file-input="op-thumb-input" onclick="handleDropClick(this)">
                        <div class="preview-placeholder" id="op-thumb-preview">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    </div>
                    <input type="file" id="op-thumb-input" name="thumbnail" accept="image/*" hidden
                           onchange="previewImage(this, 'op-thumb-preview')">
                </div>
                <span class="field-hint" id="opThumbEditHint" style="display:none; margin-top:6px;">Leave empty to keep the current thumbnail.</span>
                @error('thumbnail')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <div class="field-top"><label class="field-label">Video File <span class="req" id="opVideoReq">*</span></label></div>
                <div class="notice caution">
                    <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                    <p><b>Featured Size:</b>1920 × 1080px,  MP4, MOV, WEBM &middot; up to 20MB.</p>
                </div>
               <div class="video-drop" onclick="if(!this.classList.contains('filled')) document.getElementById('op-video-input').click()" id="op-video-drop">
                    <div class="preview-placeholder" id="op-video-preview">
                        <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                </div>
                <input type="file" id="op-video-input" name="video" accept="video/*" hidden
                       onchange="showVideoFileName(this)">
                <span class="field-hint" id="opVideoEditHint" style="display:none; margin-top:6px;">Leave empty to keep the current video.</span>
                @error('video')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="field">
            <div class="field-top" style="margin-top:15px;"><label class="field-label">YouTube Video Link</label></div>
           <input type="text" name="vedio_link" id="vedio_link" value="{{ old('vedio_link') }}"
       class="{{ $errors->has('vedio_link') ? 'input-error' : '' }}"
       placeholder="Enter YouTube Link (e.g. https://www.youtube.com/watch?v=xxxxxxxxxxx)">
            @error('vedio_link')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            <span class="field-hint" style="display:block; margin-top:6px;">Provide either a video file above OR a YouTube link.</span>
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="button" class="btn-save" id="opSubmitBtn" onclick="validateForm();">
                <i class="bi bi-check-lg"></i> Add Video
            </button>
            <button type="button" class="btn-cancel-edit" id="opCancelEditBtn" style="display:none;" onclick="cancelOpEdit()">
                Cancel
            </button>
        </div>
    </form>
</div>

    <div class="card" id="existing-videos-section">
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
        @elseif ($video->vedio_link)
            <span class="op-video-tag ok"><i class="bi bi-youtube"></i> YouTube link: <a href="{{ $video->vedio_link }}" target="_blank" rel="noopener">{{ $video->vedio_link }}</a></span>
        @else
            <span class="op-video-tag error"><i class="bi bi-exclamation-circle"></i> No video file uploaded</span>
        @endif
    </div>
    <div class="op-video-actions">


      <button type="button" class="icon-btn icon-edit op-edit-trigger"
        data-id="{{ $video->id }}"
        data-title="{{ $video->title }}"
        data-description="{{ $video->description }}"
        data-thumbnail="{{ $video->thumbnail ? asset('storage/'.$video->thumbnail) : '' }}"
        data-video-name="{{ $video->video ? basename($video->video) : '' }}"
        data-video-url="{{ $video->video ? asset('storage/'.$video->video) : '' }}"
        data-vedio-link="{{ $video->vedio_link ?? '' }}"
        data-url="{{ route('admin.about.operation.videos.update', $video) }}"
        title="Edit">
    <i class="bi bi-pencil"></i>
</button>


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

.op-video-top{
    display:flex;
    align-items:flex-start;
    gap:16px;
    margin-bottom:6px;
}

.op-video-top > div:first-child{
    flex: 1;
    min-width: 0;
}

.op-video-actions{
    display:flex;
    gap:8px;
    flex-shrink:0;
}
  .op-video-top strong{ display:block; font-size:14px; color: var(--ink,#171B2C); margin-bottom:4px; }
    .op-video-card .body p{ font-size:12.5px; color: var(--muted,#667085); margin:0; }

    .op-video-tag{ display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600; max-width:100%; flex-wrap:wrap; }
    .op-video-tag.ok{ color: var(--green,#12875A); }
    .op-video-tag.ok a{ color: var(--green,#12875A); text-decoration:underline; word-break:break-all; }
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

    .icon-edit{ background:#EFF3FF; color:#2F5FDB; }
.icon-edit:hover{ background:#2F5FDB; color:#fff; }

.btn-cancel-edit{
    font-size:13px; font-weight:600; color: var(--muted,#667085); background:none;
    border:1px solid var(--line,#E9EBF2); padding:11px 18px; border-radius:9px; cursor:pointer;
    transition:background .15s ease;
}
.btn-cancel-edit:hover{ background: var(--canvas,#F6F7FB); }

.video-drop.filled{
    border-style: solid;
    border-color: var(--green,#12875A);
    cursor: default;
}
</style>

<script>


document.getElementById('operation_form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitOperationForm();
});

function validateForm(){
    submitOperationForm();
}

document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.op-edit-trigger');
    if (editBtn) enterOpEditMode(editBtn);
});

function enterOpEditMode(btn) {
    document.getElementById('operation_form').action = btn.dataset.url;
    document.getElementById('opFormMethod').value = 'PUT';
    document.getElementById('opFormHeading').textContent = 'Edit Video';
    document.getElementById('opTitleInput').value = btn.dataset.title;
    document.getElementById('opDescInput').value = btn.dataset.description;
    document.getElementById('vedio_link').value = btn.dataset.vedioLink || '';
    document.getElementById('opThumbReq').style.display = 'none';
    document.getElementById('opVideoReq').style.display = 'none';
    document.getElementById('opThumbEditHint').style.display = 'block';
    document.getElementById('opVideoEditHint').style.display = 'block';
    document.getElementById('opSubmitBtn').innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
    document.getElementById('opCancelEditBtn').style.display = 'inline-flex';

    const thumbDrop = document.getElementById('op-thumb-drop');
    if (btn.dataset.thumbnail) {
        thumbDrop.innerHTML = `
            <img src="${btn.dataset.thumbnail}" id="op-thumb-preview" alt="Thumbnail">
            <button type="button" class="remove-img-btn" onclick="removeOpThumb(event)" title="Remove image">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        thumbDrop.classList.add('filled');
    }
    document.getElementById('op-thumb-input').value = '';

  const videoDrop = document.getElementById('op-video-drop');
if (btn.dataset.videoName) {
    videoDrop.classList.add('has-file', 'filled');
    videoDrop.innerHTML = `
        <video src="${btn.dataset.videoUrl}" controls muted style="width:100%;height:100%;object-fit:cover;"></video>
        <button type="button" class="remove-img-btn" onclick="removeOpVideo(event)" title="Remove video">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
} else if (btn.dataset.vedioLink) {
    const embedUrl = toYoutubeEmbedUrl(btn.dataset.vedioLink);
    videoDrop.classList.add('has-file', 'filled');
    videoDrop.innerHTML = `
        <iframe src="${embedUrl}" style="width:100%;height:100%;border:0;" allowfullscreen></iframe>
        <button type="button" class="remove-img-btn" onclick="removeOpVideo(event)" title="Remove video">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
}
    document.getElementById('op-video-input').value = '';

    document.querySelectorAll('#operation_form .field-error').forEach(el => el.remove());
    document.querySelectorAll('#operation_form .input-error').forEach(el => el.classList.remove('input-error'));

    document.getElementById('operation_form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function toYoutubeEmbedUrl(url) {
    const match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    return match ? `https://www.youtube.com/embed/${match[1]}` : url;
}

function removeOpThumb(event) {
    event.stopPropagation();
    document.getElementById('op-thumb-input').value = '';
    const drop = document.getElementById('op-thumb-drop');
    drop.classList.remove('filled', 'input-error');
    drop.innerHTML = `
        <div class="preview-placeholder" id="op-thumb-preview">
            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
            <div class="drop-title">Click to upload</div>
            <div class="drop-sub">or drag &amp; drop</div>
        </div>
    `;
}

function removeOpVideo(event) {
    event.stopPropagation();
    document.getElementById('op-video-input').value = '';
    document.getElementById('vedio_link').value = '';

    const drop = document.getElementById('op-video-drop');
    const videoEl = drop.querySelector('video');
    if (videoEl && videoEl.src.startsWith('blob:')) {
        URL.revokeObjectURL(videoEl.src);
    }

    drop.classList.remove('has-file', 'filled', 'input-error');
    drop.innerHTML = `
        <div class="preview-placeholder" id="op-video-preview">
            <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
            <div class="drop-title">Click to upload</div>
            <div class="drop-sub">or drag &amp; drop</div>
        </div>
    `;
}

function cancelOpEdit() {
    const form = document.getElementById('operation_form');
    form.action = "{{ route('admin.about.operation.videos.store') }}";
    document.getElementById('opFormMethod').value = 'POST';
    document.getElementById('opFormHeading').textContent = 'Add New Video';
    document.getElementById('opTitleInput').value = '';
    document.getElementById('opDescInput').value = '';
    document.getElementById('vedio_link').value = '';
    document.getElementById('opThumbReq').style.display = 'inline';
    document.getElementById('opVideoReq').style.display = 'inline';
    document.getElementById('opThumbEditHint').style.display = 'none';
    document.getElementById('opVideoEditHint').style.display = 'none';
    document.getElementById('opSubmitBtn').innerHTML = '<i class="bi bi-check-lg"></i> Add Video';
    document.getElementById('opCancelEditBtn').style.display = 'none';

    removeOpThumb({ stopPropagation(){} });
    removeOpVideo({ stopPropagation(){} });

    document.querySelectorAll('#operation_form .field-error').forEach(el => el.remove());
    document.querySelectorAll('#operation_form .input-error').forEach(el => el.classList.remove('input-error'));
}

function submitOperationForm() {
    const form = document.getElementById('operation_form');
    const formData = new FormData(form);
    const isEditMode = document.getElementById('opFormMethod').value === 'PUT';
    const submitBtn = document.getElementById('opSubmitBtn');
    const originalBtnHtml = submitBtn.innerHTML;

    document.querySelectorAll('.field-error').forEach(el => el.remove());
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        const data = await response.json().catch(() => null);

        if (response.status === 422 && data && data.errors) {
            showValidationErrors(data.errors);
            return;
        }

        if (!response.ok) {
            throw new Error('Request failed');
        }

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: (data && data.message) ? data.message : (isEditMode ? 'Video updated successfully.' : 'Video added successfully.'),
            confirmButtonColor: '#EF7B2E',
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
            confirmButtonColor: '#D5392F'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    });
}

function showValidationErrors(errors) {
    const fieldMap = {
        title: form => form.querySelector('[name="title"]'),
        description: form => form.querySelector('[name="description"]'),
        thumbnail: form => document.getElementById('op-thumb-drop'),
        video: form => document.getElementById('op-video-drop'),
        vedio_link: form => form.querySelector('[name="vedio_link"]'),
    };

    const form = document.getElementById('operation_form');

    Object.keys(errors).forEach(field => {
        const message = errors[field][0];
        const target = fieldMap[field] ? fieldMap[field](form) : null;
        if (!target) return;

        target.classList.add('input-error');

        const errorEl = document.createElement('span');
        errorEl.className = 'field-error';
        errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        target.insertAdjacentElement('afterend', errorEl);
    });

    const firstErrorField = document.querySelector('.input-error');
    if (firstErrorField) {
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

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

    // Uploading a file overrides any YouTube link previously set
    document.getElementById('vedio_link').value = '';

    const videoURL = URL.createObjectURL(file);

    drop.classList.add('has-file', 'filled');
    drop.innerHTML = `
        <video src="${videoURL}" controls muted style="width:100%;height:100%;object-fit:cover;"></video>
        <button type="button" class="remove-img-btn" title="Remove video"><i class="bi bi-x-lg"></i></button>
    `;

    drop.querySelector('.remove-img-btn').onclick = function (ev) {
        ev.stopPropagation();

        const videoEl = drop.querySelector('video');
        if (videoEl && videoEl.src.startsWith('blob:')) {
            URL.revokeObjectURL(videoEl.src);
        }

        input.value = '';
        drop.classList.remove('has-file', 'filled', 'input-error');
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