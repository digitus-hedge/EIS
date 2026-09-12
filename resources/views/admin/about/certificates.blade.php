@extends('admin.layout')
@section('title', 'Certificates')
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
        <b>Certificates</b>
    </div>

    <div class="header">
        <div>
            <h1>Certificates</h1>
            <p>Add and manage the certificates displayed on your About page.</p>
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-patch-check"></i></span> Add Certificate</h2>
        </div>

        <form action="{{ route('admin.about.certificates.store') }}" method="POST" enctype="multipart/form-data" id="certForm">
            @csrf

            <div class="field">
                <div class="field-top"><label class="field-label">Certificate Title<span class="req">*</span></label></div>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="{{ $errors->has('title') ? 'input-error' : '' }}"
                       placeholder="e.g. ISO 9001:2015">
                @error('title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="field" style="margin-top:18px;">
                <div class="field-top"><label class="field-label">Certificate Image<span class="req">*</span></label></div>

                <div class="notice caution">
                    <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                    <p><b>Recommended Size:304 × 405px</b>JPG, PNG, WEBP &middot; up to 10MB.</p>
                </div>

          <div class="image-slot" style="max-width:280px;">
    <div class="drop img-slot {{ $errors->has('image') ? 'input-error' : '' }}" data-file-input="certImage" onclick="handleDropClick(this)">
        <div class="preview-placeholder" id="certPreview">
            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
            <div class="drop-title">Click to upload</div>
            <div class="drop-sub">or drag &amp; drop</div>
        </div>
    </div>
    <input type="file" id="certImage" name="image" accept="image/*" hidden
           onchange="previewImage(this, 'certPreview')">
</div>
@error('image')
    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
@enderror
                
            </div>

            <button type="submit" class="btn-save" style="margin-top:20px;">
                <i class="bi bi-check-lg"></i> Add Certificate
            </button>
        </form>
    </div>

    <div class="card">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-collection"></i></span> Existing Certificates</h2>
        </div>

        <div class="cert-grid">
            @forelse ($certificates as $certificate)
                <div class="cert-card">
                    <img src="{{ Storage::url($certificate->image) }}" alt="{{ $certificate->title }}">
                    <div class="cert-card-body">
                        <span class="cert-card-title">{{ $certificate->title }}</span>
                        <form action="{{ route('admin.about.certificates.destroy', $certificate->id) }}" method="POST" onsubmit="return confirm('Delete this certificate?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cert-delete-btn"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="field-hint">No certificates added yet.</p>
            @endforelse
        </div>
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

    input[type=text]{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s;
    }
    input[type=text]:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#e74c3c !important; background:#fff8f8; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12.5px; margin-top:6px; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:9px 12px; margin-bottom:12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; font-size:13px; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:11.5px; }

    /* ===== Image upload slot ===== */
    .image-slot{ width:100%; }
    .drop.img-slot{
        position:relative; width:100%; height:160px;
        border:1.5px dashed var(--input-border,#DBDFEA); border-radius:12px; background:#fff;
        display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden;
        transition:border-color .15s ease;
    }
    .drop.img-slot:hover{ border-color: var(--orange-border,#F3D8C2); }
    .drop.img-slot.filled{ border-style:solid; padding:0; }
    .drop.img-slot img{ width:100%; height:100%; object-fit:cover; }

    .preview-placeholder{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; text-align:center; }
    .ico-circle{ width:34px; height:34px; border-radius:50%; background: var(--canvas,#F6F7FB); display:flex; align-items:center; justify-content:center; margin-bottom:4px; }
    .drop-title{ font-weight:600; font-size:13.5px; color: var(--ink,#171B2C); }
    .drop-sub{ font-size:11.5px; color: var(--faint,#9AA1B2); }

    /* ===== Save button ===== */
    .btn-save{
        display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }

    /* ===== Certificate grid ===== */
    .cert-grid{
        display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:16px;
    }
    .cert-card{
        background:#fff; border:1px solid var(--line,#E9EBF2); border-radius:12px; overflow:hidden; position:relative;
        box-shadow:0 1px 2px rgba(15,21,38,0.03);
    }
    .cert-card img{ width:100%; height:200px; object-fit:cover; display:block; }
    .cert-card-body{
        padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:8px;
    }
    .cert-card-title{ font-size:13.5px; font-weight:600; color: var(--ink,#171B2C); }
    .cert-delete-btn{
        background:#FFF5F4; color:#D5392F; border:1px solid #F5D3D0; width:30px; height:30px;
        border-radius:8px; cursor:pointer; flex-shrink:0; display:flex; align-items:center; justify-content:center;
        transition:background .15s ease;
    }
    .cert-delete-btn:hover{ background:#FBD5D5; }
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
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = previewId;
                preview.replaceWith(img);
                const drop = img.closest('.drop');
                if (drop) drop.classList.add('filled');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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

@endsection